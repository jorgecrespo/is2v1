<?php

namespace App\Repository;

use App\Entity\Aplicaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Aplicaciones>
 *
 * @method Aplicaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Aplicaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Aplicaciones[]    findAll()
 * @method Aplicaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AplicacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Aplicaciones::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Aplicaciones $entity, bool $flush = false): void
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
    public function remove(Aplicaciones $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Aplicaciones[] Returns an array of Aplicaciones objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Aplicaciones
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findOneById($value): ?Aplicaciones
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.id = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}

public function findOneByTurnoId($turnoId): ?Aplicaciones
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.turno_id = :val')
        ->setParameter('val', $turnoId)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}





}
