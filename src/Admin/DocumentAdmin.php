<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DocumentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('status',null,[
                'label' => 'Статус',
            ])
            ->add('counterpartyNew',null,[
                'label' => 'Новый контрагент',
            ])
            ->add('summaDok',null,[
                'label' => 'Док',
            ])
            ->add('summaZalog',null,[
                'label' => 'Залог',
            ])
            ->add('summaArenda',null,[
                'label' => 'Аренда',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
            ->add('dateClose',null,[
                'label' => 'Дата закрытия',
            ])
            ->add('documentId',null,[
                'label' => 'Ид документа',
            ])
            ->add('documentNumber',null,[
                'label' => 'Номер документа',
            ])
            ->add('storage',null,[
                'label' => 'Склад',
            ])
            ->add('counterparties',null,[
                'label' => 'Контрагент',
            ])
            ->add('rent',null,[
                'label' => 'Аренда',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('status',null,[
                'label' => 'Статус',
            ])
            ->add('counterpartyNew',null,[
                'label' => 'Новый контрагент',
            ])
            ->add('summaDok',null,[
                'label' => 'Док',
            ])
            ->add('summaZalog',null,[
                'label' => 'Залог',
            ])
            ->add('summaArenda',null,[
                'label' => 'Аренда',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
            ->add('dateClose',null,[
                'label' => 'Дата закрытия',
            ])
            ->add('documentId',null,[
                'label' => 'Ид документа',
            ])
            ->add('documentNumber',null,[
                'label' => 'Номер документа',
            ])
            ->add('storage',null,[
                'label' => 'Склад',
            ])
            ->add('counterparties',null,[
                'label' => 'Контрагент',
            ])
            ->add('rent',null,[
                'label' => 'Аренда',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('status',null,[
                'label' => 'Статус',
            ])
            ->add('counterpartyNew',null,[
                'label' => 'Новый контрагент',
            ])
            ->add('summaDok',null,[
                'label' => 'Док',
            ])
            ->add('summaZalog',null,[
                'label' => 'Залог',
            ])
            ->add('summaArenda',null,[
                'label' => 'Аренда',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
            ->add('dateClose',null,[
                'label' => 'Дата закрытия',
            ])
            ->add('documentId',null,[
                'label' => 'Ид документа',
            ])
            ->add('documentNumber',null,[
                'label' => 'Номер документа',
            ])
            ->add('storage',null,[
                'label' => 'Склад',
            ])
            ->add('counterparties',null,[
                'label' => 'Контрагент',
            ])
            ->add('rent',null,[
                'label' => 'Аренда',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('status',null,[
                'label' => 'Статус',
            ])
            ->add('counterpartyNew',null,[
                'label' => 'Новый контрагент',
            ])
            ->add('summaDok',null,[
                'label' => 'Док',
            ])
            ->add('summaZalog',null,[
                'label' => 'Залог',
            ])
            ->add('summaArenda',null,[
                'label' => 'Аренда',
            ])
            ->add('dateCreate',null,[
                'label' => 'Дата создания',
            ])
            ->add('dateClose',null,[
                'label' => 'Дата закрытия',
            ])
            ->add('documentId',null,[
                'label' => 'Ид документа',
            ])
            ->add('documentNumber',null,[
                'label' => 'Номер документа',
            ])
            ->add('storage',null,[
                'label' => 'Склад',
            ])
            ->add('counterparties',null,[
                'label' => 'Контрагент',
            ])
            ->add('rent',null,[
                'label' => 'Аренда',
            ])
        ;
    }
}