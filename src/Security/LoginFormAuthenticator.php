<?php

namespace App\Security;

use App\Entity\Mailbox;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator {
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'login';

    private EntityManagerInterface $entityManager;
    private UrlGeneratorInterface $urlGenerator;
    private CsrfTokenManagerInterface $csrfTokenManager;
    private PasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        DovecotPasswordEncoder $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @Override
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(Mailbox::class)->loadUserByIdentifier($credentials['username']);

        if (is_null($user)) {
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool {
        if ($user instanceof Mailbox) {
            return $this->passwordHasher->verify(
                $user->getPassword(),
                $credentials['password'],
            );
        }
        throw new CustomUserMessageAuthenticationException("Unexpected User class passed to checkCredentials");
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('admin'));
    }

    protected function getLoginUrl(Request $request): string {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /**
     * @inheritDoc
     */
    public function authenticate(Request $request): Passport {
        $username = $request->request->get('_username');
        $password = $request->request->get('_password');
        $csrfToken = $request->request->get('_csrf_token');

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $username,
        );

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password),
            [new CsrfTokenBadge('login', $csrfToken)]
        );

    }
}
