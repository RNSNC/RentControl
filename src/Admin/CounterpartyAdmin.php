<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

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
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('idCounterparty')
            ->add('title')
            ->add('titleFull')
            ->add('inn')
            ->add('typePerson')
            ->add('dateCreate')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('idCounterparty')
            ->addIdentifier('title')
            ->addIdentifier('titleFull')
            ->add('inn')
            ->add('typePerson')
            ->add('phone')
            ->add('dateCreate')
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
            ->add('documents')
        ;
    }
}