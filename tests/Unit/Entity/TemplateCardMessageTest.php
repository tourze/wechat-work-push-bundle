<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TemplateCardMessage;

class TemplateCardMessageTest extends TestCase
{
    private TemplateCardMessage&MockObject $templateCard;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->templateCard = $this->getMockForAbstractClass(TemplateCardMessage::class);
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getMsgType_returnsTemplateCard(): void
    {
        $this->assertEquals('template_card', $this->templateCard->getMsgType());
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '模板卡片标题';
        $result = $this->templateCard->setTitle($title);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($title, $this->templateCard->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是模板卡片的详细描述';
        $result = $this->templateCard->setDescription($description);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($description, $this->templateCard->getDescription());
    }

    public function test_setTaskId_andGetTaskId(): void
    {
        $taskId = 'task_123_abc';
        $result = $this->templateCard->setTaskId($taskId);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($taskId, $this->templateCard->getTaskId());
    }

    public function test_setTaskId_withNull(): void
    {
        $this->templateCard->setTaskId(null);
        $this->assertNull($this->templateCard->getTaskId());
    }

    public function test_setCreatedBy_andGetCreatedBy(): void
    {
        $userId = 'user123';
        $result = $this->templateCard->setCreatedBy($userId);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($userId, $this->templateCard->getCreatedBy());
    }

    public function test_setCreatedFromIp_andGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $result = $this->templateCard->setCreatedFromIp($ip);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($ip, $this->templateCard->getCreatedFromIp());
    }

    public function test_setAgent_andGetAgent(): void
    {
        $result = $this->templateCard->setAgent($this->mockAgent);

        $this->assertSame($this->templateCard, $result);
        $this->assertSame($this->mockAgent, $this->templateCard->getAgent());
    }

    public function test_retrieveAdminArray_includesBaseFields(): void
    {
        $this->templateCard->setAgent($this->mockAgent);
        $this->templateCard->setTitle('管理标题');
        $this->templateCard->setDescription('管理描述');
        $this->templateCard->setTaskId('admin_task_001');
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $this->templateCard->setCreateTime($createTime);

        $result = $this->templateCard->retrieveAdminArray();

        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertArrayHasKey('taskId', $result);
        $this->assertArrayHasKey('createTime', $result);
        $this->assertArrayHasKey('agentid', $result);
        $this->assertEquals('管理标题', $result['title']);
        $this->assertEquals('管理描述', $result['description']);
        $this->assertEquals('admin_task_001', $result['taskId']);
        $this->assertEquals('2024-01-01 10:00:00', $result['createTime']);
        $this->assertEquals('1000002', $result['agentid']);
    }

    public function test_setMsgId_andGetMsgId(): void
    {
        $msgId = 'template_msg_123';
        $result = $this->templateCard->setMsgId($msgId);

        $this->assertSame($this->templateCard, $result);
        $this->assertEquals($msgId, $this->templateCard->getMsgId());
    }

    public function test_timestampable_functionality(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $updateTime = new \DateTimeImmutable('2024-01-01 11:00:00');

        $this->templateCard->setCreateTime($createTime);
        $this->templateCard->setUpdateTime($updateTime);

        $this->assertEquals($createTime, $this->templateCard->getCreateTime());
        $this->assertEquals($updateTime, $this->templateCard->getUpdateTime());
    }

    public function test_blameable_functionality(): void
    {
        $this->templateCard->setCreatedBy('user123');
        $this->templateCard->setUpdatedBy('user456');

        $this->assertEquals('user123', $this->templateCard->getCreatedBy());
        $this->assertEquals('user456', $this->templateCard->getUpdatedBy());
    }

    public function test_safe_functionality(): void
    {
        $this->templateCard->setSafe(true);
        $this->assertTrue($this->templateCard->isSafe());

        $this->templateCard->setSafe(false);
        $this->assertFalse($this->templateCard->isSafe());
    }

    public function test_duplicateCheck_functionality(): void
    {
        $this->templateCard->setEnableDuplicateCheck(true);
        $this->templateCard->setDuplicateCheckInterval(3600);

        $this->assertTrue($this->templateCard->isEnableDuplicateCheck());
        $this->assertEquals(3600, $this->templateCard->getDuplicateCheckInterval());
    }
}