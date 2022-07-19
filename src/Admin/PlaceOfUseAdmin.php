<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PlaceOfUseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name',null,[
                'label' => 'Место',
            ])
            ->add('idRentPlace',null,[
                'label' => 'Ид места',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name',null,[
                'label' => 'Место',
            ])
            ->add('idRentPlace',null,[
                'label' => 'Ид места',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('name',null,[
                'label' => 'Место',
            ])
            ->add('idRentPlace',null,[
                'label' => 'Ид места',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('name',null,[
                'label' => 'Место',
            ])
            ->add('idRentPlace',null,[
                'label' => 'Ид места',
            ])
        ;
    }
}