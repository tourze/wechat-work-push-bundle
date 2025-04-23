<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;

/**
 * @method MiniProgramNoticeMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MiniProgramNoticeMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MiniProgramNoticeMessage[]    findAll()
 * @method MiniProgramNoticeMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MiniProgramNoticeMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MiniProgramNoticeMessage::class);
    }
}
