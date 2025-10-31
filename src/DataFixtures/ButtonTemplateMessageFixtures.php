<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;

/**
 * 按钮交互型模板卡片消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class ButtonTemplateMessageFixtures extends AbstractMessageFixtures
{
    public const BUTTON_TEMPLATE_MESSAGE_WELCOME = 'button-template-message-welcome';
    public const BUTTON_TEMPLATE_MESSAGE_CONFIRM = 'button-template-message-confirm';
    public const BUTTON_TEMPLATE_MESSAGE_ACTION = 'button-template-message-action';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建欢迎消息
        $welcomeMessage = new ButtonTemplateMessage();
        $welcomeMessage->setAgent($agent);
        $welcomeMessage->setTitle('欢迎使用系统');
        $welcomeMessage->setDescription('点击下方按钮开始体验');
        $welcomeMessage->setUrl('https://httpbin.org/html');
        $welcomeMessage->setButtonText('开始体验');
        $welcomeMessage->setButtonKey('welcome_start');
        $welcomeMessage->setTaskId('task_welcome_' . uniqid());
        $manager->persist($welcomeMessage);
        $this->setReference(self::BUTTON_TEMPLATE_MESSAGE_WELCOME, $welcomeMessage);

        // 创建确认消息
        $confirmMessage = new ButtonTemplateMessage();
        $confirmMessage->setAgent($agent);
        $confirmMessage->setTitle('操作确认');
        $confirmMessage->setDescription('请确认是否继续操作');
        $confirmMessage->setUrl('https://httpbin.org/html');
        $confirmMessage->setButtonText('确认');
        $confirmMessage->setButtonKey('confirm_action');
        $confirmMessage->setTaskId('task_confirm_' . uniqid());
        $manager->persist($confirmMessage);
        $this->setReference(self::BUTTON_TEMPLATE_MESSAGE_CONFIRM, $confirmMessage);

        // 创建行动消息
        $actionMessage = new ButtonTemplateMessage();
        $actionMessage->setAgent($agent);
        $actionMessage->setTitle('立即行动');
        $actionMessage->setDescription('现在就开始你的任务');
        $actionMessage->setUrl('https://httpbin.org/html');
        $actionMessage->setButtonText('立即开始');
        $actionMessage->setButtonKey('action_now');
        $actionMessage->setTaskId('task_action_' . uniqid());
        $manager->persist($actionMessage);
        $this->setReference(self::BUTTON_TEMPLATE_MESSAGE_ACTION, $actionMessage);

        $manager->flush();
    }
}
