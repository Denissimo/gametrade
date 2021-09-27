<?php


namespace App\Admin;

use App\Entity\FinanceAccount;
use App\Entity\Transactions;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TransactionsAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('finance', ModelType::class, [
                'class' => FinanceAccount::class,
                'choice_translation_domain' => false,
                'required' => true
            ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Transactions::$types)
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(Transactions::$statuses)
            ])
//            ->add('account')
            ->add('amount');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('finance')
            ->add('amount');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->add('finance')
            ->add('type', 'choice', [
                'choices' => Transactions::$types
            ])
            ->add('status', 'choice', [
                'choices' => Transactions::$statuses
            ])
            ->add('amount')
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