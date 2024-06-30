<?php

namespace App\Twig\Components;

use App\Entity\Edition;
use App\Form\EditionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent(template: 'edition/_form.html.twig')]
class EditionForm extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'initialFormData')]
    public ?Edition $edition;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            EditionType::class,
            $this->edition
        );
    }
}
