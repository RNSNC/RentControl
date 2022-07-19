<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CounterpartyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('idCounterparty',null,[
                'label' => 'Ид контрагента',
            ])
            ->add('title',null,[
                'label' => 'Наименование',
            ])
            ->add('titleFull',null,[
                'label' => 'Полное наименование',
            ])
            ->add('inn',null,[
                'label' => 'ИНН',
            ])
            ->add('typePerson',null,[
                'label' => 'Тип лица',
            ])
            ->add('phone',null,[
                'label' => 'Телефон',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idCounterparty',null,[
                'label' => 'Ид контрагента',
            ])
            ->add('title',null,[
                'label' => 'Наименование',
            ])
            ->add('titleFull',null,[
                'label' => 'Полное наименование',
            ])
            ->add('inn',null,[
                'label' => 'ИНН',
            ])
            ->add('typePerson',null,[
                'label' => 'Тип лица',
            ])
            ->add('phone',null,[
                'label' => 'Телефон',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('idCounterparty',null,[
                'label' => 'Ид контрагента',
            ])
            ->add('title',null,[
                'label' => 'Наименование',
            ])
            ->add('titleFull',null,[
                'label' => 'Полное наименование',
            ])
            ->add('inn',null,[
                'label' => 'ИНН',
            ])
            ->add('typePerson',null,[
                'label' => 'Тип лица',
            ])
            ->add('phone',null,[
                'label' => 'Телефон',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('idCounterparty',null,[
                'label' => 'Ид контрагента',
            ])
            ->add('title',null,[
                'label' => 'Наименование',
            ])
            ->add('titleFull',null,[
                'label' => 'Полное наименование',
            ])
            ->add('inn',null,[
                'label' => 'ИНН',
            ])
            ->add('typePerson',null,[
                'label' => 'Тип лица',
            ])
            ->add('phone',null,[
                'label' => 'Телефон',
            ])
        ;
        if($this->getSubject()->getName())
        {
            $show
                ->add('name',null,[
                    'label' => 'Имя',
                ])
                ->add('surname',null,[
                    'label' => 'Фамилия',
                ])
                ->add('patronymic',null,[
                    'label' => 'Отчество',
                ])
            ;
        }
        $show
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
            ->add('documents', null,[
                'label' => 'Документы',
            ])
        ;
    }
}