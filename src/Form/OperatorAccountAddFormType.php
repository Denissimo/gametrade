<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperatorAccountAddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game', EntityType::class, [
                'attr' => ['class' => 'form-select'],
                'class' => Game::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('externalId', null,[
                    'attr' => ['class' => 'form-control'],
                ]
            )
            ->add('price', null,[
//                'required' => false,
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
            'data_class' => Account::class,
        ]);
    }
}
