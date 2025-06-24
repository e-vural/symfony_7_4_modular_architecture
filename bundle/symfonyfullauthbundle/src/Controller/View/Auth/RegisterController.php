<?php

namespace SymfonyFullAuthBundle\Controller\View\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyFullAuthBundle\Form\Register\RegisterForm;

use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\Register\RegisterType;
use SymfonyFullAuthBundle\Response\ReactNative\ReactNativeMessageResponse;
use SymfonyFullAuthBundle\Service\Login\JsonLoginService;


class RegisterController extends AbstractController
{


    /**
     * Register Form Content
     */
    #[Route('/register/form', name: 'register_form', methods: ['GET'])]
    public function registerForm()
    {


        try {
            $form =  $this->getForm();

//            $form->add("submit", SubmitType::class);
            return $this->render('@SymfonyFullAuth/auth/register/register.html.twig', [
                'form' => $form
            ]);
//            return new JsonResponse(["form" => $registerForm], Response::HTTP_OK);
        } catch (Exception $e) {
            return new JsonResponse(["message" => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }
    }

    #[Route('/register/action', name: 'register_action', methods: ['POST'])]
    public function registerAction(Request $request,EntityManagerInterface $entityManager,ReactNativeMessageResponse $reactNativeMessageResponse,JsonLoginService $jsonLoginService)
    {

        $form =  $this->getForm();
        $form->handleRequest($request);
        $plainPassword = $form->get("user")->get("password")->getData();



        if($form->isValid()) {
            /** @var User $user */

//            $profile = $form->get("profile")->getData();
//
//
//            $entityManager->persist($user);
//            $entityManager->persist($profile);


            $entityManager->flush();
            $user = $form->get("user")->getData();
            $email = $form->get("user")->get("identifier")->getData();

//            dd($user);
            $result = $jsonLoginService->login($user->getEmail(),$plainPassword);
            $response["credentials"] = $result;

            $user = $entityManager->getRepository(User::class)->getUserAfterLogin($email);
            $response["user"] = $user;

            return $reactNativeMessageResponse->loginResponse($response,"success");
        }

//        dd($form->get("user")->get("identifier")->getErrors());
        return $this->render('@SymfonyFullAuth/auth/register/register.html.twig', [
            'form' => $form
        ]);

    }

    private function getForm()
    {
        return  $this->createForm(RegisterForm::class, null, ["csrf_protection" =>true,"allow_exception" => false,"action" => $this->generateUrl("register_action"), "method" => "POST"]);

    }
}
