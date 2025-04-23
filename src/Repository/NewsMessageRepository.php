<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\NewsMessage;

/**
 * @method NewsMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsMessage[]    findAll()
 * @method NewsMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsMessage::class);
    }
}
