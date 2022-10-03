<?php

namespace App\Model\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\ContactsFilter;
use App\Entity\Counterparty;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\ContactModel;

class ContactCRM
{
    private ValuesCRM $values;

    public function __construct(ValuesCRM $values)
    {
        $this->values = $values;
    }

    public function checkContact(AmoCRMApiClient $client, Counterparty $counterparty): ContactsCollection
    {
        $phones = $counterparty->getPhone()->getValues();
        $filter = new ContactsFilter();
        foreach ($phones as $phone)
        {
            $number = '+7'.str_replace(' ', '', $phone->getNumber());
            $filter->setQuery($number);
            try {
                $contacts = $client->contacts()->get($filter);
            }catch (AmoCRMApiNoContentException) {
                continue;
            }
            $objectNumber = $contacts
                ->first()
                ->getCustomFieldsValues()
                ->getBy('fieldId', 82553)
                ->getValues()
                ->first()
                ->getValue()
            ;
            if ($number == $objectNumber){
                $contact = $this->setContact($contacts->first(), $counterparty);
                $client->contacts()->updateOne($contact);
                break;
            }
        }
        if (!isset($contact)){
            $contact = $this->setContact(new ContactModel(), $counterparty);
            $client->contacts()->addOne($contact);
        }
        return (new ContactsCollection())->add($contact);
    }

    public function setContact(ContactModel $contact, Counterparty $counterparty): ContactModel
    {
        $contact->setName($counterparty->getTitleFull());
        $phones = $this->values->setPhonesContact($counterparty->getPhone());
        $typeCounterparty = $this->values->setTypePersonContact($counterparty->getTypePerson());
        $contact->setCustomFieldsValues(
            (new CustomFieldsValuesCollection())
            ->add($phones)
            ->add($typeCounterparty)
        );
        return $contact;
    }
}