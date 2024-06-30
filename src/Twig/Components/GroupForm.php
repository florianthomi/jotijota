<?php

namespace App\Twig\Components;

use App\Entity\Edition;
use App\Entity\Group;
use App\Form\EditionType;
use App\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent(template: 'group/_form.html.twig')]
class GroupForm extends AbstractController
{
    use DefaultActionTrait;
    use LiveCollectionTrait;

    #[LiveProp(fieldName: 'initialFormData')]
    public ?Group $group;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            GroupType::class,
            $this->group
        );
    }
}
