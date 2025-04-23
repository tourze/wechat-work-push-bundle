<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\TextCardMessage;

/**
 * @method TextCardMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextCardMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextCardMessage[]    findAll()
 * @method TextCardMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextCardMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextCardMessage::class);
    }
}
