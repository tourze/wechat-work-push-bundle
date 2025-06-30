<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;

class MultipleTemplateMessageTest extends TestCase
{
    private MultipleTemplateMessage $multipleTemplate;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->multipleTemplate = new MultipleTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->multipleTemplate->getId());
    }

    public function test_toString_returnsStringId(): void
    {
        $this->multipleTemplate->setDescription('描述内容');
        $this->multipleTemplate->setQuestionKey('question_key');
        $this->multipleTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
        ]);
        $result = $this->multipleTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function test_getCardType_returnsMultipleInteraction(): void
    {
        $reflection = new \ReflectionClass($this->multipleTemplate);
        $method = $reflection->getMethod('getCardType');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->multipleTemplate);
        $this->assertEquals('multiple_interaction', $result);
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '多选交互卡片';
        $result = $this->multipleTemplate->setTitle($title);

        $this->assertSame($this->multipleTemplate, $result);
        $this->assertEquals($title, $this->multipleTemplate->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是多选交互卡片描述';
        $result = $this->multipleTemplate->setDescription($description);

        $this->assertSame($this->multipleTemplate, $result);
        $this->assertEquals($description, $this->multipleTemplate->getDescription());
    }

    public function test_toRequestArray_returnsTemplateCardType(): void
    {
        $this->multipleTemplate->setAgent($this->mockAgent);
        $this->multipleTemplate->setTitle('测试标题');
        $this->multipleTemplate->setDescription('测试描述');
        $this->multipleTemplate->setQuestionKey('question_001');
        $this->multipleTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
            ['id' => '2', 'text' => '选项2'],
        ]);

        $result = $this->multipleTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $this->assertEquals('multiple_interaction', $result['template_card']['card_type']);
    }

    public function test_retrieveAdminArray_includesBaseFields(): void
    {
        $this->multipleTemplate->setAgent($this->mockAgent);
        $this->multipleTemplate->setTitle('管理标题');
        $this->multipleTemplate->setDescription('管理描述');
        $this->multipleTemplate->setQuestionKey('admin_question_001');
        $this->multipleTemplate->setOptions([
            ['id' => '1', 'text' => '管理选项1'],
            ['id' => '2', 'text' => '管理选项2'],
        ]);

        $result = $this->multipleTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理标题', $result['title']);
        $this->assertEquals('管理描述', $result['description']);
    }
}