<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TemplateCardMessage;

/**
 * @internal
 */
#[CoversClass(TemplateCardMessage::class)]
final class TemplateCardMessageTest extends TestCase
{
    private TemplateCardMessage $templateCard;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->templateCard = new class extends TemplateCardMessage {
            protected function getCardType(): string
            {
                return 'text_notice';
            }

            /**
             * @return array<string, mixed>
             */
            public function getTemplateCard(): array
            {
                return [
                    'card_type' => 'text_notice',
                    'main_title' => [
                        'title' => $this->getTitle(),
                        'desc' => $this->getDescription(),
                    ],
                    'emphasis_content' => [
                        'title' => $this->getTitle(),
                        'desc' => $this->getDescription(),
                    ],
                ];
            }

            /**
             * @return array<string, mixed>
             */
            public function retrieveApiArray(): array
            {
                return [
                    'msgtype' => $this->getMsgType(),
                    'agentid' => $this->getAgent()->getAgentId(),
                    'template_card' => $this->getTemplateCard(),
                    'safe' => true === $this->isSafe() ? 1 : 0,
                    'enable_duplicate_check' => true === $this->isEnableDuplicateCheck() ? 1 : 0,
                    'duplicate_check_interval' => $this->getDuplicateCheckInterval(),
                ];
            }
        };
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetMsgTypeReturnsTemplateCard(): void
    {
        $this->assertEquals('template_card', $this->templateCard->getMsgType());
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '模板卡片标题';
        $this->templateCard->setTitle($title);

        $this->assertEquals($title, $this->templateCard->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是模板卡片的详细描述';
        $this->templateCard->setDescription($description);

        $this->assertEquals($description, $this->templateCard->getDescription());
    }

    public function testSetTaskIdAndGetTaskId(): void
    {
        $taskId = 'task_123_abc';
        $this->templateCard->setTaskId($taskId);

        $this->assertEquals($taskId, $this->templateCard->getTaskId());
    }

    public function testSetTaskIdWithNull(): void
    {
        $this->templateCard->setTaskId(null);
        $this->assertNull($this->templateCard->getTaskId());
    }

    public function testSetCreatedByAndGetCreatedBy(): void
    {
        $userId = 'user123';
        $this->templateCard->setCreatedBy($userId);

        $this->assertEquals($userId, $this->templateCard->getCreatedBy());
    }

    public function testSetCreatedFromIpAndGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $this->templateCard->setCreatedFromIp($ip);

        $this->assertEquals($ip, $this->templateCard->getCreatedFromIp());
    }

    public function testSetAgentAndGetAgent(): void
    {
        $this->templateCard->setAgent($this->mockAgent);

        $this->assertSame($this->mockAgent, $this->templateCard->getAgent());
    }

    public function testRetrieveAdminArrayIncludesBaseFields(): void
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

    public function testSetMsgIdAndGetMsgId(): void
    {
        $msgId = 'template_msg_123';
        $this->templateCard->setMsgId($msgId);

        $this->assertEquals($msgId, $this->templateCard->getMsgId());
    }

    public function testTimestampableFunctionality(): void
    {
        $createTime = new \DateTimeImmutable('2024-01-01 10:00:00');
        $updateTime = new \DateTimeImmutable('2024-01-01 11:00:00');

        $this->templateCard->setCreateTime($createTime);
        $this->templateCard->setUpdateTime($updateTime);

        $this->assertEquals($createTime, $this->templateCard->getCreateTime());
        $this->assertEquals($updateTime, $this->templateCard->getUpdateTime());
    }

    public function testBlameableFunctionality(): void
    {
        $this->templateCard->setCreatedBy('user123');
        $this->templateCard->setUpdatedBy('user456');

        $this->assertEquals('user123', $this->templateCard->getCreatedBy());
        $this->assertEquals('user456', $this->templateCard->getUpdatedBy());
    }

    public function testSafeFunctionality(): void
    {
        $this->templateCard->setSafe(true);
        $this->assertTrue($this->templateCard->isSafe());

        $this->templateCard->setSafe(false);
        $this->assertFalse($this->templateCard->isSafe());
    }
}
