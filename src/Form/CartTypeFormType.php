<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\CartTicket;
use App\Entity\PassengerType;
use App\Entity\TravelSchedule;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, ['attr' => ['class' => 'quantity']])
           /* ->add('passenger', EntityType::class, [
                'mapped' => false,
                'class' => PassengerType::class,
                'choice_label' => 'type',
            ])*/

            ->add('submit', SubmitType::class, ['attr' =>  ['class' => 'btn-cart-submit']])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CartTicket::class,
        ]);
    }
}
