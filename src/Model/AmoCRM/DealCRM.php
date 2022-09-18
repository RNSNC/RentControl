<?php

namespace App\Model\AmoCRM;

use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\LeadsFilter;
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

    public function processingDealCRM(AmoCRMApiClient $client)
    {
        $documents = $this->doctrine->getRepository(Document::class)->findAllGreaterDate(new DateTime("01-08-2022"));
        foreach ($documents as $document )
        {
            if ($document->getStorage()->getName() != "Рябиновая") continue;
            if (!$this->idStorage($document)) continue;
            try {
                $modelCollection = $client->leads()->get(
                    (new LeadsFilter())
                        ->setNames('Договор '.$document->getDocumentNumber())
                        ->setPipelineIds($this->idPipeline)
                );
                $model = $modelCollection->first();
            }catch (AmoCRMApiNoContentException) {
                $model = $this->newDocument($client, $document);
                $client->leads()->addOne($model);
                continue;
            }
            if ($document->isStatus() || $model->getClosedAt()) continue;//Если открытый или уже закрыт ранее
            $oldDocument = $this->oldDocument($model, $document);
            $client->leads()->update($oldDocument);
        }
    }

    private function idStorage(Document $document): bool
    {
        switch ($document->getStorage()->getName())
        {
            case "Рябиновая":
                $this->idPipeline = 5603961;
                $this->idStatusContract = 49417665;
                return true;
            case "Мытищи":
                $this->idPipeline = 1952752;
                $this->idStatusContract = 29186470;
                return true;
            case "РУБЛЕВСКИЙ":
                $this->idPipeline = 2127313;
                $this->idStatusContract = 30456559;
                return true;
            default:
                return false;
        }
    }

    private function oldDocument(LeadModel $model, Document $document): LeadsCollection
    {
        $model->setClosedAt($document->getDateClose()->getTimestamp());
        $model->setStatusId($this->idStatusClosed);
        return (new LeadsCollection())->add($model);
    }

    private function newDocument(AmoCRMApiClient $client, Document $document): LeadModel
    {
        $idStatus = ($document->isStatus()) ? $this->idStatusContract : $this->idStatusClosed;
        $model = new LeadModel();
        $model->setName("Договор ".$document->getDocumentNumber());
        $model->setPrice($document->getSummaArenda());
        $model->setPipelineId($this->idPipeline);
        $model->setStatusId($idStatus);
        $model->setCreatedAt($document->getDateCreate()->getTimestamp());
        if ($document->getDateClose()) $model->setClosedAt($document->getDateClose()->getTimestamp());

        $tags = $this->setTags($document->getRent());
        $values = $this->valuesCRM->setValuesDocument($document);

        $model->setTags($tags);
        $model->setCustomFieldsValues($values);

        $contact = $this->contactCRM->checkContact($client, $document->getCounterparty());
        $model->setContacts($contact);

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