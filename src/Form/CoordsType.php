<?php

namespace App\Form;

use App\BusinessLogic\Model\Coords;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoordsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latitude', NumberType::class, [
                'label' => 'label.latitude',
                'scale' => 6
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'label.longitude',
                'scale' => 6
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Coords::class,
        ]);
    }
}
