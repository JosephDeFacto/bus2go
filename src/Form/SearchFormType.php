<?php

namespace App\Form;

use App\Entity\TravelSchedule;
use App\Type\PassengerTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departFrom', TextType::class, [
                'attr' => ['placeholder' => 'From', 'class' => 'search__select--from', 'autocomplete' => 'off'],
            ])
            ->add('travelTo', TextType::class, [
                'attr' => ['placeholder' => 'To', 'class' => 'search__select--to', 'autocomplete' => 'off'],
            ])
            ->add('departingOn', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker', 'placeholder' => 'Select date for depart', 'autocomplete' => 'off'],
                'required' => false,
                'html5' => false,
            ])
           /* ->add('returningOn', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'attr' => ['class' => 'js-datepicker', 'placeholder' => 'optional'],
                'required' => false,
                'html5' => false,
            ])*/
            /*->add('quantity', NumberType::class)

            ->add('passenger', ChoiceType::class, [
                'choices' => PassengerTypeEnum::getAvailableTypes(),
                'choice_label' => function ($choice) {
                return PassengerTypeEnum::getTypeName($choice);
                },
            ])*/

            /*->add('departureTime')*/
            /*->add('timeOfArrival')
            ->add('estimatedArrivalTime')*/
            /*->add('fee')
            ->add('bus')
            ->add('busDriver')
            ->add('city')*/
            ->getForm()
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TravelSchedule::class,
            'attr' => ['class' => 'bs--search-form']
        ]);
    }
}
