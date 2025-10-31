<?php

namespace WechatWorkPushBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

/**
 * @internal
 */
#[CoversClass(VoteTemplateMessage::class)]
final class VoteTemplateMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new VoteTemplateMessage();
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
            'msgId' => ['msgId', 'MSG_20240901_003'],
            'toUser' => ['toUser', ['zhangsan', 'lisi']],
            'toParty' => ['toParty', ['1', '2']],
            'toTag' => ['toTag', ['开发组', '产品组']],
            'safe' => ['safe', false],
            'enableDuplicateCheck' => ['enableDuplicateCheck', true],
            'duplicateCheckInterval' => ['duplicateCheckInterval', 2700],

            // TemplateCardMessage基类属性
            'title' => ['title', '团队活动投票'],
            'description' => ['description', '请投票选择下周团建活动地点'],
            'taskId' => ['taskId', 'TASK_VOTE_001'],

            // VoteTemplateMessage自身属性
            'questionKey' => ['questionKey', 'team_activity_location'],
            'options' => ['options', [
                ['id' => 'park', 'text' => '公园野餐'],
                ['id' => 'ktv', 'text' => 'KTV聚会'],
                ['id' => 'restaurant', 'text' => '餐厅聚餐'],
                ['id' => 'cinema', 'text' => '电影院观影'],
            ]],
        ];
    }

    private VoteTemplateMessage $voteTemplate;

    private AgentInterface $mockAgent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->voteTemplate = new VoteTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
    }

    public function testGetIdReturnsNullInitially(): void
    {
        $this->assertNull($this->voteTemplate->getId());
    }

    public function testToStringReturnsStringId(): void
    {
        $this->voteTemplate->setDescription('描述内容');
        $this->voteTemplate->setQuestionKey('question_key');
        $this->voteTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
        ]);
        $result = $this->voteTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function testGetCardTypeReturnsVoteInteraction(): void
    {
        // 测试公共API行为而不是私有方法
        $this->voteTemplate->setAgent($this->mockAgent);
        $this->voteTemplate->setTitle('Test Title');
        $this->voteTemplate->setDescription('Test Description');
        $this->voteTemplate->setQuestionKey('test_question');
        $this->voteTemplate->setOptions([
            ['id' => '1', 'text' => 'Option 1'],
        ]);
        $result = $this->voteTemplate->toRequestArray();
        $this->assertIsArray($result);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertArrayHasKey('card_type', $templateCard);
        $this->assertEquals('vote_interaction', $templateCard['card_type']);
    }

    public function testSetTitleAndGetTitle(): void
    {
        $title = '投票交互卡片';
        $this->voteTemplate->setTitle($title);

        $this->assertEquals($title, $this->voteTemplate->getTitle());
    }

    public function testSetDescriptionAndGetDescription(): void
    {
        $description = '这是投票交互卡片描述';
        $this->voteTemplate->setDescription($description);

        $this->assertEquals($description, $this->voteTemplate->getDescription());
    }

    public function testToRequestArrayReturnsTemplateCardType(): void
    {
        $this->voteTemplate->setAgent($this->mockAgent);
        $this->voteTemplate->setTitle('测试投票');
        $this->voteTemplate->setDescription('测试投票描述');
        $this->voteTemplate->setQuestionKey('question_001');
        $this->voteTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
            ['id' => '2', 'text' => '选项2'],
        ]);

        $result = $this->voteTemplate->toRequestArray();

        $this->assertEquals('template_card', $result['msgtype']);
        $this->assertArrayHasKey('template_card', $result);
        $templateCard = $result['template_card'];
        $this->assertIsArray($templateCard);
        $this->assertEquals('vote_interaction', $templateCard['card_type']);
    }

    public function testRetrieveAdminArrayIncludesBaseFields(): void
    {
        $this->voteTemplate->setAgent($this->mockAgent);
        $this->voteTemplate->setTitle('管理投票');
        $this->voteTemplate->setDescription('管理投票描述');
        $this->voteTemplate->setQuestionKey('admin_question_001');
        $this->voteTemplate->setOptions([
            ['id' => '1', 'text' => '管理选项1'],
            ['id' => '2', 'text' => '管理选项2'],
        ]);

        $result = $this->voteTemplate->retrieveAdminArray();

        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('description', $result);
        $this->assertEquals('管理投票', $result['title']);
        $this->assertEquals('管理投票描述', $result['description']);
    }
}
