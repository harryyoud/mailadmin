<?php


namespace App\Security;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class DovecotPasswordEncoder implements PasswordEncoderInterface {
    private const PASSWORD_PREFIX = "{ARGON2ID}";
    private const ALGO = 'argon2id';

    /**
     * @inheritDoc
     */
    public function encodePassword(string $raw, ?string $salt): string {
        return $this->addPrefix(password_hash($raw, self::ALGO));
    }

    private function stripPrefix(string $hash): string {
        if (substr($hash, 0, strlen(self::PASSWORD_PREFIX)) !== self::PASSWORD_PREFIX) {
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
    public function isPasswordValid(string $encoded, string $raw, ?string $salt): bool {
        return password_verify($raw, $this->stripPrefix($encoded));
    }

    /**
     * @inheritDoc
     */
    public function needsRehash(string $encoded): bool {
        return false;
    }
}