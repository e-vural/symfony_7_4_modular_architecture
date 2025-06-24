<?php

namespace SymfonyFullAuthBundle\Response\ReactNative;

use AllowDynamicProperties;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

#[AllowDynamicProperties] class ReactNativeMessageResponse
{

    const TWIG_FILE = "@SymfonyFullAuth/react_native_web_view_message_bridge.html.twig";
    const LOGIN = "login";
    const REGISTER = "register";
    const LOGOUT = "logout";
    const RESET_PASSWORD = "reset_password";
    const CHANGE_PASSWORD = "change_password";

    public function __construct(private readonly  Environment $twig)
    {
    }


    private static function responseParameters($type, $data, $status)
    {
        return [
            "postMessage" => json_encode([
                "type" => $type,
                "data" => $data,
                "status" => $status
            ])
        ];
    }


    public  function loginResponse($data, $status)
    {return $this->reponse(self::responseParameters(self::LOGIN, $data, $status));

    }

    public  function changePasswordResponse($data, $status)
    {
        return $this->reponse(self::responseParameters(self::CHANGE_PASSWORD, $data, $status));
    }

    public  function registerResponse($data, $status)
    {
        return $this->reponse(self::responseParameters(self::REGISTER, $data, $status));
    }

    public function errorResponse($type, $data)
    {
        return $this->reponse(self::responseParameters($type, $data, "error"));
    }

    private function reponse($parameters)
    {
        return new Response($this->twig->render(self::TWIG_FILE,$parameters));

    }
}
