<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\Question;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class EditionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startAt', null, [
                'widget' => 'single_text',
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
            ])
            ->add('subscriptionFrom', null, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('subscriptionTo', null, [
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('name')
            ->add('visible')
            ->add('coordinators', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'required' => false,
                'autocomplete' => true,
            ])
            ->add('questions', LiveCollectionType::class, [
                'entry_type' => QuestionType::class,
                'help' => 'Ces questions pourront être complétées par les participants'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Edition::class,
        ]);
    }
}
