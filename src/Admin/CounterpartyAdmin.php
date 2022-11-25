<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateRangeFilter;

class CounterpartyAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('idCounterparty')
            ->add('title')
            ->add('titleFull')
            ->add('inn')
            ->add('typePerson')
            ->add('phone')
            ->add('dateCreate')
            ->add('dateLastRent')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idCounterparty')
            ->add('titleFull',null,[
                'label' => 'Title',
            ])
            ->add('inn')
            ->add('typePerson')
            ->add('dateCreate',DateRangeFilter::class,[
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
            ])
            ->add('dateLastRent',DateRangeFilter::class,[
                'field_options' => [
                    'field_options' => [
                        'widget' => 'single_text',
                    ],
                ],
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
            ->add('titleFull',null,[
                'label' => 'Title',
            ])
            ->add('typePerson')
            ->add('phone')
            ->add('dateCreate',null,[
                'format' => 'd.m.Y',
            ])
            ->add('dateLastRent',null,[
                'format' => 'd.m.Y',
            ])
            ->add('sumRents',null,[
                'sort_field_mapping' => [
                    'fieldName' => 'sumRents+0',
                ],
            ])
            ->add('countRents',null,[
                'template' => 'Sonata/count_rents.html.twig',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('idCounterparty')
            ->add('title')
            ->add('titleFull')
            ->add('inn')
            ->add('typePerson')
            ->add('phone')
        ;
        if($this->getSubject()->getName())
        {
            $show
                ->add('name')
                ->add('surname')
                ->add('patronymic')
            ;
        }
        $show
            ->add('dateCreate')
            ->add('dateLastRent')
            ->add('sumRents')
            ->add('documents')
        ;
    }
}