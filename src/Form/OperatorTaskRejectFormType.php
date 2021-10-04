<?php

namespace App\Form;

use App\Entity\Task;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperatorTaskRejectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('status', HiddenType::class, ['data' => Task::STATUS_REJECTED])
            ->add(
                'reject',
                SubmitType::class,
                [
                    'label' => 'Reject',
                    'attr' => ['class' => 'btn btn-warning mt-3']
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
