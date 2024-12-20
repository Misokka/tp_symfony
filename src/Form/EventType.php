<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Event;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'événement',
                'attr' => [
                    'class' => 'mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Nom de l\'événement'
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => [
                    'class' => 'mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500',
                    'placeholder' => 'Description'
                ]
            ])
            ->add('date', null, [
                'label' => 'Date',
                'widget' => 'single_text',
            ])
            ->add('location', EntityType::class, [
                'label' => 'Lieu',
                'class' => Location::class,
                'choice_label' => 'name',
            ])
            ->add('activities', EntityType::class, [
                'label' => 'Activités',
                'class' => Activity::class,
                'choice_label' => 'name',
                'multiple' => true,
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
