<?php

namespace App\Repository;

use App\Entity\ProfileExamRelated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfileExamRelated>
 *
 * @method ProfileExamRelated|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileExamRelated|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileExamRelated[]    findAll()
 * @method ProfileExamRelated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileExamRelatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileExamRelated::class);
    }

    //    /**
    //     * @return ProfileExamRelated[] Returns an array of ProfileExamRelated objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProfileExamRelated
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
