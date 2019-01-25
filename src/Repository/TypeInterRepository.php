<?php

namespace App\Repository;

use App\Entity\TypeInter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeInter|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeInter|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeInter[]    findAll()
 * @method TypeInter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeInterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeInter::class);
    }

    // /**
    //  * @return TypeInter[] Returns an array of TypeInter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeInter
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
