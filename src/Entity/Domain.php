<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'domains')]
class Domain implements \Stringable {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\Regex(
        pattern: '/^[A-Za-z0-9\-\.]*$/',
        message: 'Domain can only contain alphanumerics, dots and hyphens.',
    )]
    private $domain;

    public function getDomain(): ?string {
        return $this->domain;
    }

    public function setDomain(string $domain): self {
        $this->domain = $domain;

        return $this;
    }

    public function __toString(): string {
        return (string) $this->domain;
    }

}
