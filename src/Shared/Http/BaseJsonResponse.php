<?php

namespace App\Shared\Http;

//use App\Entity\MobileDevice\MobileDevice;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseJsonResponse
{

//    private ?MobileDevice $mobileDevice = null;
    private null $mobileDevice = null;

    public function jsonResponse(mixed $data, $message = null, $extraData = [], int $status = 200): JsonResponse
    {

        $isJson = false;
        $finalData = [];
        if (is_string($data)) {

            $finalData = $this->fromJsonString($data, $message, $extraData);
            $isJson = true;
        }else if (is_array($data)) {
            $finalData = $this->fromArray($data, $message, $extraData);
        }

        return new JsonResponse($finalData, $status, [], $isJson);
    }

    private function fromJsonString(string $data, $message, $extraData = [])
    {
        $finalData = '{"message": "' . $message . '", "data" : ' . $data;

        if (!empty($extraData)) {
            $extraData = json_encode($extraData);
            $extraData = substr($extraData, 1, -1);
            $finalData .= ',' . $extraData;

        }

        $commonInfo = json_encode($this->getCommonInfo());
        $finalData .= ' ,"commonInfo" : ' . $commonInfo;
        $finalData .= '}';

        return $finalData;
    }

    private function fromArray(array $data, $message, $extraData = [])
    {

        $data["message"] = $message ?? "";

        if (!empty($extraData)) {
            $data = array_merge($data, $extraData);
        }
        $data["commonInfo"]  = $this->getCommonInfo();

        return $data;
    }


    private function getCommonInfo()
    {
        $mobileDevice = $this->getMobileDevice();

        if (!$mobileDevice) {
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
