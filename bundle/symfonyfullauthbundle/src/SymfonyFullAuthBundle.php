<?php

namespace SymfonyFullAuthBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use SymfonyFullAuthBundle\DependencyInjection\SymfonyFullAuthBundleExtension;

class SymfonyFullAuthBundle extends AbstractBundle
{

    public function getContainerExtension(): ?\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return new SymfonyFullAuthBundleExtension();
    }

//    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
//    {
//        $container->import('../config/services.yml');
//    }

    public function getPath(): string
    {
//        dd(__DIR__);
//        return __DIR__;
        return __DIR__;
    }


}
