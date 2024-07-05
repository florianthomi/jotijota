<?php

namespace App\Form;

use App\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jid', null, [
                'label' => 'label.jid',
            ])
            ->add('pseudo', null, [
                'label' => 'label.pseudo'
            ])
            ->add('age', null, [
                'label' => 'label.age'
            ])
            ->add('comment', null, [
                'label' => 'label.comment'
            ])
            ->add('answers', CollectionType::class, [
                'label' => 'label.answers',
                'entry_type' => AnswerType::class,
                'constraints' => new Valid()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
