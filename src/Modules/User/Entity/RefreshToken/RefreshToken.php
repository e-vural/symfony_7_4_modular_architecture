<?php

namespace App\Modules\User\Entity\RefreshToken;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;

#[ORM\Entity]
#[ORM\Table(name: 'a_refresh_tokens')]
class RefreshToken extends BaseRefreshToken
{

}
