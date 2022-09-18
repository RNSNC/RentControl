<?php

namespace App\Parser;

use App\Entity\Subdivision;
use Doctrine\Persistence\ManagerRegistry;

class SubdivisionParser
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function subdivisionParser($subdivisions)
    {
        foreach ($subdivisions as $subdivision)
        {
            $id = $subdivision->id_Podrazdelenie;
            if ($this->doctrine->getRepository(Subdivision::class)->findOneBy(['idSubdivision'=>$id]))
            {
                continue;
            }
            $subdivisionNew = new Subdivision();
            $subdivisionNew->setIdSubdivision($id);
            if (isset($subdivision->Naimenovamie)) $subdivisionNew->setName($subdivision->Naimenovamie);
            $this->doctrine->getManager()->persist($subdivisionNew);
        }
        $this->doctrine->getManager()->flush();
    }
}