<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\TextMessage;

/**
 * 文本消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class TextMessageFixtures extends AbstractMessageFixtures
{
    public const TEXT_MESSAGE_WELCOME = 'text-message-welcome';
    public const TEXT_MESSAGE_ANNOUNCEMENT = 'text-message-announcement';
    public const TEXT_MESSAGE_REMINDER = 'text-message-reminder';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建欢迎文本消息
        $welcomeMessage = new TextMessage();
        $welcomeMessage->setAgent($agent);
        $welcomeMessage->setContent('欢迎使用企业微信数字化办公平台！我们将为您提供高效便捷的办公服务。');
        $manager->persist($welcomeMessage);
        $this->setReference(self::TEXT_MESSAGE_WELCOME, $welcomeMessage);

        // 创建公告文本消息
        $announcementMessage = new TextMessage();
        $announcementMessage->setAgent($agent);
        $announcementMessage->setContent('系统维护通知：为提升系统性能，今日 22:00-24:00 进行系统维护，期间暂停服务。请做好相关准备，谢谢理解！');
        $manager->persist($announcementMessage);
        $this->setReference(self::TEXT_MESSAGE_ANNOUNCEMENT, $announcementMessage);

        // 创建提醒文本消息
        $reminderMessage = new TextMessage();
        $reminderMessage->setAgent($agent);
        $reminderMessage->setContent('友情提醒：您有一个会议将在 30 分钟后开始，会议主题：项目进度讨论。请提前做好准备。');
        $manager->persist($reminderMessage);
        $this->setReference(self::TEXT_MESSAGE_REMINDER, $reminderMessage);

        $manager->flush();
    }
}
