<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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

        $builder
            ->add('operator', EntityType::class, [
                'class' => User::class,
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('u');
                    return $qb->andWhere(
                        $qb->expr()->like('u.roles', ':roles')
                    )
                        ->setParameter('roles', '%"' . User::ROLE_OPERATOR . '"%')
                        ->orderBy('u.id', 'ASC');
                },
                'label' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('status', HiddenType::class, ['data' => Task::STATUS_OFFERED])
            ->add(
                'offer',
                SubmitType::class,
                [
                    'label' => 'Предложить оператору задачу',
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
