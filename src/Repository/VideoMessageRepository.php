<?php

namespace WechatWorkPushBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatWorkPushBundle\Entity\VideoMessage;

/**
 * @method VideoMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoMessage[]    findAll()
 * @method VideoMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoMessage::class);
    }
}
