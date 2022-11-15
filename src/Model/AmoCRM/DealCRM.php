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
    private AmoCRMApiClient $client;

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
        $this->client = $client;
        $documents = $this->doctrine->getRepository(Document::class)->findAllGreaterDate(new DateTime($datetime));
        foreach ($documents as $document ) {
            $this->idSubdivision($document); //сохраняем ид склада
            $data = $this->findDocument($document->getCounterparty()->getPhone()); //находим сделку и номер

            if (!isset($data['number'])) continue;

            if (!isset($data['leads'])) { //Ничего не нашло, создаём новую
                $this->newContract($document, $data['number']);
                continue;
            }

            foreach ($data['leads'] as $lead) //возможно, найдем по номеру документа
            {
                if ($this->getDocumentNumber($lead) == $document->getDocumentNumber()){
                    $model = $lead;
                    break;
                }
            }

            if (!isset($model)) $model = $data['leads']->first();
            unset($data['leads']);

            if ($this->getDocumentNumber($model)) {
                if ($document->getDocumentNumber() != $this->getDocumentNumber($model)){ //другой номер документа
                    $this->newContract($document, $data['number']);
                }elseif ($this->idStatusContract == $model->getStatusId() && !$document->isStatus()){//Нужно закрыть документ
                    $this->successContract($model, $document);
                }//ничего не меняется
            } else {
                $this->client->leads()->updateOne(//Перенести в "Договор"
                    $this->editContract($model, $document, true)
                );
            }
            unset($model);
        }
    }

    private function findDocument($phones): array //поиск
    {
        foreach ($phones as $phone) {
            $number = '7' . str_replace(' ', '', $phone->getNumber());
            try {
                return ['leads' => $this->client->leads()->get(
                    (new ShiftLeadsFilter())
                        ->setNames('Сделка ' . $number) //поиск сделки по названию
                        ->setWith('contacts')
                        ->setOrder('created_at','desc')
                ), 'number' => $number];
            } catch (AmoCRMApiNoContentException) {
                continue;
            }
        }
        if (isset($number)) return ['number' => $number];
        return [];
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

    private function idSubdivision(Document $document): void
    {
        switch ($document->getSubdivision()->getName())
        {
            case "Рябиновая":
                $this->idPipeline = 5603961;
                $this->idStatusContract = 49417665;
                break;
            case "Мытищи":
                $this->idPipeline = 1952752;
                $this->idStatusContract = 29186470;
                break;
            case "РУБЛЕВСКОЕ":
                $this->idPipeline = 2127313;
                $this->idStatusContract = 30456559;
                break;
            default:
                break;
        }
    }

    private function newContract(Document $document, $number): void
    {
        $model = new LeadModel();
        $model->setName("Сделка ".$number);

        $model = $this->editContract($model, $document);

        $contact = $this->contactCRM->checkContact($this->client, $document->getCounterparty());
        $model->setContacts($contact);

        $this->client->leads()->addOne($model);
    }

    private function editContract(LeadModel $model, Document $document, bool $contract = false): LeadModel
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

        if ($contract) {
            $values->add($this->valuesCRM->setFindDeal());
            if($model->getContacts()) {
                $contact = $this->contactCRM->setContact(
                    $model->getContacts()->first(),
                    $document->getCounterparty(),
                );
                $this->client->contacts()->updateOne($contact);
            }
        }

        $model->setTags($tags);
        $model->setCustomFieldsValues($values);

        return $model;
    }

    private function successContract(LeadModel $model, Document $document): void
    {
        $model->setClosedAt($document->getDateClose()->getTimestamp());
        $model->setStatusId($this->idStatusClosed);
        $this->client->leads()->updateOne($model);
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