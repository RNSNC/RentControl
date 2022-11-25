<?php

namespace App\Parser;

use App\Entity\Document;
use App\Entity\Storage;
use App\Entity\Counterparty;
use App\Entity\Subdivision;
use Doctrine\Persistence\ManagerRegistry;

class DocumentParser
{
    private ManagerRegistry $doctrine;

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
                if ($status == 'Opened' || $objectDocument->isStatus() == false ) continue;
                $this->comparisonDocument($objectDocument, $data);
                continue;
            }
            $this->persistDocument($data);
        }
        $this->doctrine->getManager()->flush();
    }

    private function getDataDocument($doc, $status): array
    {
        $data = array();
        $data['documentId'] = $doc->id_Document;
        $data['storage'] = $this->getObject('idSklad', $doc->id_sklad, Storage::class);
        $data['subdivision'] = $this->getObject('idSubdivision', $doc->id_Podrazdelenie, Subdivision::class);
        $data['counterparty'] = $this->getObject('idCounterparty',$doc->id_kontragent, Counterparty::class);

        if ($status == 'Opened') $data['status'] = true;
        else $data['status'] = false;

        $data['dok'] = $doc->SummaDok;
        $data['zalog'] = $doc->SummaZalog;
        $data['arenda'] = $doc->SummaArenda;
        $data['dateCreate'] = new \DateTime($doc->DateAktaVidachi);
        if (isset($doc->DataZakrit))
        {
            $data['dateClose'] = new \DateTime($doc->DataZakrit);
            $date1 = new \DateTime($data['dateClose']->format('Y-m-d'));
            $date2 = new \DateTime($data['dateCreate']->format('Y-m-d'));
            $date3 = $date2->diff($date1);
            $data['duration'] = $date3->days+1;
        }
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
            ->setDuration($data['duration'])
        ;
        if ($document->getSummaDok() != $data['dok']) $document->setSummaDok($data['dok']);
        if ($document->getSummaZalog() != $data['zalog']) $document->setSummaZalog($data['zalog']);
        if ($document->getSummaArenda() != $data['arenda']){
            $oldSum = $document->getCounterparty()->getSumRents();
            $oldSum -= $document->getSummaArenda();
            $document->getCounterparty()->setSumRents(
                $oldSum + $data['arenda']
            );
            $document->setSummaArenda($data['arenda']);
        }
        $this->doctrine->getManager()->persist($document);
    }

    private function persistDocument($data)
    {
        $document = new Document();
        $document
            ->setStorage($data['storage'])
            ->setCounterparty($data['counterparty'])
            ->setSubdivision($data['subdivision'])
            ->setStatus($data['status'])
            ->setCounterpartyNew($data['counterpartyNew'])
            ->setSummaDok($data['dok'])
            ->setSummaZalog($data['zalog'])
            ->setSummaArenda($data['arenda'])
            ->setDateCreate($data['dateCreate'])
            ->setDocumentId($data['documentId'])
            ->setDocumentNumber($data['documentNumber'])
        ;
        if (isset($data['dateClose']))
        {
            $document->setDateClose($data['dateClose']);
            $document->setDuration($data['duration']);
        }
        $data['counterparty']
            ->setDateLastRent($data['dateCreate'])
            ->setSumRents(
                $data['counterparty']->getSumRents() + $data['arenda']
            )
        ;

        $this->doctrine->getManager()->persist($document);
    }

    private function getObject($key, $val, $class)
    {
        return $this->doctrine->getRepository($class)->findOneBy([$key => $val]);
    }
}