<?php

namespace App\Model\AmoCRM;

use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use App\Entity\Document;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use DateTime;

class DealCRM
{
    private ManagerRegistry $doctrine;

    private ValuesCRM $valuesCRM;

    private ContactCRM $contactCRM;

    private int $idStatusClosed = 142;//Успешно завершённые

    private int $idPipeline;//Рябиновая, Мытищи, Рублевский

    private int $idStatusContract;//Договор

    public function __construct(ManagerRegistry $doctrine, ValuesCRM $valuesCRM, ContactCRM $contactCRM)
    {
        $this->doctrine = $doctrine;
        $this->valuesCRM = $valuesCRM;
        $this->contactCRM = $contactCRM;
    }

    public function processingDealCRM(AmoCRMApiClient $client, $datetime): void
    {
        $documents = $this->doctrine->getRepository(Document::class)->findAllGreaterDate(new DateTime($datetime));
        foreach ($documents as $document ) {
            if (!$this->idSubdivision($document)) continue;
            foreach ($document->getCounterparty()->getPhone() as $phone) {
                $number = '7' . str_replace(' ', '', $phone->getNumber());
                try {
                    $modelCollection = $client->leads()->get(
                        (new ShiftLeadsFilter())
                            ->setNames('Сделка ' . $number) //поиск сделки по названию
                            ->setWith('contacts')
                            ->setOrder('created_at','desc')
                    );
                    break;
                } catch (AmoCRMApiNoContentException) {
                    continue;
                }
            }
            if (!isset($number)) continue;

            if (!isset($modelCollection)) {
                $this->newContract($client, $document, $number);
                continue;
            }
            $model = $modelCollection->first();
            unset($modelCollection);

            if ($model->getClosedAt()) { //Если уже закрыт ранее
                if ($model->getCreatedAt() == $document->getDateCreate()->getTimestamp()) continue; //совпадает дата открытия
                $this->newContract($client, $document, $number);
                continue;
            }

            if ($this->getDocumentNumber($model)) {
                if ($document->getDocumentNumber() != $this->getDocumentNumber($model)){ //другой номер документа
                    $this->newContract($client, $document, $number);
                    continue;
                }
            }

            if ($this->idStatusContract == $model->getStatusId() && $document->isStatus()){ //Уже сушествует и пока открыт
                continue;
            }else{
                if ($this->idStatusContract == $model->getStatusId() && !$document->isStatus()) { //Нужно закрыть документ
                    $oldDocument = $this->successContract($model, $document);
                }else $oldDocument = $this->editContract($model, $document, $client); //Перенести в "Договор"
            }
            $client->leads()->updateOne($oldDocument);
        }
    }

    private function getDocumentNumber(LeadModel $model): int|string|null
    {
        $values = $model->getCustomFieldsValues();
        foreach ($values as $value)
        {
            if ($value->getFieldId() == 215439){
                return $value->getValues()->first()->getValue();
            }
        }
        return null;
    }

    private function idSubdivision(Document $document): bool
    {
        switch ($document->getSubdivision()->getName())
        {
            case "Рябиновая":
                $this->idPipeline = 5603961;
                $this->idStatusContract = 49417665;
                return true;
            case "Мытищи":
                $this->idPipeline = 1952752;
                $this->idStatusContract = 29186470;
                return true;
            case "РУБЛЕВСКОЕ":
                $this->idPipeline = 2127313;
                $this->idStatusContract = 30456559;
                return true;
            default:
                return false;
        }
    }

    private function newContract(AmoCRMApiClient $client, Document $document, $number): void
    {
        $model = new LeadModel();
        $model->setName("Сделка ".$number);

        $model = $this->editContract($model, $document);

        $contact = $this->contactCRM->checkContact($client, $document->getCounterparty());
        $model->setContacts($contact);

        $client->leads()->addOne($model);
    }

    private function editContract(LeadModel $model, Document $document, AmoCRMApiClient $client = null): LeadModel
    {
        $idStatus = ($document->isStatus()) ? $this->idStatusContract : $this->idStatusClosed;
        $model->setPrice($document->getSummaArenda());
        if ($model->getPipelineId() != $this->idPipeline) {
            $model->setPipelineId($this->idPipeline);
        }
        $model->setStatusId($idStatus);
        $model->setCreatedAt($document->getDateCreate()->getTimestamp());
        if ($document->getDateClose()) $model->setClosedAt($document->getDateClose()->getTimestamp());

        $tags = $this->setTags($document->getRent());
        $values = $this->valuesCRM->setValuesDocument($document);

        if ($client) {
            $values->add($this->valuesCRM->setFindDeal());
            if($model->getContacts()) {
                $contact = $this->contactCRM->setContact(
                    $model->getContacts()->first(),
                    $document->getCounterparty(),
                );
                $client->contacts()->updateOne($contact);
            }
        }

        $model->setTags($tags);
        $model->setCustomFieldsValues($values);

        return $model;
    }

    private function successContract(LeadModel $model, Document $document): LeadModel
    {
        $model->setClosedAt($document->getDateClose()->getTimestamp());
        $model->setStatusId($this->idStatusClosed);
        return $model;
    }

    private function setTags(Collection $rents): TagsCollection
    {
        $tags = new TagsCollection();
        foreach ($rents as $rent)
        {
            $name = $rent->getInstrumentName()->getInstrumentGroup();
            $tag = (new TagModel())->setName($name);
            $tags->add($tag);
        }
        return $tags;
    }
}