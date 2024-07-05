<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'label.username'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'label.roles',
                'multiple' => true,
                'choices' => [
                    'label.role_user' => 'ROLE_USER',
                    'label.role_admin' => 'ROLE_ADMIN',
                    'label.role_super_admin' => 'ROLE_SUPER_ADMIN'
                ],
                'autocomplete' => true
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
            ->add('group', EntityType::class, [
                'label' => 'label.group',
                'class' => Group::class,
                'choice_label' => 'name',
                'autocomplete' => true,
                'required' => false
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
