<?php

namespace SymfonyFullAuthBundle\Controller\View\Auth;

use App\Serializer\Jms\CustomJmsSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\Login\LoginForm;
use SymfonyFullAuthBundle\Response\ReactNative\ReactNativeMessageResponse;
use SymfonyFullAuthBundle\Service\Login\JsonLoginService;


#[OA\Tag("WebView")]
class LoginController extends AbstractController
{


    /**
     * Login Form Content
     */
    #[Route('/login', name: 'view_auth_login_form', methods: ['GET'])]
    #[OA\RequestBody(
        description: "Member registration payload",
        required: true,
        content: new Model(type: LoginForm::class)
    )]
    #[OA\Response(
        response: 200,
        description: "Login Form UI Content"
    )]
    #[OA\Response(
        response: 400,
        description: "Error"
    )]
    public function loginForm(Request $request): Response
    {
//        dd(2);


//        dd( $request->query->get("redirect_url"));
//        try {
        $form = $this->getForm();
//        $user = $entityManager->getRepository(Member::class)->findOneBy(array("email"=>"emre16@vural.com"));
//        dd($user);


        return $this->render('@SymfonyFullAuth/auth/login/login.html.twig', [
            "form" => $form,
            "redirect_url" => $request->query->get("redirect_url")
        ]);
//        } catch (Exception $e) {
//            return new JsonResponse(["error" => $e->getMessage()], Response::HTTP_FORBIDDEN);
//        }
    }

    #[Route('/form-login-check', name: 'view_form_login_check', methods: ['POST'])]
    public function loginCheck()
    {
//dd(1);
    }

    #[Route('/login-check', name: 'view_login_check', methods: ['POST'])]
    public function loginAction(Request $request, JsonLoginService $jsonLogin, CustomJmsSerializer $customJmsSerializer, EntityManagerInterface $entityManager, ReactNativeMessageResponse $reactNativeMessageResponse)
    {

//        $login = $request->request->all()["login"];
//        $email = $login["email"];
//        $passoword = $login["password"];
////        $email = $request->request->get("_username");
////        $passowrd = $request->request->get("_password");
////        $result = $jsonLogin->login($email, $passowrd);
//        $user = $entityManager->getRepository(Member::class)->findOneBy(["email" => $email]);
//        $security->login($user);
//        $array = [
//            "event" => "login",
//            "status" => 200,
//            "message" => "",
//            "data" => $result
//        ];
//        return $this->render("@SymfonyFullAuth/auth/login/login_redirect_react_native.html.twig", ["postMessage" => json_encode($array)]);
//        return $this->redirectToRoute("view_user_change_password_form");
//        dd($this->getUser(),$result);
//        dd($request->query->get("redirect_url"));
        $form = $this->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $redirectUrl = $request->request->get("redirect_url");

            try {
                $email = $form->get("email")->getData();
                $password = $form->get("password")->getData();

                $result = $jsonLogin->login($email, $password);
//                dd($result);

//                dd($form->get("email")->getData(),$form->get("password")->getData());
                $response["credentials"] = $result;
                $user = $entityManager->getRepository(User::class)->getUserAfterLogin($email);
                $response["user"] = $user;
//                dd($user);

//            $request->request->set("_username",$login["email"]);
//            $request->request->set("_password",$login["password"]);

//                $security->login($user);

                $array = [
                    "event" => "login",
                    "status" => 200,
                    "message" => "",
                    "data" => $response
                ];

            } catch (LoginFailedException $exception) {
//
////                $array = [
////                    "event" => "login",
////                    "status" => 401,
////                    "message" => "hata var!!",
////                    "data" => []
////                ];
//
////                $a = new FormError("{$exception->getMessage()}");
////                $form->addError($a);
////                dd($form->getErrors(1));
//
                $this->addFlash("danger", "Login failed");
//

//                function encodeURIComponent($str) {
//                    return str_replace(
//                        array('%20', '%21', '%22', '%23', '%24', '%25', '%26', '%27', '%28', '%29', '%2A', '%2B', '%2C', '%2F', '%3A', '%3B', '%3D', '%3F', '%40', '%5B', '%5D'),
//                        array(' ', '!', '"', '#', '$', '%', '&', "'", '(', ')', '*', '+', ',', '/', ':', ';', '=', '?', '@', '[', ']'),
//                        rawurlencode($str)
//                    );
//                }

//                dd($redirectUrl,urlencode($redirectUrl));

                if($redirectUrl){

                    return $this->render('@SymfonyFullAuth/auth/login/login.html.twig', [
                        "form" => $form,
                        "redirect_url" => $request->query->get("redirect_url")

                    ]);
                    return $this->redirect($this->generateUrl("view_auth_login_form")."?redirect_url=".urlencode($redirectUrl));
                }

                return $this->redirectToRoute("view_auth_login_form");

//
            }catch (InvalidCsrfTokenException $exception){
                $this->addFlash("danger", "Please try again to login");
                return $this->redirectToRoute("view_auth_login_form");

            }




            if($redirectUrl){


                return $this->render("@SymfonyFullAuth/angular_web_view_message_bridge.html.twig",["response" => json_encode($response)]);
            }

            return $reactNativeMessageResponse->loginResponse($response, "success");

        }

        return $this->render('@SymfonyFullAuth/auth/login/login.html.twig', [
            "form" => $form,
            "redirect_url" => $request->query->get("redirect_url")

        ]);

    }

    private function getForm()
    {
        return $this->createForm(LoginForm::class, null, ["csrf_protection" => true,"allow_exception" => false, "action" => $this->generateUrl("view_login_check")]);

    }

}
