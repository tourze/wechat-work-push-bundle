<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;

class TextNoticeTemplateMessageTest extends TestCase
{
    private TextNoticeTemplateMessage $textNoticeTemplate;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->textNoticeTemplate = new TextNoticeTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->textNoticeTemplate->getId());
    }

    public function test_toString_returnsStringId(): void
    {
        $this->textNoticeTemplate->setDescription('描述内容');
        $this->textNoticeTemplate->setUrl('https://example.com');
        $result = $this->textNoticeTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function test_getCardType_returnsTextNotification(): void
    {
        $reflection = new \ReflectionClass($this->textNoticeTemplate);
        $method = $reflection->getMethod('getCardType');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->textNoticeTemplate);
        $this->assertEquals('text_notice', $result);
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '文本通知卡片';
        $result = $this->textNoticeTemplate->setTitle($title);

        $this->assertSame($this->textNoticeTemplate, $result);
        $this->assertEquals($title, $this->textNoticeTemplate->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是文本通知卡片描述';
        $result = $this->textNoticeTemplate->setDescription($description);

        $this->assertSame($this->textNoticeTemplate, $result);
        $this->assertEquals($description, $this->textNoticeTemplate->getDescription());
    }

    public function test_toRequestArray_returnsTemplateCardType(): void
    {
        $this->textNoticeTemplate->setAgent($this->mockAgent);
        $this->textNoticeTemplate->setTitle('测试文本通知');
        $this->textNoticeTemplate->setDescription('测试描述');
        $this->textNoticeTemplate->setUrl('https://example.com/text-notice');

        $result = $this->textNoticeTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $this->assertEquals('text_notice', $result['template_card']['card_type']);
    }

    public function test_retrieveAdminArray_includesBaseFields(): void
    {
        $this->textNoticeTemplate->setAgent($this->mockAgent);
        $this->textNoticeTemplate->setTitle('管理文本通知');
        $this->textNoticeTemplate->setDescription('管理描述');
        $this->textNoticeTemplate->setUrl('https://example.com/admin-text');

        $result = $this->textNoticeTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理文本通知', $result['title']);
        $this->assertEquals('管理描述', $result['description']);
    }
}