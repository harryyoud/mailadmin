<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tlspolicies")
 */
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

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $policy;

    /**
     * @ORM\Column(type="string", length=255)
     */
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
}
