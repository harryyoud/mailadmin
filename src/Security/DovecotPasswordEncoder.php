<?php


namespace App\Security;


use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class DovecotPasswordEncoder implements PasswordHasherInterface {
    use CheckPasswordLengthTrait;

    private const PASSWORD_PREFIX = "{ARGON2ID}";
    private const ALGO = 'argon2id';

    /**
     * @inheritDoc
     */
    public function hash(string $plainPassword): string {
        if ($this->isPasswordTooLong($plainPassword)) {
            throw new InvalidPasswordException();
        }
        return $this->addPrefix(password_hash($plainPassword, self::ALGO));
    }

    private function stripPrefix(string $hash): string {
        if (!str_starts_with($hash, self::PASSWORD_PREFIX)) {
            throw new BadCredentialsException("Not a dovecot password hash");
        }
        return substr($hash, strlen(self::PASSWORD_PREFIX));
    }

    private function addPrefix(string $hash): string {
        return self::PASSWORD_PREFIX . $hash;
    }

    /**
     * @inheritDoc
     */
    public function verify(string $hashedPassword, string $plainPassword): bool {
        if ('' === $plainPassword || $this->isPasswordTooLong($plainPassword)) {
            return false;
        }
        return password_verify($plainPassword, $this->stripPrefix($hashedPassword));
    }

    /**
     * @inheritDoc
     */
    public function needsRehash(string $hashedPassword): bool {
        return false;
    }
}