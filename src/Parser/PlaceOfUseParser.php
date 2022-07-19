<?php

namespace App\Parser;

use App\Entity\PlaceOfUse;
use Doctrine\Persistence\ManagerRegistry;

class PlaceOfUseParser
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function placeOfUseParser($places)
    {
        foreach ($places as $place)
        {
            if (!isset($place->id_MestoArendi)) continue;
            $id = $place->id_MestoArendi;
            $name = $place->Naimenovanie;
            if ($this->doctrine->getRepository(PlaceOfUse::class)->findOneBy(['idRentPlace'=>$id]))
            {
                continue;
            }
            $placeOfUse = new PlaceOfUse();
            $placeOfUse->setName($name);
            $placeOfUse->setIdRentPlace($id);
            $this->doctrine->getManager()->persist($placeOfUse);
        }
        $this->doctrine->getManager()->flush();
    }
}