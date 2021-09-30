<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ManagerTaskOfferFormType extends AbstractType
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
        /** @var User[]|array $operators */
        $operators = $this->entityManager->getRepository(User::class)
            ->loadByRoleAsArray(User::ROLE_OPERATOR);

        $builder
            ->add('operator', ChoiceType::class, [
                'choices' => array_flip($operators),
                'choice_translation_domain' => false,
                'label' => false,
                'attr' => ['class' => 'form-select col-4'],
            ])
            ->add('taskId', HiddenType::class, [
                'data' => 1,
            ])
            ->add(
                'offer',
                SubmitType::class,
                [
                    'label' => 'To Offer',
                    'attr' => ['class' => 'btn btn-primary']
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
