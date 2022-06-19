<?php

namespace App\Repository;

use App\Entity\Notificaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notificaciones>
 *
 * @method Notificaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notificaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notificaciones[]    findAll()
 * @method Notificaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notificaciones::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Notificaciones $entity, bool $flush = false): void
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
    public function remove(Notificaciones $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Notificaciones[] Returns an array of Notificaciones objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notificaciones
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   /**
    * @return Notificaciones[] Returns an array of Notificaciones objects
    */
   public function findNotificacionesByTurnoIdAndAntelacion($turnoId, $diasAntelacion): array
   {
       return $this->createQueryBuilder('n')
           ->andWhere('n.turno_id = :val1')
           ->setParameter('val1', $turnoId)
           ->andWhere('n.antelacion = :val2')
           ->setParameter('val2', $diasAntelacion)
           ->getQuery()
           ->getResult()
       ;
   }


}
