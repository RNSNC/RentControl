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
            ->add('idSklad',null,[
                'label'=> 'Ид склада',
            ])
            ->add('name',null,[
                'label'=> 'Имя',
            ])
            ->add('documents',null,[
                'label'=> 'Документы',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idSklad',null,[
                'label'=> 'Ид склада',
            ])
            ->add('name',null,[
                'label'=> 'Имя',
            ])
            ->add('documents',null,[
                'label'=> 'Документы',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('idSklad',null,[
                'label'=> 'Ид склада',
            ])
            ->addIdentifier('name',null,[
                'label'=> 'Имя',
            ])
            ->add('documentsCount',null,[
                'label'=> 'Документы',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('idSklad',null,[
                'label'=> 'Ид склада',
            ])
            ->add('name',null,[
                'label'=> 'Имя',
            ])
            ->add('documents',null,[
                'label'=> 'Документы',
            ])
        ;
    }
}