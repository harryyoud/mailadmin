<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserShare
 *
 * @ORM\Table(name="user_shares", indexes={@ORM\Index(name="to_user", columns={"to_user"})})
 * @ORM\Entity(readOnly=true)
 */
class UserShare
{
    /**
     * @var string
     *
     * @ORM\Column(name="from_user", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $fromUser;

    /**
     * @var string
     *
     * @ORM\Column(name="to_user", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $toUser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dummy", type="string", length=1, nullable=true, options={"default"="1","fixed"=true})
     */
    private $dummy = '1';


}
