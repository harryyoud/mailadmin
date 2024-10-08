<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {
    private array $roles;

    public function __construct(private string $username, array $roles) {
        $this->roles = array_unique([
            'ROLE_USER',
            'ROLE_OAUTH_USER',
            'THING',
            ...$roles,
        ]);
    }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array {
        return $this->roles;
    }

    public function getPassword(): ?string {
        return null;
    }

    public function getSalt(): ?string {
        return null;
    }

    public function getUsername(): string {
        return $this->getUserIdentifier();
    }

    public function eraseCredentials(): void {
    }

    public function equals(UserInterface $user): bool {
        return $user->getUserIdentifier() === $this->username;
    }
}