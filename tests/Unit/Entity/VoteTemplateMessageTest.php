<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;

class VoteTemplateMessageTest extends TestCase
{
    private VoteTemplateMessage $voteTemplate;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->voteTemplate = new VoteTemplateMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->voteTemplate->getId());
    }

    public function test_toString_returnsStringId(): void
    {
        $this->voteTemplate->setDescription('描述内容');
        $this->voteTemplate->setQuestionKey('question_key');
        $this->voteTemplate->setOptions([
            ['id' => '1', 'text' => '选项1'],
        ]);
        $result = $this->voteTemplate->__toString();
        $this->assertNotNull($result);
    }

    public function test_getCardType_returnsVoteInteraction(): void
    {
        $reflection = new \ReflectionClass($this->voteTemplate);
        $method = $reflection->getMethod('getCardType');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->voteTemplate);
        $this->assertEquals('vote_interaction', $result);
    }

    public function test_setTitle_andGetTitle(): void
    {
        $title = '投票交互卡片';
        $result = $this->voteTemplate->setTitle($title);

        $this->assertSame($this->voteTemplate, $result);
        $this->assertEquals($title, $this->voteTemplate->getTitle());
    }

    public function test_setDescription_andGetDescription(): void
    {
        $description = '这是投票交互卡片描述';
        $result = $this->voteTemplate->setDescription($description);

        $this->assertSame($this->voteTemplate, $result);
        $this->assertEquals($description, $this->voteTemplate->getDescription());
    }

    public function test_toRequestArray_returnsTemplateCardType(): void
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
        $this->assertEquals('vote_interaction', $result['template_card']['card_type']);
    }

    public function test_retrieveAdminArray_includesBaseFields(): void
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