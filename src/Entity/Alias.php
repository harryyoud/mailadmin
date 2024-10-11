<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'aliases')]
#[ORM\UniqueConstraint(name: 'username', columns: ['source_username', 'source_domain', 'destination_username', 'destination_domain'])]
class Alias {
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\.]*/',
        message: 'Username can only contain alphanumerics, dots and hyphens. To use a wildcard, leave this field blank',
    )]
    private $source_username;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\.]*/',
        message: 'Domain can only contain alphanumerics, dots and hyphens. To use a wildcard, leave this field blank',
    )]
    private $source_domain;

    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\.]*/',
        message: 'Username can only contain alphanumerics, dots and hyphens.',
    )]
    private $destination_username;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Regex(
        pattern: '/[A-Za-z0-9\-\.]*/',
        message: 'Domain can only contain alphanumerics, dots and hyphens.',
    )]
    private $destination_domain;

    #[ORM\Column(type: 'boolean')]
    private $can_send;

    #[ORM\Column(type: 'boolean')]
    private $can_receive;

    #[ORM\Column(type: 'string', length: 255)]
    private $comment = "";


    public function getId(): ?int {
        return $this->id;
    }

    public function getSourceAddress(): string {
        $u = $this->source_username ?? "*";
        $d = $this->source_domain ?? "*";
        return $u . "@" . $d;
    }

    public function getSourceUsername(): ?string {
        return $this->source_username;
    }

    public function setSourceUsername(?string $source_username): self {
        $this->source_username = $source_username;

        return $this;
    }

    public function getSourceDomain(): ?string {
        return $this->source_domain;
    }

    public function setSourceDomain(?string $source_domain): self {
        $this->source_domain = $source_domain;

        return $this;
    }

    public function getDestinationAddress(): string {
        return $this->destination_username . "@" . $this->destination_domain;
    }

    public function getDestinationUsername(): ?string {
        return $this->destination_username;
    }

    public function setDestinationUsername(string $destination_username): self {
        $this->destination_username = $destination_username;

        return $this;
    }

    public function getDestinationDomain(): ?string {
        return $this->destination_domain;
    }

    public function setDestinationDomain(string $destination_domain): self {
        $this->destination_domain = $destination_domain;

        return $this;
    }

    public function getCanSend() {
        return $this->can_send;
    }

    public function setCanSend($can_send): self {
        $this->can_send = $can_send;

        return $this;
    }

    public function getCanReceive() {
        return $this->can_receive;
    }

    public function setCanReceive($can_receive) {
        $this->can_receive = $can_receive;

        return $this;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(string $comment): self {
        $this->comment = $comment;

        return $this;
    }

}
