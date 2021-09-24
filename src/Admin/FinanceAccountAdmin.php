<?php


namespace App\Admin;

use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

class FinanceAccountAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user', ModelAutocompleteType::class, [
                'property' => 'username',
                'class' => User::class,
                'required' => false
            ])
            ->add('amount')
            ->add('active')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('user')
            ->add('amount')
            ->add('active')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('user')
        ->add('amount')
        ->add('active', null, ['editable' => true])
        ->add(
            'createdAt',
            null,
            [
                'format' => 'd.m.Y h:i:s'
            ]
        )
        ->add('updatedAt',
            null,
            [
                'format' => 'd.m.Y h:i:s'
            ])
        ->add('_action', null, [
            'actions' => [
                'edit' => [],
            ],
        ]);
    }
}