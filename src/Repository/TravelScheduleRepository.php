<?php

namespace App\Repository;

use App\Entity\TravelSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TravelSchedule>
 *
 * @method TravelSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelSchedule[]    findAll()
 * @method TravelSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelSchedule::class);
    }

    public function add(TravelSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TravelSchedule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchForBuses($departFrom, $travelTo, $departingOn, $returningOn): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = "SELECT * FROM travel_schedule WHERE depart_from LIKE '%$departFrom%' AND travel_to LIKE '%$travelTo%' AND departing_on LIKE '%$departingOn%' AND returning_on LIKE '%$returningOn%'";
        $stmt = $connection->prepare($query);
        $result = $stmt->executeQuery(['depart_from' => $departFrom, 'travel_to' => $travelTo, 'departing_on' => $departingOn, 'returning_on' => $returningOn]);


        return $result->fetchAllAssociative();



        // WORKS FOR NOW!
        /* $connection = $this->getEntityManager()->getConnection();

         $query = 'SELECT * FROM travel_schedule WHERE travel_to = "Rijeka"';
         $stmt = $connection->prepare($query);
         $result = $stmt->executeQuery(['travel_to']);

         return $result->fetchAllAssociative();*/

        // THIS WORKS TOO
        /* $qb = $this->createQueryBuilder('t')
             ->where('t.fee = :fee')
             ->setParameter('fee', $fee);


         $query = $qb->getQuery();
         return $query->execute();*/
    }

    public function countSearchResult($departFrom, $travelTo, $departingOn, $returningOn): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = "SELECT COUNT(*) FROM travel_schedule WHERE depart_from LIKE '%$departFrom%' AND travel_to LIKE '%$travelTo%' AND departing_on LIKE '%$departingOn%' AND returning_on LIKE '%$returningOn%'";
        $stmt = $connection->prepare($query);
        $result = $stmt->executeQuery(['depart_from' => $departFrom, 'travel_to' => $travelTo, 'departing_on' => $departingOn, 'returning_on' => $returningOn]);

        return $result->fetchAllAssociative();
    }


//    /**
//     * @return TravelSchedule[] Returns an array of TravelSchedule objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TravelSchedule
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
