<?php

namespace App\Model\AmoCRM;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\ContactsFilter;
use App\Entity\Counterparty;
use Doctrine\Common\Collections\Collection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;

class ContactCRM
{
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
            $contact = $contacts->first();
            $objectNumber = $contact
                ->getCustomFieldsValues()
                ->getBy('fieldId', 82553)
                ->getValues()
                ->first()
                ->getValue()
            ;
            if ($number == $objectNumber){
                return (new ContactsCollection())->add($contact);
            }
        }
        $contact = $this->setContact($counterparty);
        $client->contacts()->addOne($contact);
        return (new ContactsCollection())->add($contact);
    }

    private function setContact(Counterparty $counterparty): ContactModel
    {
        $contact = new ContactModel();
        $contact->setName($counterparty->getTitleFull());
        $phones = $this->setContactPhones($counterparty->getPhone());
        $contact->setCustomFieldsValues($phones);
        return $contact;
    }

    private function setContactPhones(Collection $phones): CustomFieldsValuesCollection
    {
        $model = new MultitextCustomFieldValuesModel();
        $model->setFieldId(82553);
        $valueCollection = new MultitextCustomFieldValueCollection();
        foreach ($phones as $phone)
        {
            $valueModel = new MultitextCustomFieldValueModel();
            $valueModel->setValue('+7'.$phone->getNumber());
            $valueCollection->add($valueModel);
        }
        $model->setValues($valueCollection);
        return (new CustomFieldsValuesCollection())->add($model);
    }
}