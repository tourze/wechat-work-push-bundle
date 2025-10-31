<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\MarkdownMessage;

/**
 * Markdown消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class MarkdownMessageFixtures extends AbstractMessageFixtures
{
    public const MARKDOWN_MESSAGE_ANNOUNCEMENT = 'markdown-message-announcement';
    public const MARKDOWN_MESSAGE_MEETING = 'markdown-message-meeting';
    public const MARKDOWN_MESSAGE_REPORT = 'markdown-message-report';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建 Markdown 消息测试数据
        $markdownMessage = new MarkdownMessage();
        $markdownMessage->setContent('# 测试标题\n\n这是测试内容');
        $markdownMessage->setAgent($agent);

        $manager->persist($markdownMessage);
        $manager->flush();

        $this->addReference(self::MARKDOWN_MESSAGE_ANNOUNCEMENT, $markdownMessage);
    }
}
