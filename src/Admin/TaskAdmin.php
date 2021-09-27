<?php


namespace App\Admin;

use App\Entity\Account;
use App\Entity\Task;
use App\Entity\TaskType;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('head', ModelType::class, [
                'class' => User::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('operator', ModelType::class, [
                'class' => User::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('type', ModelType::class, [
                'class' => TaskType::class,
                'choice_translation_domain' => false,
                'required' => true
            ])
            ->add('account', ModelType::class, [
                'class' => Account::class,
                'choice_translation_domain' => false,
                'required' => false
            ])
            ->add('deadLine',
            DateTimePickerType::class, [
                'required'       => false,
                'dp_side_by_side'       => true,
                'dp_use_current'        => true,
                'dp_use_seconds'        => false,
                'dp_collapse'           => true,
                'dp_calendar_weeks'     => false,
                'dp_view_mode'          => 'days',
                'dp_min_view_mode'      => 'days',
                'datepicker_use_button' => true,
                'dp_show_today' => true
            ])
            ->add('status', ChoiceType::class, [
                'choice_translation_domain' => false,
                'choices' => array_flip(Task::$statuses)
            ])
            ->add('description');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('head')
            ->add('operator')
            ->add('type')
            ->add('account')
            ->add('status');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->add('name')
            ->add('head')
            ->add('operator')
            ->add('type')
            ->add('account')
            ->add('deadLine')
            ->add('status', 'choice', [
                'choices' => Account::$statuses
            ])
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