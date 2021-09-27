<?php


namespace App\Admin;

use App\Entity\Account;
use App\Entity\FinanceAccount;
use App\Entity\Game;
use App\Entity\Transaction;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('owner', ModelType::class, [
                'class' => User::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('operator', ModelType::class, [
                'class' => User::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('game', ModelType::class, [
                'class' => Game::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('externalId')
            ->add('status', ChoiceType::class, [
                'choice_translation_domain' => false,
                'choices' => array_flip(Account::$statuses)
            ])
            ->add('price');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('owner')
            ->add('operator')
            ->add('game')
            ->add('externalId')
            ->add('status')
            ->add('price');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->add('owner')
            ->add('operator')
            ->add('game')
            ->add('externalId')
            ->add('status', 'choice', [
                'choices' => Account::$statuses
            ])
            ->add('price')
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