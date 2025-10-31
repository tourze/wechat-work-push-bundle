<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\VideoMessage;

/**
 * 视频消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class VideoMessageFixtures extends AbstractMessageFixtures
{
    public const VIDEO_MESSAGE_TRAINING = 'video-message-training';
    public const VIDEO_MESSAGE_PRESENTATION = 'video-message-presentation';
    public const VIDEO_MESSAGE_DEMO = 'video-message-demo';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建培训视频消息
        $trainingMessage = new VideoMessage();
        $trainingMessage->setAgent($agent);
        $trainingMessage->setMediaId('media_training_video_' . uniqid());
        $trainingMessage->setTitle('新员工入职培训');
        $trainingMessage->setDescription('本视频包含公司文化、组织架构、工作流程等内容，请认真学习。');
        $manager->persist($trainingMessage);
        $this->setReference(self::VIDEO_MESSAGE_TRAINING, $trainingMessage);

        // 创建演示视频消息
        $presentationMessage = new VideoMessage();
        $presentationMessage->setAgent($agent);
        $presentationMessage->setMediaId('media_presentation_video_' . uniqid());
        $presentationMessage->setTitle('产品发布会演示');
        $presentationMessage->setDescription('介绍最新产品功能特性，包含操作演示和案例分享。');
        $manager->persist($presentationMessage);
        $this->setReference(self::VIDEO_MESSAGE_PRESENTATION, $presentationMessage);

        // 创建演示视频消息
        $demoMessage = new VideoMessage();
        $demoMessage->setAgent($agent);
        $demoMessage->setMediaId('media_demo_video_' . uniqid());
        $demoMessage->setTitle('系统操作演示');
        $demoMessage->setDescription('步骤详细的系统操作指导，帮助您快速上手使用。');
        $manager->persist($demoMessage);
        $this->setReference(self::VIDEO_MESSAGE_DEMO, $demoMessage);

        $manager->flush();
    }
}
