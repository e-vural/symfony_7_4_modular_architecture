<?php

namespace SymfonyFullAuthBundle\Service\Login;

use AllowDynamicProperties;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use SymfonyFullAuthBundle\Controller\View\Auth\LoginFailedException;
use SymfonyFullAuthBundle\Entity\User\User;

#[AllowDynamicProperties]
class JsonLoginService
{
    // Define whether a password is required during login (constant for configuration purposes)
    const PASSWORD_REQUIRED = false;

    private EntityManagerInterface $entityManager;

    // Constructor to inject required dependencies for the service
    /**
     * JsonLoginService constructor.
     *
     * This constructor initializes the JsonLoginService with the necessary dependencies
     * to perform user authentication and session handling.
     *
     * @param RouterInterface $router                   The router used to generate URLs.
     * @param HttpClientInterface $httpClient           The HTTP client for making requests.
     * @param AuthenticationSuccessHandler $authenticationSuccessHandler The handler for successful authentication.
     * @param EntityManagerInterface $entityManager     The entity manager for database operations.
     * @param UserPasswordHasherInterface $passwordHasher The hasher for password verification.
     */
    public function __construct(
        RouterInterface $router,
        HttpClientInterface $httpClient,
        AuthenticationSuccessHandler $authenticationSuccessHandler,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->router = $router;
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    // Method to handle user login
    /**
     * Handles user login and authentication.
     *
     * This function attempts to authenticate a user by fetching their data from the database
     * using the provided identifier (email) and validating the provided password.
     * If the authentication is successful, a response is generated (e.g., JWT token).
     *
     * @param string $identifier The user's email address used as the login identifier.
     * @param string $password The user's password to be validated.
     *
     * @return array The decoded JSON response containing authentication data.
     *
     * @throws LoginFailedException if the authentication fails, providing an error message.
     */
    public function login($identifier, $password)
    {
        // Fetch the user entity from the database using the provided identifier (email)
        $user = $this->entityManager->getRepository(User::class)->findOneBy(array("email" => $identifier));

        // Check if user exists and the provided password is valid
        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            $message = "";
            // Throw a custom exception if login fails
            throw new LoginFailedException("Login failed." . $message);
        }

        // Handle successful authentication and generate a response (e.g., JWT token)
        $response = $this->authenticationSuccessHandler->handleAuthenticationSuccess($user);

        try {
            // Attempt to parse the response as JSON and return it
            return json_decode($response->getContent(), true);
        } catch (\Exception $exception) {
            // Handle any exceptions that occur during the response parsing
            $message = "";
            if ($_SERVER['APP_ENV'] == "dev") {
                // In development mode, include the exception message for debugging purposes
                $message = $exception->getMessage();
            }
            // Throw a custom exception with the error details
            throw new LoginFailedException("Login failed." . $message);
        }
    }
}
