<?php

namespace App\Repository;

use App\Entity\CartTicket;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartTicket>
 *
 * @method CartTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartTicket[]    findAll()
 * @method CartTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartTicket::class);
    }

    public function add(CartTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CartTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function checkTicket(User $user)
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = "SELECT * FROM cart_ticket WHERE user_id = '$user'";
        $stmt = $connection->prepare($query);
        $stmt->bindValue(':user_id', $user);

        $result = $stmt->executeQuery();

        return $result->fetchAllAssociative();
    }

    public function emptyCart($id)
    {
        $connection = $this->getEntityManager()->getConnection();
        $query = "DELETE FROM cart_ticket WHERE user_id = '{$id}'";
        $stmt = $connection->prepare($query);
        $stmt->bindValue(':user_id', $id);

        return $stmt->executeQuery();
    }



//    /**
//     * @return CartTicket[] Returns an array of CartTicket objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CartTicket
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
