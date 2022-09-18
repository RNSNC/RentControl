<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class RentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('klw')
            ->add('stavka')
            ->add('zalog')
            ->add('instrumentName')
            ->add('instrumentName.instrumentGroup')
            ->add('document')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('klw')
            ->add('stavka')
            ->add('zalog')
            ->add('instrumentName')
            ->add('instrumentName.instrumentGroup')
            ->add('document')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('klw')
            ->add('stavka')
            ->add('zalog')
            ->add('instrumentName')
            ->add('instrumentName.instrumentGroup')
            ->add('document')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('klw')
            ->add('stavka')
            ->add('zalog')
            ->add('instrumentName')
            ->add('instrumentName.instrumentGroup')
            ->add('document')
        ;
    }
}