<?php

namespace App\Repository;

use App\Entity\Subdivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subdivision>
 *
 * @method Subdivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subdivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subdivision[]    findAll()
 * @method Subdivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubdivisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subdivision::class);
    }

    public function add(Subdivision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subdivision $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
