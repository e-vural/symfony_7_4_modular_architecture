<?php

namespace App\Modules\User\Attribute;

use Symfony\Component\Routing\Attribute\Route;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class UserRoutePrefix extends Route
{

    public function __construct($path = null, ...$args)
    {
        parent::__construct($path ?: $this->getRoutePath(), ...$args);
    }

    private function getRoutePath(): string
    {
        return "/user";
    }

}
