<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    Swift_Mailer $mailer, 
    EntityManagerInterface $entityManager,
    TokenGeneratorInterface  $tokenGenerator)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
             // Capture the selected roles
   // Capture the selected roles
$selectedRoles = $form->get('roles')->getData();
$userRoles = [];
foreach ($selectedRoles as $role) {
    $userRoles[] = $role;
}
$user->setRoles($userRoles);$token = $tokenGenerator->generateToken();

$url = $this->generateUrl('app_verify_email', ['email'=>$form->get('email')->getData(),'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);



            $entityManager->persist($user);
            $entityManager->flush();

            // Render the email template
            $emailBody = $this->renderView(
                'registration/activationEmail.html.twig',
                ['url' => $url]
            );
        
            // Create the Swift_Message instance
            $message = (new Swift_Message('َActivation Email'))
                ->setFrom('formationSymfony4@gmail.com') // Set your sender email address
                ->setTo($user->getEmail()) // Set the recipient email address
                ->setBody(
                    $emailBody,
                    'text/html' // Specify that the body is HTML
                );
                
        
                $mailer->send($message);


            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator,
    EntityManagerInterface $entityManager,

    ): Response
    {

   // Get the repository for the User entity
   $userRepository = $entityManager->getRepository(User::class);

   // Find the user by email
   $user = $userRepository->findOneBy(['email' => $request->get('email')]);

        try {
         //   $this->emailVerifier->handleEmailConfirmation($request, $user);
            $user->setIsVerified(true);
            $entityManager->flush();



          
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('email_verif');
    }


    
    #[Route('/emailVerif', name: 'email_verif')]
    public function index_front(): Response
    {
        return $this->render('registration/emailVerification.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
