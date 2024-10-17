<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'label.username',
                'help' => 'label.username.help',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => empty($builder->getData()->getPassword()),
                'first_options'  => ['label' => 'label.password', 'attr' => ['autocomplete' => 'new-password']],
                'second_options' => ['label' => 'label.repeat_password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstname', null, [
                'label' => 'label.firstname'
            ])
            ->add('lastname', null, [
                'label' => 'label.lastname'
            ])
            ->add('section', null, [
                'label' => 'label.section'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
