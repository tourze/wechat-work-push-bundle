<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;

/**
 * @method TextNoticeTemplateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextNoticeTemplateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextNoticeTemplateMessage[]    findAll()
 * @method TextNoticeTemplateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextNoticeTemplateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextNoticeTemplateMessage::class);
    }
}
