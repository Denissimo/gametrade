<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManagerTaskEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null,[
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add('type', EntityType::class, [
                'attr' => ['class' => 'form-select'],
                'class' => TaskType::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('deadLine',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'empty_data'  => '',
                    'required'  => false,
                    'attr' => ['class' => 'form-control datepicker'],
                ]
            )
            ->add('description', null,[
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add(
                'save',
                SubmitType::class,
                [
                    'label' => 'Save',
                    'attr' => ['class' => 'btn btn-primary mt-3']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
