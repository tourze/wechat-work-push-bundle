<?php

namespace WechatWorkPushBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\WechatWorkContracts\AgentInterface;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

/**
 * @internal
 */
#[CoversClass(SendMessageRequest::class)]
final class SendMessageRequestTest extends RequestTestCase
{
    private SendMessageRequest $request;

    private AgentInterface $mockAgent;

    private AppMessage $mockMessage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new SendMessageRequest();
        $this->mockAgent = $this->createMock(AgentInterface::class);
        $this->mockAgent->method('getAgentId')->willReturn('1000002');
        $this->mockMessage = $this->createMock(AppMessage::class);
        $this->mockMessage->method('toRequestArray')->willReturn([
            'msgtype' => 'text',
            'text' => ['content' => 'Test message'],
        ]);
        $this->mockMessage->method('getMsgType')->willReturn('text');
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $this->assertEquals('cgi-bin/message/send', $this->request->getRequestPath());
    }

    public function testSetMessageAndGetMessage(): void
    {
        $this->request->setMessage($this->mockMessage);

        $this->assertSame($this->mockMessage, $this->request->getMessage());
    }

    public function testGetRequestOptionsWithValidAgentAndMessage(): void
    {
        $this->request->setAgent($this->mockAgent);
        $this->request->setMessage($this->mockMessage);

        $options = $this->request->getRequestOptions();

        $expectedJson = [
            'agentid' => '1000002',
            'msgtype' => 'text',
            'text' => ['content' => 'Test message'],
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }

    public function testGetRequestOptionsMergesAgentIdWithMessageArray(): void
    {
        // Create a custom message mock for this test
        $mockMessage = $this->createMock(AppMessage::class);
        $mockMessage->method('toRequestArray')->willReturn([
            'msgtype' => 'image',
            'image' => ['media_id' => 'media123'],
            'touser' => 'user1',
        ]);
        $mockMessage->method('getMsgType')->willReturn('image');

        // Create a custom agent mock for this test
        $mockAgent = $this->createMock(AgentInterface::class);
        $mockAgent->method('getAgentId')->willReturn('2000003');

        $this->request->setAgent($mockAgent);
        $this->request->setMessage($mockMessage);

        $options = $this->request->getRequestOptions();

        $expectedJson = [
            'agentid' => '2000003',
            'msgtype' => 'image',
            'image' => ['media_id' => 'media123'],
            'touser' => 'user1',
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }
}
