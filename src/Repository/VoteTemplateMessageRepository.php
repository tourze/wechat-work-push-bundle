<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

/**
 * @method VoteTemplateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoteTemplateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoteTemplateMessage[]    findAll()
 * @method VoteTemplateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteTemplateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoteTemplateMessage::class);
    }
}
