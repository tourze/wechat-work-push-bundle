<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\ImageMessage;

/**
 * 图片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class ImageMessageFixtures extends AbstractMessageFixtures
{
    public const IMAGE_MESSAGE_BANNER = 'image-message-banner';
    public const IMAGE_MESSAGE_CHART = 'image-message-chart';
    public const IMAGE_MESSAGE_PHOTO = 'image-message-photo';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建横幅图片消息
        $bannerMessage = new ImageMessage();
        $bannerMessage->setAgent($agent);
        $bannerMessage->setMediaId('media_banner_' . uniqid());
        $manager->persist($bannerMessage);
        $this->setReference(self::IMAGE_MESSAGE_BANNER, $bannerMessage);

        // 创建图表图片消息
        $chartMessage = new ImageMessage();
        $chartMessage->setAgent($agent);
        $chartMessage->setMediaId('media_chart_' . uniqid());
        $manager->persist($chartMessage);
        $this->setReference(self::IMAGE_MESSAGE_CHART, $chartMessage);

        // 创建照片消息
        $photoMessage = new ImageMessage();
        $photoMessage->setAgent($agent);
        $photoMessage->setMediaId('media_photo_' . uniqid());
        $manager->persist($photoMessage);
        $this->setReference(self::IMAGE_MESSAGE_PHOTO, $photoMessage);

        $manager->flush();
    }
}
