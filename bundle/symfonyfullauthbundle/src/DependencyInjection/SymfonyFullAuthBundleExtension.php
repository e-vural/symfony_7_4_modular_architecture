<?php

namespace SymfonyFullAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Routing\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class SymfonyFullAuthBundleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

//        dd($container);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
//        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../config'));
        $loader->load('services.yml');
//        $loader->load('parameters.yaml'); // TODO Buraya gerek yok gibi. Bundle içinden gideceğiz artık...

//        $routingLoader = new YamlFileLoader(new FileLocator(__DIR__.'/../../config'));
//        $routingLoader->load('routing.yaml');


    }
}
