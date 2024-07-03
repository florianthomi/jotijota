<?php

namespace App\Twig\Components;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
class SelectInput
{
    public ?FormView $form = null;
    public ?string $name = null;
    public string|array $currentValues = [];
    public bool $required = false;
    public bool $multiple = false;
    public ?string $label = null;
    public iterable $choices = [];
    public array $errors = [];
    public array $extraAttributes = [];

    #[PreMount]
    public function preMount(array $data): array
    {
        if (!array_key_exists('currentValues', $data)) {
            $data['currentValues'] = [];
        }elseif (is_string($data['currentValues'])) {
            $data['currentValues'] = [$data['currentValues']];
        }

        return $data;
    }
}
