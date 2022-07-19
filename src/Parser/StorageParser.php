<?php

namespace App\Parser;

use App\Entity\Storage;
use Doctrine\Persistence\ManagerRegistry;

class StorageParser
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function storageParser($storages)
    {
        foreach ($storages as $storage)
        {
            $id = $storage->id_sklad;
            if (isset($storage->Naimenovanie)) $name = $storage->Naimenovanie;
            if ($this->doctrine->getRepository(Storage::class)->findOneBy(['idSklad'=>$id]))
            {
                continue;
            }
            $storageNew = new Storage();
            if (isset($storage->Naimenovanie)) $storageNew->setName($name);
            $storageNew->setIdSklad($id);
            $this->doctrine->getManager()->persist($storageNew);
        }
        $this->doctrine->getManager()->flush();
    }
}