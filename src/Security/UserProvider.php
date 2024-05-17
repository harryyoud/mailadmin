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
            $response->getUsername(),
            $response->getPath('roles') ?? []
        );
    }

    public function refreshUser(UserInterface $user): UserInterface {
        if (!$this->supportsClass($user::class)) {
            throw new UnsupportedUserException(sprintf('Unsupported user class "%s"', $user::class));
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass($class): bool {
        return User::class === $class;
    }
}