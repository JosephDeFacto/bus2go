<?php

namespace App\Form;

use App\Entity\CartTicket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /*->add('quantity', NumberType::class, ['attr' => ['class' => 'quantity']])*/
            ->add('childQuantity', IntegerType::class,
                ['attr' => ['class' => 'quantity', 'min' => 0],
                'label' => 'Children',
                'required' => false,
                'empty_data' => ''])
            ->add('studentQuantity', IntegerType::class,
                ['attr' => ['class' => 'quantity', 'min' => 0],
                'label' => 'Student',
                'required' => false])
            ->add('adultQuantity', IntegerType::class, [
                'attr' => ['class' => 'quantity', 'min' => 0],
                'label' => 'Adult',
                'required' => false])
            ->add('pensionerQuantity', IntegerType::class, [
                'attr' => ['class' => 'quantity', 'min' => 0],
                'label' => 'Pensioner',
                'required' => false])

            ->add('submit', SubmitType::class, ['attr' =>  ['class' => 'btn-cart-submit'], 'label' => 'Proceed'])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartTicket::class,
        ]);
    }
}
