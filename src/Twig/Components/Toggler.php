<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Toggler
{
    public string $label = '';
    public ?string $name = null;
    public array $actions = [];
}
