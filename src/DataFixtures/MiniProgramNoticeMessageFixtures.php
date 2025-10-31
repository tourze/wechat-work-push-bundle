<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;

/**
 * 小程序通知消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class MiniProgramNoticeMessageFixtures extends AbstractMessageFixtures
{
    public const MINI_PROGRAM_MESSAGE_ORDER = 'mini-program-message-order';
    public const MINI_PROGRAM_MESSAGE_MEETING = 'mini-program-message-meeting';
    public const MINI_PROGRAM_MESSAGE_TASK = 'mini-program-message-task';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建订单通知消息
        $orderMessage = new MiniProgramNoticeMessage();
        $orderMessage->setAgent($agent);
        $orderMessage->setAppId('wx1234567890abcdef');
        $orderMessage->setTitle('订单通知');
        $orderMessage->setDescription('您的订单状态已更新');
        $orderMessage->setPage('pages/order/detail?id=123');
        $orderMessage->setEmphasisFirstItem(true);
        $orderMessage->setContentItem([
            'order_id' => ['key' => '订单号', 'value' => 'ORD' . uniqid()],
            'status' => ['key' => '订单状态', 'value' => '已发货'],
            'delivery' => ['key' => '预计到达', 'value' => '2024-01-15'],
        ]);
        $manager->persist($orderMessage);
        $this->setReference(self::MINI_PROGRAM_MESSAGE_ORDER, $orderMessage);

        // 创建会议通知消息
        $meetingMessage = new MiniProgramNoticeMessage();
        $meetingMessage->setAgent($agent);
        $meetingMessage->setAppId('wx1234567890abcdef');
        $meetingMessage->setTitle('会议通知');
        $meetingMessage->setDescription('您有一个新的会议邀请');
        $meetingMessage->setPage('pages/meeting/detail?id=456');
        $meetingMessage->setEmphasisFirstItem(false);
        $meetingMessage->setContentItem([
            'topic' => ['key' => '会议主题', 'value' => '周会议'],
            'time' => ['key' => '会议时间', 'value' => '2024-01-10 10:00'],
            'location' => ['key' => '会议地点', 'value' => 'A座301会议室'],
        ]);
        $manager->persist($meetingMessage);
        $this->setReference(self::MINI_PROGRAM_MESSAGE_MEETING, $meetingMessage);

        // 创建任务通知消息
        $taskMessage = new MiniProgramNoticeMessage();
        $taskMessage->setAgent($agent);
        $taskMessage->setAppId('wx1234567890abcdef');
        $taskMessage->setTitle('任务通知');
        $taskMessage->setDescription('您有一个新的任务待处理');
        $taskMessage->setPage('pages/task/detail?id=789');
        $taskMessage->setEmphasisFirstItem(true);
        $taskMessage->setContentItem([
            'task_name' => ['key' => '任务名称', 'value' => '系统测试'],
            'deadline' => ['key' => '截止日期', 'value' => '2024-01-20'],
            'priority' => ['key' => '优先级', 'value' => '高'],
        ]);
        $manager->persist($taskMessage);
        $this->setReference(self::MINI_PROGRAM_MESSAGE_TASK, $taskMessage);

        $manager->flush();
    }
}
