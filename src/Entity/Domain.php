<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'domains')]
class Domain implements \Stringable {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
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
