<?php

namespace App\Parser;

use App\Entity\Counterparty;
use App\Entity\Phone;
use Doctrine\Persistence\ManagerRegistry;

class CounterpartyParser
{
    private $doctrine;

    private $entity;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->entity = $doctrine->getManager();
    }

    public function counterpartyParser($counterparty)
    {
        foreach ($counterparty as $person)
        {
            $data = array();

            $data['title'] = $person->Naimenovanie;
            $object = $this->doctrine->getRepository(Counterparty::class)->findOneBy(['title' => $data['title']]);

            if ($object) continue;

            if (isset($person->id_kontragent)) $data['id'] = $person->id_kontragent;
            if (isset($person->NaimenovaniePolnoe)) $data['titleFull'] = $person->NaimenovaniePolnoe;
            if (isset($person->INN)) $data['inn'] = $person->INN;
            if (isset($person->TipLico)) $data['typePerson'] = $person->TipLico;
            if (isset($person->DataSozdaniya)) $data['dateCreate'] = $person->DataSozdaniya;
            if (isset($person->AdresProjivaniya)) $data['addressHome'] = $person->AdresProjivaniya;
            if (isset($person->E_mail)) $data['email'] = $person->E_mail;
            if (isset($person->Imya))
            {
                $data['name'] = $person->Imya;
                $data['surname'] = $person->Famoliya;
                $data['patronymic'] = $person->Ochestvo;
            }
            $counterparty = $this->persistCounterparty($data);
            foreach ($person->Telefons as $phone)
            {
                if (isset($phone->RolKontLica))
                {
                    $number = $phone->Telefon;
                    $description = $phone->RolKontLica;
                    $this->persistPhone($number, $description, $counterparty);
                }
            }
        }
        $this->entity->flush();
    }

    private function persistCounterparty($data)
    {
        $counterparty = new Counterparty();
        if (isset($data['id']))$counterparty->setIdCounterparty($data['id']);
        $counterparty->setTitle($data['title']);
        if (isset($data['titleFull']))$counterparty->setTitleFull($data['titleFull']);
        if (isset($data['inn']))$counterparty->setInn($data['inn']);
        if (isset($data['typePerson']))$counterparty->setTypePerson($data['typePerson']);
        if (isset($data['dateCreate']))$counterparty->setDateCreate(new \DateTime($data['dateCreate']));
        if (isset($data['addressHome'])) $counterparty->setAddressHome($data['addressHome']);
        if (isset($data['email']))$counterparty->setEmail($data['email']);
        if (isset($data['name']))
        {
            $counterparty->setName($data['name']);
            $counterparty->setSurname($data['surname']);
            $counterparty->setPatronymic($data['patronymic']);
        }
        $this->entity->persist($counterparty);
        return $counterparty;
    }

    private function persistPhone($number, $description, $counterparty)
    {
        $phone = new Phone();
        $phone->setNumber($number);
        $phone->setDescription($description);
        $phone->setCounterparty($counterparty);
        $this->entity->persist($phone);
    }
}