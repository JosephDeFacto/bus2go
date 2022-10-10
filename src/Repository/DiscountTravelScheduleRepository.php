<?php

namespace App\Repository;

use App\Entity\DiscountTravelSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DiscountTravelSchedule>
 *
 * @method DiscountTravelSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiscountTravelSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiscountTravelSchedule[]    findAll()
 * @method DiscountTravelSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountTravelScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DiscountTravelSchedule::class);
    }

    public function add(DiscountTravelSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DiscountTravelSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return DiscountTravelSchedule[] Returns an array of DiscountTravelSchedule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DiscountTravelSchedule
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
