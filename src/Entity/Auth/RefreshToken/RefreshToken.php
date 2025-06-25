<?php

namespace App\Entity\Auth\RefreshToken;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;

#[ORM\Entity]
#[ORM\Table(name: 'a_refresh_tokens')]
class RefreshToken extends BaseRefreshToken
{

}
