<?php

namespace App\Security\Authentication;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\Token\JWTPostAuthenticationToken;

class CustomAuthenticationToken extends JWTPostAuthenticationToken
{

//    public function getJwt(){
//        return $this->token;
//    }

}
