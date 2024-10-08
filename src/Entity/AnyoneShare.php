<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnyoneShare
 */
#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'anyone_shares')]
class AnyoneShare
{
    /**
     * @var string
     */
    #[ORM\Column(name: 'from_user', type: 'string', length: 100, nullable: false)]
    #[ORM\Id]
    private $fromUser;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'dummy', type: 'string', length: 1, nullable: true, options: ['default' => 1])]
    private $dummy = '1';


}
