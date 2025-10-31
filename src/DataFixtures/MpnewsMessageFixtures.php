<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\MpnewsMessage;

/**
 * 图文消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class MpnewsMessageFixtures extends AbstractMessageFixtures
{
    public const MPNEWS_MESSAGE_TECH = 'mpnews-message-tech';
    public const MPNEWS_MESSAGE_BUSINESS = 'mpnews-message-business';
    public const MPNEWS_MESSAGE_NEWS = 'mpnews-message-news';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建技术图文消息
        $techMessage = new MpnewsMessage();
        $techMessage->setAgent($agent);
        $techMessage->setTitle('最新技术分享');
        $techMessage->setContent('本文介绍了最新的PHP 8.4特性及最佳实践，包括新的语法糖、性能优化和安全性增强。');
        $techMessage->setThumbMediaUrl('https://httpbin.org/image/png');
        $techMessage->setThumbMediaId('tech_thumb_' . uniqid());
        $techMessage->setDigest('PHP 8.4最新特性详解');
        $techMessage->setContentSourceUrl('https://httpbin.org/html');
        $manager->persist($techMessage);
        $this->setReference(self::MPNEWS_MESSAGE_TECH, $techMessage);

        // 创建商业图文消息
        $businessMessage = new MpnewsMessage();
        $businessMessage->setAgent($agent);
        $businessMessage->setTitle('行业商业动态');
        $businessMessage->setContent('本文分析了当前的市场趋势、竞争格局及发展机遇，为企业决策提供参考。');
        $businessMessage->setThumbMediaUrl('https://httpbin.org/image/png');
        $businessMessage->setThumbMediaId('business_thumb_' . uniqid());
        $businessMessage->setDigest('市场趋势分析报告');
        $businessMessage->setContentSourceUrl('https://httpbin.org/html');
        $manager->persist($businessMessage);
        $this->setReference(self::MPNEWS_MESSAGE_BUSINESS, $businessMessage);

        // 创建新闻图文消息
        $newsMessage = new MpnewsMessage();
        $newsMessage->setAgent($agent);
        $newsMessage->setTitle('企业新闻速讻');
        $newsMessage->setContent('本周企业重要新闻汇总：人事变动、项目进展、合作伙伴更新等内容。');
        $newsMessage->setThumbMediaUrl('https://httpbin.org/image/png');
        $newsMessage->setThumbMediaId('news_thumb_' . uniqid());
        $newsMessage->setDigest('企业周报内容概要');
        $newsMessage->setContentSourceUrl('https://httpbin.org/html');
        $manager->persist($newsMessage);
        $this->setReference(self::MPNEWS_MESSAGE_NEWS, $newsMessage);

        $manager->flush();
    }
}
