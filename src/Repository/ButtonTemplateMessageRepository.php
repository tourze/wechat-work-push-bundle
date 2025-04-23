<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;

/**
 * @method ButtonTemplateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ButtonTemplateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ButtonTemplateMessage[]    findAll()
 * @method ButtonTemplateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ButtonTemplateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ButtonTemplateMessage::class);
    }
}
