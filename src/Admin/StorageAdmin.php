<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class StorageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('idSklad')
            ->add('name')
            ->add('documents')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idSklad')
            ->add('name')
            ->add('documents')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('idSklad')
            ->addIdentifier('name')
            ->add('documentsCount')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('idSklad')
            ->add('name')
            ->add('documentsCount')
            ->add('documents')
        ;
    }
}