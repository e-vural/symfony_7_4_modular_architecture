<?php

namespace App\Infrastructure\Traits\Symfony;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait EntityManagerProviderTrait
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->container->get(EntityManagerInterface::class);
    }
}
