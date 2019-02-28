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
    public function findByMultiplFields( $refMantis = null, $client = null, $project = null, $typeInter = null, $user = null, $dateD = null, $dateF = null )
    {
        // comment on fait pour que ça fonctionne même 
        //si il n'y a que le client ou que la ref ou les deux ?
       

        $qb = $this->createQueryBuilder('t');

            if ($refMantis != null ){
                $qb->andWhere('t.refMantis = :val')
                ->setParameter('val', $refMantis);
            }

            if ($client != null ){
                $qb->andWhere('t.client = :val1')
                ->setParameter('val1', $client);
            }

            if ($project != null ){
                $qb->andWhere('t.project = :val2')
                ->setParameter('val2', $project);
            }

            if ($typeInter != null ){
                $qb->andWhere('t.typeInter = :val3')
                ->setParameter('val3', $typeInter);
            }
            
            if ($user != null ){
                $qb->andWhere('t.user = :val4')
                ->setParameter('val4', $user);
            }

            if ($dateD != null ){
                $qb->andWhere('t.date >= :val5')
                ->setParameter('val5', $dateD);
            }

            if ($dateF != null ){
                $qb->andWhere('t.date <= :val6')
                ->setParameter('val6', $dateF);
            }

            $qb->setMaxResults(1000);
        
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
