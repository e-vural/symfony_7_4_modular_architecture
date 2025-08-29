<?php

namespace App\Shared\Traits\Symfony;

use Symfony\Component\Form\FormFactoryInterface;

trait FormFactoryInterfaceProviderTrait
{
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->container->get(FormFactoryInterface::class);
    }
}
