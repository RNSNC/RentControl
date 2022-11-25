<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class SubdivisionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('idSubdivision')
            ->add('name')
            ->add('documents')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idSubdivision')
            ->add('name')
            ->add('documents')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('idSubdivision')
            ->addIdentifier('name')
            ->add('countDocuments')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('idSubdivision')
            ->add('name')
            ->add('countDocuments')
            ->add('documents')
        ;
    }
}