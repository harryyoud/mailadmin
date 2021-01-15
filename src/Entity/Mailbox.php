<?php

namespace App\Entity;

use App\Repository\MailboxRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=MailboxRepository::class)
 * @ORM\Table(name="accounts", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="username", columns={"username", "domain"})
 * })
 */
class Mailbox implements UserInterface {
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

    /**
     * @ORM\Column(type="boolean")
     */
    private $admin;

    private ?string $plainPassword = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getUsername() {
        return $this->username . "@" . $this->domain;
    }

    public function getLocalUsername(): ?string {
        return $this->username;
    }

    public function setLocalUsername(string $username): self {
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

    public function setPassword(string $password): self {
        $this->password = $password;
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

    public function setPlainPassword(?string $plainPassword): self {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getAdmin(): bool {
        return $this->admin;
    }

    public function setAdmin($admin): self {
        $this->admin = $admin;

        return $this;
    }

    public function getRoles() {
        $roles = ['ROLE_USER'];
        $this->admin ? ($roles[] = 'ROLE_ADMIN') : false;
        return $roles;
    }

    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getSalt(): string {
        return hash('sha256', $this->getUsername());
    }
}
