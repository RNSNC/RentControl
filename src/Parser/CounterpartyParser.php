<?php

namespace App\Parser;

use App\Entity\Counterparty;
use App\Entity\Phone;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use function Composer\Autoload\includeFile;

class CounterpartyParser
{
    private ManagerRegistry $doctrine;

    private ObjectManager $entity;

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
            $data['phone'] = $person->Telefons;
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
            $this->setCounterparty($data);
        }
        $this->entity->flush();
    }

    private function setCounterparty($data): void
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
        $numbers = array();
        foreach ($data['phone'] as $phone)
        {
            $numbers[$phone->Telefon] = (isset($phone->RolKontLica)) ? $phone->RolKontLica : '';
        }

        foreach ($numbers as $number => $description)
        {
            $this->setPhone($number, $description, $counterparty);
        }
        $this->entity->persist($counterparty);
    }

    private function setPhone($number, $description, $counterparty): void
    {
        $phone = new Phone();
        $phone->setNumber($number);
        $phone->setDescription($description);
        $phone->setCounterparty($counterparty);
        $this->entity->persist($phone);
    }
}