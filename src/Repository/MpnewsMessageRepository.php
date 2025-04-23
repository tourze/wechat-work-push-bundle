<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\MpnewsMessage;

/**
 * @method MpnewsMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MpnewsMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MpnewsMessage[]    findAll()
 * @method MpnewsMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MpnewsMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MpnewsMessage::class);
    }
}
