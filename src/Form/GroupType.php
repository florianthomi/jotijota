<?php

namespace App\Form;

use App\Entity\Edition;
use App\Entity\Group;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\IntlCallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locale;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class GroupType extends AbstractType
{
    public function __construct(#[Autowire(param: 'locales')] private readonly array $locales)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('coords', CoordsType::class, [
                'constraints' => new Valid(),
                'by_reference' => false
            ])
            ->add('country', CountryType::class, [
                'autocomplete' => true
            ])
            ->add('comment')
            ->add('rules')
            ->add('visible')
            ->add('languages', ChoiceType::class, [
                'choice_loader' => new IntlCallbackChoiceLoader(function() {
                    $choices = [];

                    foreach ($this->locales as $locale) {
                        $choices[Languages::getName($locale, $locale)] = $locale;
                    }

                    return $choices;
                }),
                'autocomplete' => true,
                'multiple' => true
            ])
            ->add('questions', LiveCollectionType::class, [
                'entry_type' => QuestionType::class,
                'help' => 'Ces questions pourront être complétées par les participants'
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
