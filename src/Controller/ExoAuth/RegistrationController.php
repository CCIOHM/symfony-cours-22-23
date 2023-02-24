<?php

namespace App\Controller\ExoAuth;

use App\Controller\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends Controller
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function create()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_home');
        }

        return $this->render('exo_auth/registration/register.html.twig');
    }

    public function store(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppCustomAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('user_home');
        }

        // Form validation
        $errors = $this->validate($request, RegistrationFormType::class);
        if ($errors) {
            (new Session())->getFlashBag()->set('errors', $errors);
            return $this->redirectToRoute('user_register');
        }

        $user = new User();
        $user->setName($request->get('name'));
        $user->setEmail($request->get('email'));
        $user->setPassword(
            $userPasswordHasher->hashPassword($user, $request->get('password'))
        );
        $user->setCreatedAt(new \DateTimeImmutable());
        $entityManager->persist($user);
        $entityManager->flush();

        // TODO - Enable if the mailer is configured
        // Send mail for the email verification
        $user->sendVerifEmail($this->emailVerifier);

        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function verifyUserEmail(Request $request): Response
    {
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('user_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('user_login');
    }
}
