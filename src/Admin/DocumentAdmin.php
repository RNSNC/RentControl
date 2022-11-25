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
            ->add('status')
            ->add('counterpartyNew')
            ->add('summaDok')
            ->add('summaZalog')
            ->add('summaArenda')
            ->add('dateCreate')
            ->add('dateClose')
            ->add('documentId')
            ->add('documentNumber')
            ->add('storage')
            ->add('counterparty')
            ->add('rent')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('status',null,[
                'label' => 'Opened',
            ])
            ->add('counterpartyNew')
            ->add('counterparty', null,[
                'show_filter' => true,
            ])
            ->add('summaDok')
            ->add('summaZalog')
            ->add('summaArenda')
            ->add('dateCreate',DateRangeFilter::class,[
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
            ])
            ->add('dateClose',DateRangeFilter::class,[
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
            ])
            ->add('documentId')
            ->add('documentNumber')
            ->add('subdivision', null, [
                'show_filter' => true,
            ])
            ->add('rent.instrumentName.instrumentGroup', null,[
                'show_filter' => true,
                'label' => 'Instrument Group',
            ])
            ->add('rent.instrumentName', null,[
                'show_filter' => true,
                'label' => 'Instrument Name',
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
            ->add('counterparty')
            ->add('status',null,[
                'label' => 'Opened',
            ])
            ->add('counterpartyNew')
            ->add('summaArenda')
            ->add('dateCreate',null,[
                'format' => 'd.m.Y',
            ])
            ->add('dateClose',null,[
                'format' => 'd.m.Y',
            ])
            ->add('duration')
            ->add('documentNumber')
            ->add('subdivision')
            ->add('rentCount')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('status',null,[
                'label' => 'Opened',
            ])
            ->add('counterpartyNew')
            ->add('summaDok')
            ->add('summaZalog')
            ->add('summaArenda')
            ->add('dateCreate')
            ->add('dateClose')
            ->add('documentId')
            ->add('documentNumber')
            ->add('storage')
            ->add('subdivision')
            ->add('counterparty')
            ->add('rent')
        ;
    }
}