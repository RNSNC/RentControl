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

            $data['id'] = $person->id_kontragent;
            $object = $this->doctrine->getRepository(Counterparty::class)->findOneBy(['idCounterparty' => $data['id']]);

            if ($object) {
                continue;
            }

            $data['title'] = $person->Naimenovanie;
            $data['titleFull'] = $person->NaimenovaniePolnoe;
            $data['inn'] = $person->INN;
            $data['typePerson'] = $person->TipLico;
            $data['dateCreate'] = $person->DataSozdaniya;
            if (isset($person->E_mail))
            {
                if ($person->E_mail) $data['email'] = $person->E_mail;
            }
            if (isset($person->Imya))
            {
                $data['name'] = $person->Imya;
                $data['surname'] = $person->Famoliya;
                $data['patronymic'] = $person->Ochestvo;
                $data['addressHome'] = $person->AdresProjivaniya;
            }
            $counterparty = $this->persistCounterparty($data);
            foreach ($person->Telefons as $phone)
            {
                $number = $phone->Telefon;
                $description = (isset($phone->RolKontLica)) ? $phone->RolKontLica : '';
                $this->persistPhone($number, $description, $counterparty);
            }
        }
        $this->entity->flush();
    }

    private function persistCounterparty($data)
    {
        $counterparty = new Counterparty();
        $counterparty
            ->setIdCounterparty($data['id'])
            ->setTitle($data['title'])
            ->setTitleFull($data['titleFull'])
            ->setInn($data['inn'])
            ->setTypePerson($data['typePerson'])
            ->setDateCreate(new \DateTime($data['dateCreate']))
        ;
        if (isset($data['email'])) $counterparty->setEmail($data['email']);
        if (isset($data['name']))
        {
            $counterparty
                ->setName($data['name'])
                ->setSurname($data['surname'])
                ->setPatronymic($data['patronymic'])
                ->setAddressHome($data['addressHome'])
            ;
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