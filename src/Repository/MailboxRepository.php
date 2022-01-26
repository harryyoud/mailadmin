<?php


namespace App\Repository;


use App\Entity\Mailbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MailboxRepository extends ServiceEntityRepository implements UserLoaderInterface {
     public function __construct(ManagerRegistry $registry) {
         parent::__construct($registry, Mailbox::class);
     }

    public function loadUserByIdentifier(string $username): ?UserInterface {
        $split = explode("@", $username);
        if (count($split) != 2) {
            return null;
        }
        return $this->getEntityManager()->createQuery(
            'SELECT u FROM App\Entity\Mailbox u
            WHERE u.username = :username AND
            u.domain = :domain'
        )
            ->setParameter('username', $split[0])
            ->setParameter('domain', $split[1])
            ->getOneOrNullResult();
    }
}
