<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class PhoneAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('number')
            ->add('description')
            ->add('counterparty')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('number')
            ->add('description')
            ->add('counterparty')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('number')
            ->add('description')
            ->add('counterparty')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('number')
            ->add('description')
            ->add('counterparty')
        ;
    }
}