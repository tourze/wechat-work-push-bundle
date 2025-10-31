<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\NewsMessage;

/**
 * 图文消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class NewsMessageFixtures extends AbstractMessageFixtures
{
    public const NEWS_MESSAGE_ANNOUNCEMENT = 'news-message-announcement';
    public const NEWS_MESSAGE_PROMOTION = 'news-message-promotion';
    public const NEWS_MESSAGE_UPDATE = 'news-message-update';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建公司公告
        $announcement = new NewsMessage();
        $announcement->setAgent($agent);
        $announcement->setTitle('重要公告：系统升级通知');
        $announcement->setDescription('系统将于本周末进行重要升级，预计耗时4小时，期间服务可能暂时中断。');
        $announcement->setUrl('https://httpbin.org/html');
        $announcement->setPicUrl('https://httpbin.org/image/png');
        $manager->persist($announcement);
        $this->setReference(self::NEWS_MESSAGE_ANNOUNCEMENT, $announcement);

        // 创建促销消息
        $promotion = new NewsMessage();
        $promotion->setAgent($agent);
        $promotion->setTitle('限时优惠：年度促销活动');
        $promotion->setDescription('年度最大促销活动开始了！全场商品8折起，满1000减200，快来抢购吧！');
        $promotion->setUrl('https://httpbin.org/html');
        $promotion->setPicUrl('https://httpbin.org/image/jpeg');
        $manager->persist($promotion);
        $this->setReference(self::NEWS_MESSAGE_PROMOTION, $promotion);

        // 创建更新消息
        $update = new NewsMessage();
        $update->setAgent($agent);
        $update->setTitle('产品更新：v2.0版本发布');
        $update->setDescription('新版本带来了全新的用户界面、性能优化和多项新功能，欢迎体验！');
        $update->setUrl('https://httpbin.org/html');
        $update->setPicUrl('https://httpbin.org/image/webp');
        $manager->persist($update);
        $this->setReference(self::NEWS_MESSAGE_UPDATE, $update);

        $manager->flush();
    }
}
