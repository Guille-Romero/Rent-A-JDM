<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Make;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('make',EntityType::class, [
                'label' => 'Make',
                'class' => Make::class,
                'choice_label' => 'name',
                'required' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank(),
                ]
            ])

            ->add('model')
            ->add('color')
            ->add('chassis')
            ->add('year')
            ->add('engine')
            ->add('horsepower')
            ->add('image')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
