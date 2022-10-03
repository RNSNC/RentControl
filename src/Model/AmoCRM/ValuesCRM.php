<?php

namespace App\Model\AmoCRM;

use App\Entity\Document;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CheckboxCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CheckboxCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
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
use Doctrine\Common\Collections\Collection;

class ValuesCRM
{
    public function setValuesDocument(Document $document): CustomFieldsValuesCollection
    {
        $values = new CustomFieldsValuesCollection();

        $numberDocument = $this->setNumberDocument($document->getDocumentNumber());
        $typePerson = $this->setTypePersonDocument($document->getCounterparty()->getTypePerson());
        $newCounterparty = $this->setNewCounterpartyDocument($document->isCounterpartyNew());
        $subdivision = $this->setSubdivisionDocument($document->getSubdivision()->getName());

        $values
            ->add($numberDocument)
            ->add($typePerson)
            ->add($newCounterparty)
            ->add($subdivision)
        ;

        return $values;
    }

    private function setTypePersonDocument($type): RadiobuttonCustomFieldValuesModel //  Физ/Юр лицо
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

    private function setNumberDocument($value): NumericCustomFieldValuesModel //  Номер документа
    {
        $model = new NumericCustomFieldValuesModel();
        $model->setFieldId(215439);
        $valueModel = new NumericCustomFieldValueModel();
        $valueModel->setValue($value);
        $model->setValues((new NumericCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    private function setNewCounterpartyDocument($value): SelectCustomFieldValuesModel //  Новый контрагент
    {
        $id = ($value) ? 881314 : 881316;
        $model = new SelectCustomFieldValuesModel();
        $model->setFieldId(691318);
        $valueModel = new SelectCustomFieldValueModel();
        $valueModel->setEnumId($id);
        $model->setValues((new SelectCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    private function setSubdivisionDocument($nameStorage): TextCustomFieldValuesModel  //  Подразделение
    {
        $model = new TextCustomFieldValuesModel();
        $model->setFieldId(691316);
        $valueModel = new TextCustomFieldValueModel();
        $valueModel->setValue($nameStorage);
        $model->setValues((new TextCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    public function setFindDeal(): CheckboxCustomFieldValuesModel  // Сделка найдена
    {
        $model = new CheckboxCustomFieldValuesModel();
        $model->setFieldId(691546);
        $valueModel = new CheckboxCustomFieldValueModel();
        $valueModel->setValue(true);
        $model->setValues((new CheckboxCustomFieldValueCollection())->add($valueModel));
        return $model;
    }

    public function setPhonesContact(Collection $phones): MultitextCustomFieldValuesModel  // Телефоны контакта
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
        return $model;
    }

    public function setTypePersonContact($type): SelectCustomFieldValuesModel // Физ/Юр лицо контрагента
    {
        $fizId = 251067;
        $yrId = 251069;
        $id = ($type == 'Физ. лицо') ? $fizId : $yrId;

        $model = new SelectCustomFieldValuesModel();
        $model->setFieldId(167787);
        $valueModel = new SelectCustomFieldValueModel();
        $valueModel->setEnumId($id);
        $model->setValues((new SelectCustomFieldValueCollection())->add($valueModel));
        return $model;
    }
}