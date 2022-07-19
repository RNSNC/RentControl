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
            ->add('klw',null,[
                'label' => 'Количество',
            ])
            ->add('stavka',null,[
                'label' => 'Ставка',
            ])
            ->add('zalog',null,[
                'label' => 'Залог',
            ])
            ->add('instrumentName',null,[
                'label' => 'Название инструмента',
            ])
            ->add('instrumentName.instrumentGroup',null,[
                'label' => 'Название группы',
            ])
            ->add('document',null,[
                'label' => 'Документ',
            ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('klw',null,[
                'label' => 'Количество',
            ])
            ->add('stavka',null,[
                'label' => 'Ставка',
            ])
            ->add('zalog',null,[
                'label' => 'Залог',
            ])
            ->add('instrumentName',null,[
                'label' => 'Название инструмента',
            ])
            ->add('instrumentName.instrumentGroup',null,[
                'label' => 'Название группы',
            ])
            ->add('document',null,[
                'label' => 'Документ',
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('klw',null,[
                'label' => 'Количество',
            ])
            ->add('stavka',null,[
                'label' => 'Ставка',
            ])
            ->add('zalog',null,[
                'label' => 'Залог',
            ])
            ->add('instrumentName',null,[
                'label' => 'Название инструмента',
            ])
            ->add('instrumentName.instrumentGroup',null,[
                'label' => 'Название группы',
            ])
            ->add('document',null,[
                'label' => 'Документ',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('klw',null,[
                'label' => 'Количество',
            ])
            ->add('stavka',null,[
                'label' => 'Ставка',
            ])
            ->add('zalog',null,[
                'label' => 'Залог',
            ])
            ->add('instrumentName',null,[
                'label' => 'Название инструмента',
            ])
            ->add('instrumentName.instrumentGroup',null,[
                'label' => 'Название группы',
            ])
            ->add('document',null,[
                'label' => 'Документ',
            ])
        ;
    }
}