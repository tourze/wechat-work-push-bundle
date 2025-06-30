<?php

namespace WechatWorkPushBundle\Tests\Unit\Entity;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Entity\MarkdownMessage;

class MarkdownMessageTest extends TestCase
{
    private MarkdownMessage $markdownMessage;
    private AgentInterface&MockObject $mockAgent;

    protected function setUp(): void
    {
        $this->markdownMessage = new MarkdownMessage();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->expects($this->any())
            ->method('getAgentId')
            ->willReturn('1000002');
    }

    public function test_getId_returnsNullInitially(): void
    {
        $this->assertNull($this->markdownMessage->getId());
    }

    public function test_getMsgType_returnsMarkdown(): void
    {
        $this->assertEquals('markdown', $this->markdownMessage->getMsgType());
    }

    public function test_setContent_andGetContent(): void
    {
        $content = '# 标题\n这是**粗体**文本';
        $result = $this->markdownMessage->setContent($content);

        $this->assertSame($this->markdownMessage, $result);
        $this->assertEquals($content, $this->markdownMessage->getContent());
    }

    public function test_setContent_withMaxLength(): void
    {
        $content = str_repeat('a', 2048);
        $this->markdownMessage->setContent($content);

        $this->assertEquals($content, $this->markdownMessage->getContent());
    }

    public function test_setCreatedBy_andGetCreatedBy(): void
    {
        $userId = 'user123';
        $result = $this->markdownMessage->setCreatedBy($userId);

        $this->assertSame($this->markdownMessage, $result);
        $this->assertEquals($userId, $this->markdownMessage->getCreatedBy());
    }

    public function test_setCreatedFromIp_andGetCreatedFromIp(): void
    {
        $ip = '192.168.1.1';
        $result = $this->markdownMessage->setCreatedFromIp($ip);

        $this->assertSame($this->markdownMessage, $result);
        $this->assertEquals($ip, $this->markdownMessage->getCreatedFromIp());
    }

    public function test_setAgent_andGetAgent(): void
    {
        $result = $this->markdownMessage->setAgent($this->mockAgent);

        $this->assertSame($this->markdownMessage, $result);
        $this->assertSame($this->mockAgent, $this->markdownMessage->getAgent());
    }

    public function test_toRequestArray_withBasicData(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('# 测试标题\n这是**测试**内容');
        $this->markdownMessage->setToUser(['user1', 'user2']);

        $expectedArray = [
            'agentid' => '1000002',
            'touser' => 'user1|user2',
            'enable_duplicate_check' => 0,
            'duplicate_check_interval' => 1800,
            'msgtype' => 'markdown',
            'markdown' => [
                'content' => '# 测试标题\n这是**测试**内容'
            ]
        ];

        $this->assertEquals($expectedArray, $this->markdownMessage->toRequestArray());
    }

    public function test_toRequestArray_withAllUsers(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('通知所有人');
        $this->markdownMessage->setToUser(['@all']);

        $result = $this->markdownMessage->toRequestArray();

        $this->assertEquals('@all', $result['touser']);
        $this->assertArrayNotHasKey('toparty', $result);
        $this->assertArrayNotHasKey('totag', $result);
    }


    public function test_toRequestArray_withDuplicateCheckEnabled(): void
    {
        $this->markdownMessage->setAgent($this->mockAgent);
        $this->markdownMessage->setContent('重复检查消息');
        $this->markdownMessage->setEnableDuplicateCheck(true);
        $this->markdownMessage->setDuplicateCheckInterval(3600);

        $result = $this->markdownMessage->toRequestArray();

        $this->assertEquals(1, $result['enable_duplicate_check']);
        $this->assertEquals(3600, $result['duplicate_check_interval']);
    }


    public function test_setMsgId_andGetMsgId(): void
    {
        $msgId = 'msg_markdown_123';
        $result = $this->markdownMessage->setMsgId($msgId);

        $this->assertSame($this->markdownMessage, $result);
        $this->assertEquals($msgId, $this->markdownMessage->getMsgId());
    }

    public function test_toString_returnsStringId(): void
    {
        $result = $this->markdownMessage->__toString();
        $this->assertNotNull($result);
    }

    public function test_markdownContent_withComplexFormatting(): void
    {
        $complexMarkdown = <<<MD
# 主标题
## 副标题

**粗体文本** 和 *斜体文本*

- 列表项1
- 列表项2

[链接文本](https://example.com)

`代码片段`

```
代码块
```
MD;

        $this->markdownMessage->setContent($complexMarkdown);
        $this->assertEquals($complexMarkdown, $this->markdownMessage->getContent());
    }
}