<?php

namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class TextInput
{
    public FormView $form;
}
