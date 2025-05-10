<?php

namespace WechatWorkPushBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

class SendMessageRequestTest extends TestCase
{
    private SendMessageRequest $request;
    private Agent $agent;
    private TextMessage $message;

    protected function setUp(): void
    {
        $this->request = new SendMessageRequest();
        
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');
        
        $this->message = $this->createMock(TextMessage::class);
        $this->message->method('toRequestArray')->willReturn([
            'msgtype' => 'text',
            'text' => [
                'content' => '测试消息',
            ],
        ]);
        
        $this->request->setAgent($this->agent);
        $this->request->setMessage($this->message);
    }

    public function testGetRequestPath_returnsCorrectPath(): void
    {
        $this->assertEquals('/cgi-bin/message/send', $this->request->getRequestPath());
    }

    public function testGetRequestOptions_returnsCorrectOptions(): void
    {
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('agentid', $options['json']);
        $this->assertEquals('1000001', $options['json']['agentid']);
        $this->assertArrayHasKey('msgtype', $options['json']);
        $this->assertEquals('text', $options['json']['msgtype']);
        $this->assertArrayHasKey('text', $options['json']);
        $this->assertIsArray($options['json']['text']);
        $this->assertArrayHasKey('content', $options['json']['text']);
        $this->assertEquals('测试消息', $options['json']['text']['content']);
    }

    public function testGetMessage_returnsSetMessage(): void
    {
        $this->assertSame($this->message, $this->request->getMessage());
    }

    public function testSetMessage_setsMessage(): void
    {
        $newMessage = $this->createMock(TextMessage::class);
        $this->request->setMessage($newMessage);
        $this->assertSame($newMessage, $this->request->getMessage());
    }
} 