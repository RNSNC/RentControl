<?php

namespace App\Parser;

use App\Entity\Document;
use App\Entity\Storage;
use App\Entity\Counterparty;
use Doctrine\Persistence\ManagerRegistry;

class DocumentParser
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function documentParser($documents, $status)
    {
        foreach ($documents as $document)
        {
            $data = $this->getDataDocument($document, $status);

            $objectDocument = $this->getObject('documentId', $data['documentId'], Document::class);
            if ($objectDocument)
            {
                $this->setGlobal(
                    $objectDocument->getStorage()[0]->getName(),
                    'old',
                    $objectDocument->getCounterparties()[0]->getTypePerson(),
                );
                if ($status == 'Opened' || $objectDocument->isStatus() == false ) continue;
                $this->comparisonDocument($objectDocument, $data);
                continue;
            }
            $this->protectedDocument($data);
        }
        $this->doctrine->getManager()->flush();
    }

    private function getDataDocument($doc, $status)
    {
        $data = array();
        $data['documentId'] = $doc->id_Document;
        $data['storage'] = $this->getObject('idSklad', $doc->id_sklad, Storage::class);
        $data['counterparty'] = $this->getObject('idCounterparty',$doc->id_kontragent, Counterparty::class);

        if ($status == 'Opened') $data['status'] = true;
        else $data['status'] = false;

        $data['dok'] = $doc->SummaDok;
        $data['zalog'] = $doc->SummaZalog;
        $data['arenda'] = $doc->SummaArenda;
        $data['dateCreate'] = new \DateTime($doc->Date);
        if (isset($doc->DataZakrit)) $data['dateClose'] = new \DateTime($doc->DataZakrit);
        $data['documentNumber'] = $doc->Nomer;
        if ($data['counterparty']->getDateCreate()->format('Ymd') == $data['dateCreate']->format('Ymd')) {
            $data['counterpartyNew'] = true;
        }else{
            $data['counterpartyNew'] = false;
        }
        return $data;
    }

    private function comparisonDocument($document, $data)
    {
        $document
            ->setStatus(false)
            ->setDateClose($data['dateClose'])
        ;
        if ($document->getSummaDok() != $data['dok']) $document->setSummaDok($data['dok']);
        if ($document->getSummaZalog() != $data['zalog']) $document->setSummaZalog($data['zalog']);
        if ($document->getSummaArenda() != $data['arenda']) $document->setSummaArenda($data['arenda']);
        $this->doctrine->getManager()->persist($document);
    }

    private function protectedDocument($data)
    {
        $document = new Document();
        $document
            ->addStorage($data['storage'])
            ->addCounterparty($data['counterparty'])
            ->setStatus($data['status'])
            ->setCounterpartyNew($data['counterpartyNew'])
            ->setSummaDok($data['dok'])
            ->setSummaZalog($data['zalog'])
            ->setSummaArenda($data['arenda'])
            ->setDateCreate($data['dateCreate'])
            ->setDocumentId($data['documentId'])
            ->setDocumentNumber($data['documentNumber'])
        ;
        if (isset($data['dateClose'])) $document->setDateClose($data['dateClose']);

        $this->setGlobal(
            $data['storage']->getName(),
            'new',
            $data['counterparty']->getTypePerson(),
            $data['counterpartyNew'],
        );

        $this->doctrine->getManager()->persist($document);
    }

    private function getObject($key, $val, $class)
    {
        return $this->doctrine->getRepository($class)->findOneBy([$key => $val]);
    }

    private function setGlobal($storage, $deal, $type, $statusType = null)
    {
        if (!isset($GLOBALS['storage'][$storage]))
        {
            $GLOBALS['storage'][$storage] = [
                'deal' => [
                    'new' => 0,
                    'old' => 0,
                ],
                'fiz' => [
                    'new' => 0,
                    'old' => 0,
                ],
                'yr' => [
                    'new' => 0,
                    'old' => 0,
                ],
            ];
        }
        if ($type == 'Юр. лицо') $type = 'yr';
        else $type = 'fiz';

        if ($statusType) $statusType = 'new';
        else $statusType = 'old';

        $GLOBALS['storage'][$storage]['deal'][$deal]++;
        $GLOBALS['storage'][$storage][$type][$statusType]++;
    }
}