<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Toggler
{
    public string $label = '';
    public ?string $name = null;
    public string $value = '1';
    public bool $checked = false;
    public array $actions = [];
}
