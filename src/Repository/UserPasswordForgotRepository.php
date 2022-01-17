<?php

namespace App\Repository;

use App\Entity\UserPasswordForgot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPasswordForgot|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPasswordForgot|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPasswordForgot[]    findAll()
 * @method UserPasswordForgot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPasswordForgotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPasswordForgot::class);
    }

    public function findByToken($value): ?UserPasswordForgot
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.token = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserPasswordForgot
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
