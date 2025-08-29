<?php

namespace App\Sandbox\Test\Attiribute;

use Symfony\Component\Routing\Attribute\DeprecatedAlias;
use Symfony\Component\Routing\Attribute\Route;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class TestRoutePrefix extends Route
{

    public function __construct(array|string|null $path = null, ?string $name = null, array $requirements = [], array $options = [], array $defaults = [], ?string $host = null, array|string $methods = [], array|string $schemes = [], ?string $condition = null, ?int $priority = null, ?string $locale = null, ?string $format = null, ?bool $utf8 = null, ?bool $stateless = null, ?string $env = null, array|DeprecatedAlias|string $alias = [])
    {

        if(!$path){
            $path = $this->getRoutePath();
        }
        parent::__construct($path, $name, $requirements, $options, $defaults, $host, $methods, $schemes, $condition, $priority, $locale, $format, $utf8, $stateless, $env, $alias);
    }

    private function getRoutePath(): string
    {
        return "/test";
    }

}
