<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="accounts", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="username", columns={"username", "domain"})
 * })
 */
class Mailbox {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $username;

    /**
     * @ManyToOne(targetEntity="Domain")
     * @JoinColumn(name="domain", referencedColumnName="domain", nullable=false)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="integer")
     */
    private $quota;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sendonly;

    private ?string $plainPassword = "";

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getDomain(): ?Domain {
        return $this->domain;
    }

    public function setDomain(Domain $domain): self {
        $this->domain = $domain;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $plaintext): self {
        $salt = base64_encode(random_bytes(48));
        $hash = "{SHA512-CRYPT}". crypt($plaintext, '$6$'.$salt);
        $this->password = $hash;
        return $this;
    }

    public function getQuota(): ?int {
        return $this->quota;
    }

    public function setQuota(int $quota): self {
        $this->quota = $quota;

        return $this;
    }

    public function getEnabled(): ?bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSendonly(): ?bool {
        return $this->sendonly;
    }

    public function setSendonly(bool $sendonly): self {
        $this->sendonly = $sendonly;

        return $this;
    }

    public function getPlainPassword(): ?string {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void {
        $this->plainPassword = $plainPassword;
    }
}
