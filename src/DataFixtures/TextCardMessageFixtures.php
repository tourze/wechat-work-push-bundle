<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\TextCardMessage;

/**
 * 文本卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class TextCardMessageFixtures extends AbstractMessageFixtures
{
    public const TEXT_CARD_MESSAGE_REMINDER = 'text-card-message-reminder';
    public const TEXT_CARD_MESSAGE_NOTICE = 'text-card-message-notice';
    public const TEXT_CARD_MESSAGE_ALERT = 'text-card-message-alert';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建提醒消息
        $reminder = new TextCardMessage();
        $reminder->setAgent($agent);
        $reminder->setTitle('重要提醒');
        $reminder->setDescription('您有一项重要任务将于今天下午3点到期，请及时处理。');
        $reminder->setUrl('https://httpbin.org/html');
        $reminder->setBtnText('查看');
        $manager->persist($reminder);
        $this->setReference(self::TEXT_CARD_MESSAGE_REMINDER, $reminder);

        // 创建通知消息
        $notice = new TextCardMessage();
        $notice->setAgent($agent);
        $notice->setTitle('系统通知');
        $notice->setDescription('您的账户已成功升级为VIP会员，可以享受更多专属权益。');
        $notice->setUrl('https://httpbin.org/html');
        $notice->setBtnText('详情');
        $manager->persist($notice);
        $this->setReference(self::TEXT_CARD_MESSAGE_NOTICE, $notice);

        // 创建警告消息
        $alert = new TextCardMessage();
        $alert->setAgent($agent);
        $alert->setTitle('安全警告');
        $alert->setDescription('检测到您的账户在异地登录，请确认是否为本人操作。');
        $alert->setUrl('https://httpbin.org/html');
        $alert->setBtnText('立即');
        $manager->persist($alert);
        $this->setReference(self::TEXT_CARD_MESSAGE_ALERT, $alert);

        $manager->flush();
    }
}
