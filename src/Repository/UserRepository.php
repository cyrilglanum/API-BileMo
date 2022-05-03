<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function add(Users $user): bool
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
        return true;
    }

    public function delete(Users $user): bool
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
        return true;
    }
}
