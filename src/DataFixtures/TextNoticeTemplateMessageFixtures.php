<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;

/**
 * 文本通知型模板卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class TextNoticeTemplateMessageFixtures extends AbstractMessageFixtures
{
    public const TEXT_NOTICE_TEMPLATE_MESSAGE_SYSTEM = 'text-notice-template-message-system';
    public const TEXT_NOTICE_TEMPLATE_MESSAGE_SECURITY = 'text-notice-template-message-security';
    public const TEXT_NOTICE_TEMPLATE_MESSAGE_UPDATE = 'text-notice-template-message-update';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建系统通知消息
        $systemMessage = new TextNoticeTemplateMessage();
        $systemMessage->setAgent($agent);
        $systemMessage->setTitle('系统维护通知');
        $systemMessage->setDescription('系统将于今晚22:00-24:00进行升级维护');
        $systemMessage->setUrl('https://httpbin.org/html');
        $systemMessage->setBtnText('了解详情');
        $systemMessage->setTaskId('task_system_' . uniqid());
        $manager->persist($systemMessage);
        $this->setReference(self::TEXT_NOTICE_TEMPLATE_MESSAGE_SYSTEM, $systemMessage);

        // 创建安全通知消息
        $securityMessage = new TextNoticeTemplateMessage();
        $securityMessage->setAgent($agent);
        $securityMessage->setTitle('安全警告');
        $securityMessage->setDescription('发现异常登录行为，请及时检查帐户安全');
        $securityMessage->setUrl('https://httpbin.org/html');
        $securityMessage->setBtnText('立即检查');
        $securityMessage->setTaskId('task_security_' . uniqid());
        $manager->persist($securityMessage);
        $this->setReference(self::TEXT_NOTICE_TEMPLATE_MESSAGE_SECURITY, $securityMessage);

        // 创建更新通知消息
        $updateMessage = new TextNoticeTemplateMessage();
        $updateMessage->setAgent($agent);
        $updateMessage->setTitle('版本更新通知');
        $updateMessage->setDescription('新版本v2.1.0已发布，新增多项实用功能');
        $updateMessage->setUrl('https://httpbin.org/html');
        $updateMessage->setBtnText('查看更新');
        $updateMessage->setTaskId('task_update_' . uniqid());
        $manager->persist($updateMessage);
        $this->setReference(self::TEXT_NOTICE_TEMPLATE_MESSAGE_UPDATE, $updateMessage);

        $manager->flush();
    }
}
