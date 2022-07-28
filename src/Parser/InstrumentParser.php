<?php

namespace App\Parser;

use App\Entity\InstrumentName;
use App\Entity\InstrumentGroup;
use Doctrine\Persistence\ManagerRegistry;

class InstrumentParser
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function instrumentParser($instruments)
    {
        foreach ($instruments as $instrument)
        {
            $id = $instrument->id_ObektArenda;
            $instrumentName = $this->doctrine->getRepository(InstrumentName::class)->findOneBy(['idObjectRent'=> $id]);
            if ($instrumentName) continue;

            $group = $instrument->NaimenovanieGruppa;
            $instrumentGroup = $this->doctrine->getRepository(InstrumentGroup::class)->findOneBy(['name'=>$group]);
            if (!$instrumentGroup)
            {
                $instrumentGroup = new InstrumentGroup();
                $instrumentGroup->setName($group);
                $this->doctrine->getManager()->persist($instrumentGroup);
                $this->doctrine->getManager()->flush();
            }

            $name = (isset($instrument->Naimenovanie)) ? $instrument->Naimenovanie : $instrument->Наименование;
            $instrumentName = new InstrumentName();
            $instrumentName->setName($name);
            $instrumentName->setInstrumentGroup($instrumentGroup);
            $instrumentName->setIdObjectRent($id);
            $this->doctrine->getManager()->persist($instrumentName);
        }
        $this->doctrine->getManager()->flush();
    }
}