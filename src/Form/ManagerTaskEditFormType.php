<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Task;
use App\Entity\TaskType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ManagerTaskEditFormType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ManagerTaskOfferFormType constructor.
     *
     * @param EntityManagerInterface $EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $EntityManagerInterface)
    {
        $this->entityManager = $EntityManagerInterface;
    }

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
//                    'input'  => 'datetime_immutable',
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
