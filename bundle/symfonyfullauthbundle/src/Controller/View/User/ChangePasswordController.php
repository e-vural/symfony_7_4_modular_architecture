<?php

declare(strict_types=1);

namespace SymfonyFullAuthBundle\Controller\View\User;

use AllowDynamicProperties;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\Form\ChangePassword\ChangePasswordForm;
use SymfonyFullAuthBundle\Form\Login\LoginForm;
use SymfonyFullAuthBundle\Response\ReactNative\ReactNativeMessageResponse;
use SymfonyFullAuthBundle\Security\FullAuthBundleToken;
use SymfonyFullAuthBundle\Service\JWT\JWTManuelChecker;

#[AllowDynamicProperties] #[OA\Tag("WebView")]
class ChangePasswordController extends AbstractController
{

    private $parsedJWT;

    /**
     * @param JWTManuelChecker $JWTManuelChecker
     * @param ReactNativeMessageResponse $reactNativeMessageResponse
     *
     * That controller  outside firewall. We check  JWT manual
     *
     */
    public function __construct(JWTManuelChecker $JWTManuelChecker, private readonly ReactNativeMessageResponse $reactNativeMessageResponse)
    {
        $this->checkJWT($JWTManuelChecker);

    }

    /**
     * Change Password Form
     */
    #[Route('/change-password', name: "view_user_change_password_form", methods: ["POST"])]
    #[OA\RequestBody(
        description: "Member registration payload",
        required: true,
        content: new Model(type: ChangePasswordForm::class)
    )]
    #[
        OA\Response(
            response: 400,
            description: "Change password Problem"
        ),


    ]
    public function changePasswordForm(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {

        $form = $this->getForm();
        $jwt = $this->parsedJWT["jwt"];
        $change_password_token = $csrfTokenManager->getToken("change_password_token")->getValue();
        $request->getSession()->set("change_password_token", $change_password_token);

        return $this->render('@SymfonyFullAuth/user/user_change_password.html.twig', [
            "form" => $form,
            "jwt" => $jwt
        ]);
    }

    #[Route('/change-password-check', name: "view_user_change_password_action", methods: ["POST"])]
    public function changePasswordAction(Request $request, EntityManagerInterface $entityManager, Security $security,): Response
    {

        if (!$this->isCsrfTokenValid("change_password_token", $request->getSession()->get("change_password_token"))) {
            return $this->reactNativeMessageResponse->errorResponse("invalid_request_token", []);
        }

        $user = $entityManager->getRepository(User::class)->findOneBy(array("email" => $this->parsedJWT["parsedJWT"]["username"]));
        //Need manual login for symfony user mechanism
        $security->login($user, "security.authenticator.jwt.api", "api", [], ["token" => $this->parsedJWT["jwt"]]);

        $form = $this->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPassword = $form->get("newPassword")->getData();
            $this->getUser()->setPassword($newPassword);

            $entityManager->flush();
            $security->logout(false);

            return $this->reactNativeMessageResponse->changePasswordResponse([], "success");
        }


        return $this->render('@SymfonyFullAuth/user/user_change_password.html.twig', [
            "form" => $form,
            "jwt" => $this->parsedJWT["jwt"]
        ]);
    }

    private function checkJWT(JWTManuelChecker $JWTManuelChecker)
    {

        try {
            $this->parsedJWT = $JWTManuelChecker->checkFromPostParameter();

        } catch (JWTDecodeFailureException $exception) {

            $response = $this->reactNativeMessageResponse->errorResponse($exception->getReason(), []);
            $response->send();
            exit;

        }


    }

    private function getForm()
    {

        $form = $this->createForm(ChangePasswordForm::class, null, ["action" => $this->generateUrl("view_user_change_password_action"), "allow_exception" => false, "csrf_protection" => false]);
        return $form;
    }
}
