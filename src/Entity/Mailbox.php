<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'accounts')]
#[ORM\UniqueConstraint(name: 'username', columns: ['username', 'domain'])]
class Mailbox implements \Stringable {

    public function __construct() {
        $this->appPasswords = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\.]*/',
        message: 'Username can only contain alphanumerics, dots and hyphens.',
    )]
    private $username;

    #[ManyToOne(targetEntity: \Domain::class)]
    #[JoinColumn(name: 'domain', referencedColumnName: 'domain', nullable: false)]
    private $domain;

    #[ORM\OneToMany(targetEntity: \Password::class, mappedBy: 'mailbox', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $appPasswords;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\Column(type: 'integer')]
    private $quota;

    #[ORM\Column(type: 'boolean')]
    private $enabled;

    #[ORM\Column(type: 'boolean')]
    private $sendonly;

    #[ORM\Column(type: 'string', length: 255)]
    private $allowedIps = '0.0.0.0/0,::0/0';

    private ?string $plainPassword = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getAddress() {
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

    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getSalt(): string {
        return hash('sha256', $this->getAddress());
    }

    public function getAppPasswords() {
        return $this->appPasswords;
    }

    public function __toString(): string {
        return (string) $this->getAddress();
    }

    public function addAppPassword(Password $password) {
        $this->appPasswords->add($password);
        $password->setMailbox($this);
    }

    public function removeAppPassword(Password $password) {
        $this->appPasswords->removeElement($password);
        $password->setMailbox(null);
    }

    public function getAllowedIps() {
        return $this->allowedIps;
    }

    public function setAllowedIps($allowedIps): void {
        $this->allowedIps = $allowedIps;
    }
}
