<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'tlspolicies')]
class TlsPolicy {
    const POLICIES = [
        'none',
        'may',
        'encrypt',
        'dane',
        'dane-only',
        'fingerprint',
        'verify',
        'secure',
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Must have domain')]
    #[Assert\Regex(pattern: '/^\[?([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\]?(:[0-9]{1,5})?\]?$/', message: 'Domain must conform to Postfix format (valid domain name, optionally in square brackets, optionally with a port)')]
    private $domain;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Choice(self::POLICIES, message: 'Must be one of Postfix TLS policies', multiple: false)]
    private $policy = "";

    #[ORM\Column(type: 'string', length: 255)]
    private $params;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDomain(): ?string {
        return $this->domain;
    }

    public function setDomain(string $domain): self {
        $this->domain = $domain;

        return $this;
    }

    public function getPolicy(): ?string {
        return $this->policy;
    }

    public function setPolicy(string $policy): self {
        $this->policy = $policy;

        return $this;
    }

    public function getParams(): ?string {
        return $this->params;
    }

    public function setParams(string $params): self {
        $this->params = $params;

        return $this;
    }

    #[Assert\IsTrue(message: 'Domain must have matching brackets')]
    public function hasMatchedBracketsInDomain() {
        return substr_count($this->domain, '[') ==
            substr_count($this->domain, ']');
    }
}
