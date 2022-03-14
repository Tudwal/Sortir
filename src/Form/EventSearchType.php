<?php

namespace App\Form;

use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' => 'name',
                'required' => false
            ])
            ->add('search', SearchType::class, [
                'mapped' => false,
                'label' => 'Recherche',
                'attr' => array(
                    'placeholder' => ' Rechercher une sortie'
                ),
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Date de début ',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Date de fin d\'inscription ',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('eventOrganizer', CheckboxType::class, [
                'label' => 'Sortie dont je suis l\'organisateur.trice',
                'row_attr' => ['checked' => true],
                'required' => false
            ])
            ->add('eventRegister', CheckboxType::class, [
                'label' => 'Sortie auxquelles je suis inscrit.e',
                'required' => false
            ])
            ->add('eventNotRegister', CheckboxType::class, [
                'label' => 'Sortie auxquelles je ne suis pas inscrit.e',
                'required' => false
            ])
            ->add('pastEvent', CheckboxType::class, [
                'label' => 'Sortie passées',
                'required' => false
            ]);
        // ->add('submit', SubmitType::class, [
        //     'label' => 'Rechercher',
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
