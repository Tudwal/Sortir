<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la sortie: '])

            //->add('startDateTime')
            ->add('startDateTime', DateTimeType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                    'hour' => 'Hour', 'minute' => 'Minute',
                ],
                'label' => 'Date et heure de la sortie'
            ])

            //->add('endRegisterDate')
            ->add('endRegisterDate', TypeDateType::class, [
                'label' => 'Date limite d\'insciption ',
                'html5' => true,
                'widget' => 'single_text',
                'required' => false,
            ])

            ->add('duration', null, ['label' => 'Durée (en minutes): '])



            ->add('nbParticipantMax', null, ['label' => 'Nombre de places: '])


            ->add('details', TextareaType::class, ['label' => 'Description et infos: '])
            //->add('state')
            //->add('location')
            ->add('campus')
            //->add('organizer')
            //->add('participants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
