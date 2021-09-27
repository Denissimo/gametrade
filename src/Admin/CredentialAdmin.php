<?php


namespace App\Admin;

use App\Entity\Account;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CredentialAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('account', ModelType::class, [
                'choice_translation_domain' => false,
                'class' => Account::class,
                'required' => true
            ])
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('isTest')
            ->add('isActive')
            ->add('validTill',
                DatePickerType::class, [
                    'required' => false,
                    'dp_side_by_side' => true,
                    'dp_use_current' => true,
                    'dp_collapse' => true,
                    'dp_calendar_weeks' => false,
                    'dp_view_mode' => 'days',
                    'dp_min_view_mode' => 'days',
                    'datepicker_use_button' => true,
                    'dp_show_today' => true,
                ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('password')
            ->add('isTest')
            ->add('isActive')
            ->add('validTill')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('id')
            ->add('username')
            ->add('password')
            ->add('isTest', null, ['editable' => true])
            ->add('isActive', null, ['editable' => true])
            ->add('validTill',
                null,
                [
                    'format' => 'd.m.Y'
                ]
            )
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