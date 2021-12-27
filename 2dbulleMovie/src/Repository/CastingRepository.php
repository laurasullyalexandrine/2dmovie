<?php

namespace App\Repository;

use App\Entity\Casting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    public function findOneByMoviePerson($id)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("
        SELECT c, m, p
        FROM App\Entity\Casting c
        LEFT JOIN c.movie m
        LEFT JOIN c.person p
        WHERE c.id = :id
        ORDER BY c.personage DESC
        ")->setParameter(':id', $id);

        return $query->getOneOrNullResult();
    }
    // /**
    //  * @return Casting[] Returns an array of Casting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Casting
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
