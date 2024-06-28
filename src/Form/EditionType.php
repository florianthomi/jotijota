<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ])
            ->add('subscriptionTo', null, [
                'widget' => 'single_text',
            ])
            ->add('name')
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'id',
                'multiple' => true,
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
