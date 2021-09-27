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

class TaskTypeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('code')
            ->add('name')
            ->add('award')
            ->add('overduePenalty')
            ->add('rejectPenalty')
            ->add('defaultDuration')
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('code')
            ->add('name')
            ->add('award')
            ->add('overduePenalty')
            ->add('rejectPenalty')
            ->add('defaultDuration');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->add('code')
            ->add('name')
            ->add('award')
            ->add('overduePenalty')
            ->add('rejectPenalty')
            ->add('defaultDuration')
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