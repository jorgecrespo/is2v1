<?php

namespace App\Repository;

use App\Entity\Vacunas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vacunas>
 *
 * @method Vacunas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacunas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacunas[]    findAll()
 * @method Vacunas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacunasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vacunas::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vacunas $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Vacunas $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Vacunas[] Returns an array of Vacunas objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vacunas
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findOneById($value): ?Vacunas
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.id = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
}
