<?php

namespace SymfonyFullAuthBundle\Controller\View\Auth;

use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyFullAuthBundle\Entity\User\User;
use SymfonyFullAuthBundle\EventListener\UserEntityListener;
use SymfonyFullAuthBundle\Form\ResetPassword\ResetPasswordForm;
use SymfonyFullAuthBundle\Form\ResetPassword\ResetPasswordRequestForm;

#[
    Route('/reset-password'),
    OA\Post(tags: ["Reset Password"]),
    OA\Get(tags: ["Reset Password"])
]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Display & process form to request a password reset.
     */
    #[Route('', name: 'app_forgot_password_request', methods: ["GET", "POST"])]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ResetPasswordRequestForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $email = $form->get('email')->getData();
            return $this->processSendingPasswordResetEmail($email, $mailer, $translator);
        }

        return $this->render('@SymfonyFullAuth/auth/reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     */
    #[Route('/check-email', name: 'app_check_email', methods: ["GET"])]
    public function checkEmail(): Response
    {
        // Generate a fake token if the user does not exist or someone hit this page directly.
        // This prevents exposing whether or not a user was found with the given email address or not
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('@SymfonyFullAuth/auth/reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     */
    #[Route('/reset/{resetType}/{token}', name: 'app_reset_password', methods: ["GET", "POST"],defaults: ["resetType" => "web"])]
    public function reset(Request $request,  TranslatorInterface $translator, ?string $resetType = null, ?string $token = null): Response
    {
        if ($token) {
            // We store the token in session and remove it from the URL, to avoid the URL being
            // loaded in a browser and potentially leaking the token to 3rd party JavaScript.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();

        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ResetPasswordForm::class,null,["allow_exception" => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $request->isMethod('POST')) {
            // A password reset token should be used only once, remove it.

            /** @var string $plainPassword */
            $plainPassword = $form->get('newPassword')->getData();

            // Encode(hash) the plain password, and set it.
//            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            /** @see UserEntityListener::preUpdate() Hash will in that class */
            $user->setPassword($plainPassword);

            /** Real password set process, If account detail exist and is not real password */
            if ($user->getAccountDetail() and !$user->getAccountDetail()->isRealPassword()){
                $user->getAccountDetail()->setRealPassword(true);
            }
            /** */

            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();
            $this->resetPasswordHelper->removeResetRequest($token);

            return $this->redirectToRoute('view_auth_login_form');
        }

        return $this->render('@SymfonyFullAuth/auth/reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }

    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);


        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // If you want to tell the user why a reset email was not sent, uncomment
            // the lines below and change the redirect to 'app_forgot_password_request'.
            // Caution: This may reveal if a user is registered or not.
            //
             $this->addFlash('reset_password_error', sprintf(
                 '%s - %s',
                 $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
                 $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
             ));


            return $this->redirectToRoute('app_forgot_password_request');
        }

        /**
         * TODO Role göre mobile veya web yapıyoruz.
         *      Sadece Personel ise mobile yönlendirmek için resetType mobile oluyor
         *      Sadece Admin ise web e yönlendirmek için resetType web oluyor.
         *      Hem Admin hem Personel ise web oluyor.
         *
         **/
        $resetType = "mobile";
        $resetUrl = $this->generateUrl("app_reset_password",["token" => $resetToken->getToken(),"resetType" => $resetType], UrlGeneratorInterface::ABSOLUTE_URL);

        $email = (new TemplatedEmail())
            ->from(new Address('hasan.kacar@kodpit.com', 'HK Mail Bot'))
            ->to((string) $user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('@SymfonyFullAuth/auth/reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'resetUrl' => $resetUrl,
            ]);

        $mailer->send($email);

        // Store the token object in session for retrieval in check-email route.
        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('app_check_email');
    }
}
