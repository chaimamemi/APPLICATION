<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Entity\User;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
{
    // Récupérer l'utilisateur à partir du token
    /** @var User $user */
    $user = $token->getUser();

    // Récupérer le rôle de l'utilisateur
    $roles = $user->getRoles();

    // Vérifier si l'utilisateur a plusieurs rôles et récupérer le premier rôle (celui le plus significatif)
    $role = $roles[0] ?? null;

    // Définir les routes de redirection en fonction du rôle sélectionné
    $route = match ($role) {
        'ROLE_OWNER' => 'app_owner_dashboard',
        'ROLE_FAMILY_MEMBER' => 'app_family_member_dashboard',
        'ROLE_DOCTOR' => 'app_health_professional_doctor_dashboard', // Rediriger directement vers le tableau de bord du médecin
        'ROLE_EMERGENCY_TEAM' => 'app_health_professional_emergency_dashboard',
        default => 'default_dashboard_route',
    };

    // Générer l'URL de redirection
    $redirectUrl = $this->urlGenerator->generate($route);

    // Créer une réponse de redirection vers l'URL appropriée
    return new RedirectResponse($redirectUrl);
}

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
