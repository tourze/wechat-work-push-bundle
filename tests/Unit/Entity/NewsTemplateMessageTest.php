<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;

class NewsTemplateMessageTest extends TestCase
{
    private NewsTemplateMessage $newsTemplate;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->newsTemplate = new NewsTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->newsTemplate->getId());
    }

    public function test_toString_returnsStringId(): void
    {
        $this->newsTemplate->setDescription('描述内容');
        $this->newsTemplate->setUrl('https://example.com');
        $this->newsTemplate->setImageUrl('https://example.com/image.jpg');
        $result = $this->newsTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function test_getCardType_returnsNewsNotification(): void
    {
        $reflection = new \ReflectionClass($this->newsTemplate);
        $method = $reflection->getMethod('getCardType');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->newsTemplate);
        $this->assertEquals('news_notice', $result);
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '新闻通知卡片';
        $result = $this->newsTemplate->setTitle($title);

        $this->assertSame($this->newsTemplate, $result);
        $this->assertEquals($title, $this->newsTemplate->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是新闻通知卡片描述';
        $result = $this->newsTemplate->setDescription($description);

        $this->assertSame($this->newsTemplate, $result);
        $this->assertEquals($description, $this->newsTemplate->getDescription());
    }

    public function test_toRequestArray_returnsTemplateCardType(): void
    {
        $this->newsTemplate->setAgent($this->mockAgent);
        $this->newsTemplate->setTitle('测试新闻');
        $this->newsTemplate->setDescription('测试新闻描述');
        $this->newsTemplate->setUrl('https://example.com/news');
        $this->newsTemplate->setImageUrl('https://example.com/image.jpg');

        $result = $this->newsTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $this->assertEquals('news_notice', $result['template_card']['card_type']);
    }

    public function test_retrieveAdminArray_includesBaseFields(): void
    {
        $this->newsTemplate->setAgent($this->mockAgent);
        $this->newsTemplate->setTitle('管理新闻');
        $this->newsTemplate->setDescription('管理新闻描述');
        $this->newsTemplate->setUrl('https://example.com/admin');
        $this->newsTemplate->setImageUrl('https://example.com/admin-image.jpg');

        $result = $this->newsTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理新闻', $result['title']);
        $this->assertEquals('管理新闻描述', $result['description']);
    }
}