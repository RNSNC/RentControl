<?php

namespace App\Parser;

class FullParser
{
    private $document;

    private $counterparty;

    private $rent;

    private $instrument;

    private $place;

    private $storage;

    public function __construct(
        DocumentParser     $document,
        CounterpartyParser $counterparty,
        RentParser         $rent,
        InstrumentParser   $instrument,
        PlaceOfUseParser   $place,
        StorageParser      $storage,
    )
    {
        $this->document = $document;
        $this->counterparty = $counterparty;
        $this->rent = $rent;
        $this->instrument = $instrument;
        $this->place = $place;
        $this->storage = $storage;
    }


    public function parserRent($dir)
    {
        $file = file_get_contents($dir);
        $objectFile = json_decode($file);
        $status = array('Opened', 'Closed');
        foreach ($status as $val)
        {
            $this->parserStatus($objectFile, $val);
        }
    }

    public function parserStatus($object, $status)
    {
        $this->counterparty->counterpartyParser($object->$status->Kontragenti);
        $this->instrument->instrumentParser($object->$status->Obekti);
        $this->place->placeOfUseParser($object->$status->MestoEkspluatacii);
        $this->storage->storageParser($object->$status->skladi);
        $this->document->documentParser($object->$status->Doki, $status);
        $this->rent->rentParser($object->$status->Prodaji);
    }
}