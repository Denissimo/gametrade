<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAccountAddToBasketFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Account $account */
        $account = $options['data'];
        $builder
//            ->add('id', HiddenType::class, ['data' => $account->getId()])
            ->add('price', HiddenType::class, ['data' => $account->getPrice()])
            ->add(
                'add',
                SubmitType::class,
                [
                    'label' => 'Add',
                    'attr' => ['class' => 'btn btn-primary']
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
