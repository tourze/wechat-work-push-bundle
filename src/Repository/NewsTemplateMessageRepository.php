<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

/**
 * @method NewsTemplateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsTemplateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsTemplateMessage[]    findAll()
 * @method NewsTemplateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsTemplateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsTemplateMessage::class);
    }
}
