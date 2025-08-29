<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

//    private function configureRoutes(RoutingConfigurator $routes): void
//    {
//        $configDir = preg_replace('{/config$}', '/{config}', $this->getConfigDir());
//
//
//        $routes->import($configDir.'/{routes}/'.$this->environment.'/*.{php,yaml}');
//        $routes->import($configDir.'/{routes}/*.{php,yaml}');
//
//        if (is_file($this->getConfigDir().'/routes.yaml')) {
//            $routes->import($configDir.'/routes.yaml');
//
//            $routes->import(__DIR__."/Domain/**/Config/routes.yaml")->prefix("/{_locale}/api")
//                ->defaults(["_locale" => "tr"])
//                ->requirements(['_locale' => 'tr|en']);
//        } else {
//            $routes->import($configDir.'/{routes}.php');
//        }
//
//        if ($fileName = (new \ReflectionObject($this))->getFileName()) {
//            $routes->import($fileName, 'attribute');
//        }
//    }
}
