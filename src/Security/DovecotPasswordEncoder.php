<?php


namespace App\Security;


use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class DovecotPasswordEncoder implements PasswordEncoderInterface {
    private const PASSWORD_PREFIX = "{SHA512-CRYPT}";
    private const MAX_SALT_LEN = 16;

    /**
     * @inheritDoc
     */
    public function encodePassword(string $raw, ?string $salt, bool $addPrefix = false): string {
        if ($this->isPasswordTooLong($raw)) {
            throw new BadCredentialsException('Invalid password.');
        }
        if (is_null($salt)) {
            throw new BadCredentialsException('Cannot hash password with null salt.');
        }
        return $this->addPrefix(crypt($raw, '$6$' . substr($salt, 0, 16)));
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

    private function extractSalt(string $hash): ?string {
        $matches = [];
        if (preg_match('/(\{.*-CRYPT\})\$([0-9]*)\$(.*)\$(.*)/', $hash, $matches)) {
            return $matches[3];
        };
        return null;
    }

    /**
     * @inheritDoc
     */
    public function isPasswordValid(string $encoded, string $raw, ?string $salt): bool {
        if ($this->isPasswordTooLong($raw)) {
            return false;
        }
        // Ignore the passed salt, we want to compare using the existing salt
        $salt = $this->extractSalt($encoded);
        return hash_equals($this->stripPrefix($encoded), crypt($raw, '$6$'.$salt));
    }

    /**
     * @inheritDoc
     */
    public function needsRehash(string $encoded): bool {
        return false;
    }

    private function isPasswordTooLong(string $raw): bool {
        return strlen($raw) > 4096;
    }
}