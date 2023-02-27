<?php

namespace App\Repository;

use App\Entity\TravelSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

use PDO;


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

    public function searchForBuses($departFrom, $travelTo): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('t')
            ->where($qb->expr()->like('t.departFrom', ':departFrom'))
            ->orWhere($qb->expr()->like('t.travelTo', ':travelTo'))
            ->setParameter('departFrom', '%'.$departFrom.'%')
            ->setParameter('travelTo', '%'.$travelTo.'%');

        return $qb->getQuery()->getResult();
    }

    public function countSearchResult($departFrom, $travelTo): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = "SELECT COUNT(*) FROM travel_schedule WHERE depart_from LIKE '%$departFrom%' AND travel_to LIKE '%$travelTo%'";
        $stmt = $connection->prepare($query);
        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    public function busCompany(int $id)
    {
        return $this->createQueryBuilder('t')
            ->select('t')
            ->andWhere('t.busCompany = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
