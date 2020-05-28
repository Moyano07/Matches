<?php

namespace App\Repository;

use App\Entity\Gol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gol|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gol|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gol[]    findAll()
 * @method Gol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gol::class);
    }

    // /**
    //  * @return Gol[] Returns an array of Gol objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gol
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
