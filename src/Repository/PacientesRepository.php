<?php

namespace App\Repository;

use App\Entity\Pacientes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pacientes>
 *
 * @method Pacientes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pacientes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pacientes[]    findAll()
 * @method Pacientes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PacientesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pacientes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Pacientes $entity, bool $flush = false): void
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
    public function remove(Pacientes $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

//    /**
//     * @return Pacientes[] Returns an array of Pacientes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pacientes
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

   public function findOneByEmail($mail): ?Pacientes
   {
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT  p
        FROM App\Entity\Pacientes p
        WHERE p.mail = :mail'
    )->setParameter('mail', $mail);

    // returns an array of Product objects
    $busqueda = $query->getResult();
    
    if (count($busqueda) > 0)
        return $query->getResult()[0];
    else 
        return null;
   }

   public function findOneById($id): ?Pacientes
   {
    $entityManager = $this->getEntityManager();

    $query = $entityManager->createQuery(
        'SELECT  p
        FROM App\Entity\Pacientes p
        WHERE p.id = :id'
    )->setParameter('id', $id);

    // returns an array of Product objects
    $busqueda = $query->getResult();
    
    if (count($busqueda) > 0)
        return $query->getResult()[0];
    else 
        return null;
   }


      /**
    * @return Pacientes[] Returns an array of Pacientes objects
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


}
