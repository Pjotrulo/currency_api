<?php

namespace App\Repository;

use App\Entity\ExchangeRates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeRates>
 *
 * @method ExchangeRates|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRates|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRates[]    findAll()
 * @method ExchangeRates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRates::class);
    }

    public function save(ExchangeRates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExchangeRates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCurrencyAndDate(string $currency, string $date): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT currency, date, amount FROM exchange_rates
            WHERE currency = :currency AND date = :date
        ';

        $statement = $conn->prepare($sql);
        $resultSet = $statement->executeQuery(['currency' => $currency, 'date' => $date]);

        return $resultSet->fetchAllAssociative();

    }

    public function findByDate(string $date): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT currency, date, amount FROM exchange_rates
            WHERE date = :date
        ';

        $statement = $conn->prepare($sql);
        $resultSet = $statement->executeQuery(['date' => $date]);

        return $resultSet->fetchAllAssociative();
    }

//    /**
//     * @return ExchangeRates[] Returns an array of ExchangeRates objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExchangeRates
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
