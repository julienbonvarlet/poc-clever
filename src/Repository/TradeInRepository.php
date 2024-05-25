<?php

namespace App\Repository;

use App\Entity\TradeIn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TradeIn>
 *
 * @method TradeIn|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeIn|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeIn[]    findAll()
 * @method TradeIn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeInRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TradeIn::class);
    }
}
