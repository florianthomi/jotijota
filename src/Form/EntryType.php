<?php

namespace App\Form;

use App\Entity\Entry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class EntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jid', TextType::class, [
                'attr' => [
                    'data-inputmask-regex' => "[1-7][A-Za-z]{2}[0-9]{2}[A-Za-z0-9]"
                ],
                'constraints' => new Regex('#^[1-7][A-Za-z]{2}[0-9]{2}[A-Za-z0-9]$#')
            ])
            ->add('pseudo')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entry::class,
        ]);
    }
}
