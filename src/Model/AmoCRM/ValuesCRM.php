<?php

namespace App\Model\AmoCRM;

use App\Entity\Document;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\RadiobuttonCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\SelectCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\RadiobuttonCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\SelectCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\RadiobuttonCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\SelectCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;

class ValuesCRM
{
    public function setValuesDocument(Document $document): CustomFieldsValuesCollection
    {
        $values = new CustomFieldsValuesCollection();

        $numberDocument = $this->setNumberDocument($document->getDocumentNumber());
        $typePerson = $this->setTypePersonDocument($document->getCounterparty()->getTypePerson());
        $newCounterparty = $this->setNewCounterpartyDocument($document->isCounterpartyNew());
        $storage = $this->setStorageDocument($document->getStorage()->getName());

        $values
            ->add($numberDocument)
            ->add($typePerson)
            ->add($newCounterparty)
            ->add($storage)
        ;

        return $values;
    }

    private function setTypePersonDocument($type): RadiobuttonCustomFieldValuesModel
    {
        if ($type == 'Физ. лицо') {
            $id = 140361;
        }else{
            $id = 140359;
        }
        $model = new RadiobuttonCustomFieldValuesModel();
        $model->setFieldId(99483);
        $valueModel = new RadiobuttonCustomFieldValueModel();
        $valueModel->setEnumId($id);
        $model->setValues((new RadiobuttonCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    private function setNumberDocument($value): NumericCustomFieldValuesModel
    {
        $model = new NumericCustomFieldValuesModel();
        $model->setFieldId(215439);
        $valueModel = new NumericCustomFieldValueModel();
        $valueModel->setValue($value);
        $model->setValues((new NumericCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    private function setNewCounterpartyDocument($value): SelectCustomFieldValuesModel
    {
        $id = ($value) ? 881314 : 881316;
        $model = new SelectCustomFieldValuesModel();
        $model->setFieldId(691318);
        $valueModel = new SelectCustomFieldValueModel();
        $valueModel->setEnumId($id);
        $model->setValues((new SelectCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    private function setStorageDocument($nameStorage): TextCustomFieldValuesModel
    {
        $model = new TextCustomFieldValuesModel();
        $model->setFieldId(691316);
        $valueModel = new TextCustomFieldValueModel();
        $valueModel->setValue($nameStorage);
        $model->setValues((new TextCustomFieldValueCollection())->add($valueModel));
        return $model;
    }
}