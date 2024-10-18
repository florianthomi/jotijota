<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function __construct(private readonly Security $security, private readonly RoleHierarchyInterface $roleHierarchy)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roles = $this->roleHierarchy->getReachableRoleNames($this->security->getUser()?->getRoles() ?? []);
        $builder
            ->add('username', null, [
                'label' => 'label.username',
                'help' => 'label.username.help',
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'label.roles',
                'multiple' => true,
                'choices' => array_combine(array_map(static fn(string $role) => 'label.' . strtolower($role), $roles), $roles),
                'autocomplete' => true
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => empty($builder->getData()->getPassword()),
                'first_options' => ['label' => 'label.password', 'attr' => ['autocomplete' => 'new-password']],
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
            ]);

        $params = [
            'label' => 'label.group',
            'class' => Group::class,
            'choice_label' => 'name',
            'autocomplete' => true,
            'required' => false
        ];

        if (!$this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $params['choices'] = $this->security->getUser()?->getCoordinatedGroups() ?? new ArrayCollection();
        }
        $builder->add('group', EntityType::class, $params);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
