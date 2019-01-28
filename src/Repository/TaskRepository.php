<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
    * @return Task[] Returns an array of Task objects
    */
    
    //, $client, $typeInter, $ptojet
    public function findByMultiplFields( $refMantis, $client )
    {
        // comment on fait pour que ça fonctionne même 
        //si il n'y a que le client ou que la ref ou les deux ?
        return $this->createQueryBuilder('t')
            ->andWhere('t.client = :val', 't.refMantis = :val1')
            ->setParameter('val', $client)
            ->setParameter('val1', $refMantis)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Task
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
