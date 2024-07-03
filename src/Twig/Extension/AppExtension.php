<?php

namespace App\Twig\Extension;

use App\Repository\EditionRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private readonly EditionRepository $editionRepository)
    {

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_editions', $this->getEditions(...)),
        ];
    }

    public function getEditions()
    {
        return $this->editionRepository->findBy(['visible' => true]);
    }
}
