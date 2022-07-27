<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;

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
            ->add('dateCreate',DateRangeFilter::class,[
                'label' => 'Дата создания',
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
            ])
            ->add('dateClose',DateRangeFilter::class,[
                'label' => 'Дата закрытия',
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
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
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('_action', 'actions',[
                'actions' => [
                    'show' => [],
                ],
                'label' => false,
            ])
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
                'format' => 'd.m.Y',
            ])
            ->add('dateClose',null,[
                'label' => 'Дата закрытия',
                'format' => 'd.m.Y',
            ])
            ->add('documentNumber',null,[
                'label' => 'Номер документа',
            ])
            ->add('storage',null,[
                'label' => 'Склад',
            ])
            ->add('rentCount', null,[
                'label' => 'Кол-во аренд'
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