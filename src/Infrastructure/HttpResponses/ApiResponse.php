<?php

namespace App\Infrastructure\HttpResponses;

//use App\Entity\MobileDevice\MobileDevice;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse
{

//    private ?MobileDevice $mobileDevice = null;
    private null $mobileDevice = null;

    //TODO message içinde { gibi jsonu bozacak şeyler varsa sistemi bozar.
    public function jsonResponse(?string $json = "{}", $message = null, $extraData = [], int $status = 200): JsonResponse
    {

        if(!$json){
            $json = "{}";
        }

        $a = '{"message": "'.$message.'", "data" : '.$json;

        if(!empty($extraData)){
            $extraData = json_encode($extraData);
            $extraData = substr($extraData, 1, -1);
            $a.= ','. $extraData;
        }

        $commonInfo = json_encode($this->getCommonInfo());
        $a .= ' ,"commonInfo" : '.$commonInfo;
        $a.= '}';


        return new JsonResponse($a, $status,[],true);
    }


    private function getCommonInfo()
    {
        $mobileDevice = $this->getMobileDevice();

        if(!$mobileDevice){
            return [];
        }


        $pushToken = (bool)$mobileDevice->getPushToken();
        $isLogin = ($mobileDevice->isLogin() && $mobileDevice->getUser());
        $resetDevice = (bool)$mobileDevice->isResetDevice();

        return [
            "pushToken" => $pushToken,
            "isLogin" => $isLogin,
            "resetDevice" => $resetDevice
        ];

    }

    public function getMobileDevice()
    {
        return $this->mobileDevice;
    }
//
//
//    public function setMobileDevice(?MobileDevice $mobileDevice)
//    {
//        $this->mobileDevice = $mobileDevice;
//    }

}
