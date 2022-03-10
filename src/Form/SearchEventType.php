<?php

namespace App\Form;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('campus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'choice_label' =>  'name',
                'choice_value' => 'name',
                'required' => false
            ])
            ->add('search', SearchType::class, [
                'attr' => array(
                    'placeholder' => 'Search an event by name'
                ),
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Entre ',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'et ',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('eventOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur.trice',
                'required' => false
            ])
            ->add('eventRegister', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit.e',
                'required' => false
            ])
            ->add('eventNotRegister', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit.e',
                'required' => false
            ])
            ->add('pastEvent', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
