<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'label.username'
            ])
            ->add('firstname', null, [
                'label' => 'label.firstname'
            ])
            ->add('lastname', null, [
                'label' => 'label.lastname'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'label.password', 'attr' => ['autocomplete' => 'new-password']],
                'second_options' => ['label' => 'label.repeat_password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'message.enter_password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'message.password_min_length',
                        'max' => 4096,
                    ]),
                ],
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
