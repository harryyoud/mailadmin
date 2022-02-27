<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnyoneShare
 *
 * @ORM\Table(name="anyone_shares")
 * @ORM\Entity(readOnly=true)
 */
class AnyoneShare
{
    /**
     * @var string
     *
     * @ORM\Column(name="from_user", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fromUser;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dummy", type="string", length=1, nullable=true, options={"default"="1","fixed"=true})
     */
    private $dummy = '1';


}
