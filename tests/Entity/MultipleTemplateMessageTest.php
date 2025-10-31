<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;

/**
 * @internal
 */
#[CoversClass(MultipleTemplateMessage::class)]
final class MultipleTemplateMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MultipleTemplateMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        $now = new \DateTimeImmutable();

        return [
            // Traits属性
            'createTime' => ['createTime', $now],
            'updateTime' => ['updateTime', $now],
            'createdBy' => ['createdBy', '管理员'],
            'updatedBy' => ['updatedBy', '测试用户'],
            'createdFromIp' => ['createdFromIp', '192.168.1.100'],
            'updatedFromIp' => ['updatedFromIp', '10.0.0.1'],
            'msgId' => ['msgId', 'MSG_20240901_002'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'safe' => ['safe', true],
            'enableDuplicateCheck' => ['enableDuplicateCheck', false],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 1800],

            // TemplateCardMessage基类属性
            'title' => ['title', '多项选择调研问卷'],
            'description' => ['description', '请选择您认为重要的技术栈'],
            'taskId' => ['taskId', 'TASK_SURVEY_001'],

            // MultipleTemplateMessage自身属性
            'questionKey' => ['questionKey', 'tech_stack_preference'],
            'options' => ['options', [
                ['id' => 'react', 'text' => 'React'],
                ['id' => 'vue', 'text' => 'Vue.js'],
                ['id' => 'angular', 'text' => 'Angular'],
                ['id' => 'svelte', 'text' => 'Svelte'],
            ]],
        ];
    }

    private MultipleTemplateMessage $multipleTemplate;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->multipleTemplate = new MultipleTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->multipleTemplate->getId());
    }

    public function testToStringReturnsStringId(): void
    {
        $this->multipleTemplate->setDescription('描述内容');
        $this->multipleTemplate->setQuestionKey('question_key');
        $this->multipleTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
        ]);
        $result = $this->multipleTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function testGetCardTypeReturnsMultipleInteraction(): void
    {
        // 测试公共API行为而不是私有方法
        $this->multipleTemplate->setAgent($this->mockAgent);
        $this->multipleTemplate->setTitle('Test Title');
        $this->multipleTemplate->setDescription('Test Description');
        $this->multipleTemplate->setQuestionKey('test_question');
        $this->multipleTemplate->setOptions([
            ['id' => '1', 'text' => 'Option 1'],
        ]);
        $result = $this->multipleTemplate->toRequestArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('card_type', $templateCard);
        $this->assertEquals('multiple_interaction', $templateCard['card_type']);
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '多选交互卡片';
        $this->multipleTemplate->setTitle($title);

        $this->assertEquals($title, $this->multipleTemplate->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是多选交互卡片描述';
        $this->multipleTemplate->setDescription($description);

        $this->assertEquals($description, $this->multipleTemplate->getDescription());
    }

    public function testToRequestArrayReturnsTemplateCardType(): void
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
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertEquals('multiple_interaction', $templateCard['card_type']);
    }

    public function testRetrieveAdminArrayIncludesBaseFields(): void
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
