<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('car',EntityType::class, [
                'label' => 'Car',
                'class' => Car::class,
                'choice_label' => function ($car) {
                    return $car->getMake()->getName() . ' ' . $car->getModel().' '.$car->getChassis();
                },
                'expanded' => true,
                'multiple' => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
