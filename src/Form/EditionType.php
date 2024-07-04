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
                'label' => 'label.start_at'
            ])
            ->add('endAt', null, [
                'widget' => 'single_text',
                'label' => 'label.end_at'
            ])
            ->add('subscriptionFrom', null, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'label.subscription_from'
            ])
            ->add('subscriptionTo', null, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'label.subscription_to'
            ])
            ->add('name', null, [
                'label' => 'label.name'
            ])
            ->add('visible', null, [
                'label' => 'label.visible'
            ])
            ->add('coordinators', EntityType::class, [
                'label' => 'label.coordinators',
                'class' => User::class,
                'multiple' => true,
                'required' => false,
                'autocomplete' => true,
            ])
            ->add('questions', LiveCollectionType::class, [
                'label' => 'label.questions',
                'entry_type' => QuestionType::class,
                'help' => 'help.questions'
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
