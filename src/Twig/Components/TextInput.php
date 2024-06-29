<?php

namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class TextInput
{
    public ?FormView $form = null;
    public array $icon = [];
    public string $name;
    public string $type = 'text';
    public string $value = '';
    public ?string $label = null;
    public array $errors = [];
    public array $extraAttributes = [];

}
