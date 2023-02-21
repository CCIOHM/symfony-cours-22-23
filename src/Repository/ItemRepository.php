<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 *
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Item $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /*
     * Exercice Entity 4 - Part 2
     */

    public function findByLabelAndMinimalQty(string $search = '', int $minQuantity = 0, bool $orderByAsc = true)
    {
        return $this
            ->getQueryFindByLabelAndMinimalQty($search, $minQuantity, $orderByAsc)
            ->getQuery()
            ->getResult();
    }

    public function getQueryFindByLabelAndMinimalQty(string $search = '', int $minQuantity = 0, bool $orderByAsc = true): QueryBuilder
    {
        $query = $this->createQueryBuilder('i');

        $this->addSearchToQuery($query, $search)
            ->addMinimalQtyToQuery($query, $minQuantity)
            ->addOrderByPriceToQuery($query, $orderByAsc);

        return $query;
    }

    public function addSearchToQuery(QueryBuilder $query, ?string $search): self
    {
        $search = trim($search);
        if ($search) {
            $query->andWhere('i.label LIKE :search')
                ->setParameter('search', '%'.$search.'%')
                ->orWhere('i.location LIKE :search')
                ->setParameter('search', '%'.$search.'%');
        }

        return $this;
    }

    public function addMinimalQtyToQuery(QueryBuilder $query, ?int $minQuantity): self
    {
        if ($minQuantity > 0) {
            $query->andWhere('i.quantity > :quantity')
                ->setParameter('quantity', $minQuantity);
        }

        return $this;
    }

    public function addOrderByPriceToQuery(QueryBuilder $query, bool $asc = true): self
    {
        $query->orderBy('i.price', $asc ? 'ASC' : 'DESC');

        return $this;
    }

    /*
     * Exercice Entity 4 - Part 1
     */

    public function findByDocQueryLang($id)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT i.id, i.label
                FROM App\Entity\Item i
                WHERE i.id = :id'
            )
            ->setParameters(['id' => $id]);

        return $query->getResult()[0] ?? null;
    }

    public function findByNative($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        $query = $conn->prepare(
            'SELECT i.id, i.label 
            FROM item i
            WHERE i.id = :id'
        );

        return $query
            ->executeQuery(['id' => $id])
            ->fetchAssociative() ?: null;
    }

    public function deleteByDocQueryLang($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('DELETE FROM App\Entity\Item i WHERE i.id = :id')
            ->setParameters(['id' => $id]);

        return $query->getResult();
    }

    public function deleteByNative($id)
    {
        $conn = $this->getEntityManager()->getConnection();

        return $conn->executeStatement(
            'DELETE FROM item WHERE id = :id',
            ['id' => $id]
        );
    }

    /*
     * Doctrine Query Builder Examples
     */

//    /**
//     * @return Item[] Returns an array of Item objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Item
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
