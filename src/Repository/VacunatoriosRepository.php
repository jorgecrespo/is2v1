<?php

namespace App\Repository;

use App\Entity\Vacunatorios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vacunatorios>
 *
 * @method Vacunatorios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vacunatorios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vacunatorios[]    findAll()
 * @method Vacunatorios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VacunatoriosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vacunatorios::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Vacunatorios $entity, bool $flush = false): void
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
    public function remove(Vacunatorios $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Vacunatorios[] Returns an array of Vacunatorios objects
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

//    public function findOneBySomeField($value): ?Vacunatorios
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


      /**
    * @return Vacunatorios[] Returns an array of Pacientes objects
    */
    public function findAll(): array
    {
        return $this->createQueryBuilder('p')
         //    ->andWhere('p.exampleField = :val')
         //    ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
         //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneById($value): ?Vacunatorios
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.id = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}


}
