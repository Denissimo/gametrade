<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Credential;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperatorCredentialAddFormType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $data)
    {
        $builder
            ->add('username', null,[
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add('password', PasswordType::class,[
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add('isTest', CheckboxType::class,[
                    'required'  => false,
                    'attr' => ['class' => 'form-check-input'],
                ]
            )
            ->add('isActive', CheckboxType::class,[
                    'required'  => false,
                    'attr' => ['class' => 'form-check-input'],
                ]
            )
            ->add('validTill', DateType::class,[
                    'widget' => 'single_text',
                    'empty_data'  => '',
                    'required'  => false,
                    'attr' => ['class' => 'form-control datepicker'],
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
            'data_class' => Credential::class,
        ]);
    }
}
