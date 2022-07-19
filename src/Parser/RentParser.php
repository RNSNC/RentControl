<?php

namespace App\Parser;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\InstrumentName;
use App\Entity\Document;
use App\Entity\Rent;

class RentParser
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function rentParser($prodaji)
    {
        foreach ($prodaji as $object)
        {
            $idDocument = $object->id_Document;
            $document = $this->getData(Document::class, 'documentId', $idDocument);
            foreach ($object->Arenda as $rent)
            {
                $idRent = $rent->id_ObektArenda;
                $instrument = $this->getData(InstrumentName::class,'idObjectRent', $idRent);

                if ($this->getData(Rent::class, 'document', $document, 'instrumentName', $instrument))
                {
                    continue;
                }

                $klw = $rent->Klw;
                $stavka = $rent->Stavka;
                $zalog = $rent->Zalog;
                $data = array(
                    'document' => $document,
                    'instrument' => $instrument,
                    'klw' => $klw,
                    'stavka' => $stavka,
                    'zalog' => $zalog,
                );
                $this->persistRent($data);
            }
        }
        $this->doctrine->getManager()->flush();
    }

    private function persistRent($data)
    {
        $rent = new Rent();
        $rent
            ->setDocument($data['document'])
            ->setInstrumentName($data['instrument'])
            ->setKlw($data['klw'])
            ->setStavka($data['stavka'])
            ->setZalog($data['zalog'])
        ;
        $this->doctrine->getManager()->persist($rent);
    }

    private function getData($class, $key, $val, $key2 = null, $val2 = null)
    {
        if ($key2 != null && $val2 != null)
        {
            return $this->doctrine->getRepository($class)->findOneBy([
                $key => $val,
                $key2 => $val2,
            ]);
        }
        return $this->doctrine->getRepository($class)->findOneBy([$key => $val]);
    }
}