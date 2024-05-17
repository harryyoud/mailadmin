<?php

namespace App\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {
    public function loadUserByIdentifier(string $identifier): UserInterface {
        return new User($identifier, []);
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response): UserInterface {
        return new User(
            $response->getEmail(),
            array_map(
                fn ($role) => 'ROLE_' . strtoupper($role),
                $response->getData()['roles'] ?? []
            )
        );
    }

    public function refreshUser(UserInterface $user): UserInterface {
        if (!$this->supportsClass($user::class)) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', $user::class));
        }
        return new User(
            $user->getUserIdentifier(),
            $user->getRoles(),
        );
    }

    public function supportsClass($class): bool {
        return User::class === $class;
    }
}