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
    public function findByMultiplFields( $refMantis = null , $client = null )
    {
        // comment on fait pour que ça fonctionne même 
        //si il n'y a que le client ou que la ref ou les deux ?
       

        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.refMantis = :val1');

            if ($client != null ){
                //var_dump($client);
                $qb->andWhere('t.client = :val')
                ->setParameter('val', $client);
            }
            $qb->setParameter('val1', $refMantis)
            ->setMaxResults(10)
        ;
        
        return $qb->getQuery()
            ->getResult();

        

        /*
        return $this->createQueryBuilder('t')
            ->andWhere('t.refMantis = :val1')
            ->andWhere( 't.client = :val')
            ->setParameter('val', $client)
            ->setParameter('val1', $refMantis)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        */
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
