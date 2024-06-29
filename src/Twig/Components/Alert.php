<?php

namespace App\Twig\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent]
class Alert
{
    public string $type;
    public string $title;
    public ?string $message = null;

    #[PreMount]
    public function preMount(array $data): array
    {
        // validate data
        $resolver = new OptionsResolver();
        $resolver->setIgnoreUndefined(true);

        $resolver->setDefaults(['type' => 'success']);
        $resolver->setAllowedValues('type', ['success', 'danger', 'warning']);
        $resolver->setRequired('title');

        return $resolver->resolve($data) + $data;
    }
}
