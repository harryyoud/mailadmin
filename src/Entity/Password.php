<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Password
 *
 * @ORM\Table(name="passwords", indexes={@ORM\Index(name="IDX_ED822B16A69FE20B", columns={"mailbox"})}, uniqueConstraints={
 *     @ORM\UniqueConstraint(name="app_password", columns={"app_name", "mailbox"})
 * })
 * @ORM\Entity
 */
class Password
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="app_name", type="string", length=255, nullable=false)
     */
    private $appName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Mailbox", inversedBy="appPasswords")
     * @ORM\JoinColumn(name="mailbox", referencedColumnName="id", nullable=false)
     */
    private $mailbox;

    private ?string $plainPassword = null;

    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAppName(): string {
        return $this->appName;
    }

    public function setAppName(string $appName): void {
        $this->appName = $appName;
    }

    public function getPlainPassword(): ?string {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void {
        $this->plainPassword = $plainPassword;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = $password;
    }

    public function getMailbox() {
        return $this->mailbox;
    }

    public function setMailbox($mailbox): void {
        $this->mailbox = $mailbox;
    }

    public function __toString() {
        return $this->appName;
    }

    public static function createRandomPassword() {
        $token = "";

        $codeAlphabet = "ABCDEFGHKMNPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghkmnpqrstuvwxyz";
        $codeAlphabet.= "23456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < 16; $i++) {
            $token .= $codeAlphabet[mt_rand(0, $max-1)];
        }

        return $token;
    }
}
