<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('coords')
            ->add('country')
            ->add('comment')
            ->add('rules')
            ->add('languages')
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('editions', EntityType::class, [
                'class' => Edition::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
