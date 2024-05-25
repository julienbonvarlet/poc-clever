<?php

namespace App\Repository;

use App\Entity\TradeInCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TradeInCart>
 *
 * @method TradeInCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method TradeInCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method TradeInCart[]    findAll()
 * @method TradeInCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TradeInCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TradeInCart::class);
    }
}
