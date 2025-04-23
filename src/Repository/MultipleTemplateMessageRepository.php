<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;

/**
 * @method MultipleTemplateMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultipleTemplateMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultipleTemplateMessage[]    findAll()
 * @method MultipleTemplateMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultipleTemplateMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MultipleTemplateMessage::class);
    }
}
