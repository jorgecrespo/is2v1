<?php

namespace App\Repository;

use App\Entity\Turnos;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Turnos>
 *
 * @method Turnos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Turnos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Turnos[]    findAll()
 * @method Turnos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TurnosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Turnos::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Turnos $entity, bool $flush = false): void
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
    public function remove(Turnos $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Turnos[] Returns an array of Turnos objects
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

//    public function findOneBySomeField($value): ?Turnos
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   /**
    * @return Turnos[] Returns an array of Turnos objects
    */
   public function findTurnosByUser($userId): array
   {
       return $this->createQueryBuilder('t')
           ->andWhere('t.paciente_id = :val')
           ->setParameter('val', $userId)
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   

      /**
    * @return Turnos[] Returns an array of Turnos objects
    */
    public function findTurnosByVacunatorio($vacunatorioId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.vacunatorio_id = :val')
            ->setParameter('val', $vacunatorioId)
         //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

          /**
    * @return Turnos[] Returns an array of Turnos objects
    */
    public function findTurnosByVacuna($vacunaId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.vacuna_id = :val')
            ->setParameter('val', $vacunaId)
         //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneById($id): ?Turnos
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByPacienteAndVacunaId($pacienteId, $vacunaId): ?Turnos
{
    $estado = 'APLICADA';
    return $this->createQueryBuilder('u')
        ->andWhere('u.paciente_id = :val')
        ->setParameter('val', $pacienteId)
        ->andWhere('u.vacuna_id = :val')
        ->setParameter('val', $vacunaId)
        ->andWhere('u.estado = :val')
        ->setParameter('val', $estado)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}


     /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function bajaTurno(int $idTurno): void
    {
        $turno = $this->findOneById($idTurno);
        if (isset($turno)){
            $turno->setEstado('CANCELADO');
        }

        
         $this->_em->flush();
     
    }

}
