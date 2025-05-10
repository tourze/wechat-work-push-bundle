<?php

namespace WechatWorkPushBundle\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Service\WorkService;
use WechatWorkMediaBundle\Entity\TempMedia;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

class SendMessageListenerTest extends TestCase
{
    private WorkService $workService;
    private EntityManagerInterface $entityManager;
    private TestSendMessageListener $listener;
    private Agent $agent;

    protected function setUp(): void
    {
        $this->workService = $this->createMock(WorkService::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->listener = new TestSendMessageListener($this->workService, $this->entityManager);
        
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');
    }

    public function testPostPersist_withTextMessage_sendsMessage(): void
    {
        // 准备测试数据
        $message = new TextMessage();
        $message->setAgent($this->agent);
        $message->setContent('测试消息');

        // 设置预期
        $this->workService->expects($this->once())
            ->method('request')
            ->with($this->callback(function (SendMessageRequest $request) {
                return $request->getMessage() instanceof TextMessage
                    && $request->getAgent() === $this->agent;
            }))
            ->willReturn(['msgid' => '123456789']);

        // 执行测试
        $this->listener->testPostPersist($message);
        
        // 验证消息ID是否被设置
        $this->assertEquals('123456789', $message->getMsgId());
    }

    public function testPostPersist_withMpnewsMessage_createsMediaAndSendsMessage(): void
    {
        // 准备测试数据
        $message = new MpnewsMessage();
        $message->setAgent($this->agent);
        $message->setThumbMediaUrl('http://example.com/image.jpg');

        // 设置预期 - 应创建临时媒体文件
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($media) {
                if (!$media instanceof TempMedia) {
                    return false;
                }
                // 设置媒体ID模拟entityManager的处理
                $media->setMediaId('MEDIA_ID_123');
                return $media->getFileUrl() === 'http://example.com/image.jpg';
            }));
        
        $this->entityManager->expects($this->once())
            ->method('flush');

        // 设置预期 - 应发送消息
        $this->workService->expects($this->once())
            ->method('request')
            ->with($this->callback(function (SendMessageRequest $request) {
                return $request->getMessage() instanceof MpnewsMessage
                    && $request->getAgent() === $this->agent;
            }))
            ->willReturn(['msgid' => '123456789']);

        // 执行测试
        $this->listener->testPostPersist($message);
        
        // 验证结果
        $this->assertEquals('MEDIA_ID_123', $message->getThumbMediaId());
        $this->assertEquals('123456789', $message->getMsgId());
    }

    public function testPostPersist_whenResponseHasNoMsgId_doesNotUpdateMessage(): void
    {
        // 准备测试数据
        $message = new TextMessage();
        $message->setAgent($this->agent);
        $message->setContent('测试消息');

        // 设置预期 - 返回没有 msgid 的响应
        $this->workService->expects($this->once())
            ->method('request')
            ->willReturn(['errcode' => 40013, 'errmsg' => 'invalid appid']);

        // 执行测试
        $this->listener->testPostPersist($message);
        
        // 验证消息ID保持为null
        $this->assertNull($message->getMsgId());
    }
} 