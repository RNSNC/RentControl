<?php

namespace App\Parser;

use App\Entity\Document;
use App\Entity\Storage;
use App\Entity\Counterparty;
use App\Entity\Rent;
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
        foreach ($documents as $doc)
        {
            $data = array();

            $data['documentId'] = $doc->id_Document;
            if ($this->getData('documentId', $data['documentId'], Document::class))
            {
                continue;
            }

            $data['storage'] = $this->getData('idSklad', $doc->id_sklad, Storage::class);
            $data['counterparty'] = $this->getData('idCounterparty',$doc->id_kontragent, Counterparty::class);

            if ($status == 'Opened') $data['status'] = true;
            else $data['status'] = false;

            $data['dok'] = $doc->SummaDok;
            $data['zalog'] = $doc->SummaZalog;
            $data['arenda'] = $doc->SummaArenda;
            $data['dateCreate'] = new \DateTime($doc->Date);
            if (isset($doc->DataZakrit)) $data['dateClose'] = new \DateTime($doc->DataZakrit);
            $data['documentNumber'] = $doc->Nomer;
            if (!$data['counterparty']) $data['counterpartyNew'] = false;
            else if ($data['counterparty']->getDateCreate()->format('Ymd') == $data['dateCreate']->format('Ymd')) {
                $data['counterpartyNew'] = true;
            }else{
                $data['counterpartyNew'] = false;
            }
            $this->protectedDocument($data);
        }
        $this->doctrine->getManager()->flush();
    }

    private function protectedDocument($data)
    {
        $document = new Document();
        $document
            ->addStorage($data['storage'])
            ->setStatus($data['status'])
            ->setCounterpartyNew($data['counterpartyNew'])
            ->setSummaDok($data['dok'])
            ->setSummaZalog($data['zalog'])
            ->setSummaArenda($data['arenda'])
            ->setDateCreate($data['dateCreate'])
            ->setDocumentId($data['documentId'])
            ->setDocumentNumber($data['documentNumber'])
        ;
        if (isset($data['counterparty'])) $document->addCounterparty($data['counterparty']);
        if (isset($data['dateClose'])) $document->setDateClose($data['dateClose']);
        $this->doctrine->getManager()->persist($document);
    }

    private function getData($key, $val, $class)
    {
        return $this->doctrine->getRepository($class)->findOneBy([$key => $val]);
    }
}