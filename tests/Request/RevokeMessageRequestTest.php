<?php

namespace WechatWorkPushBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

class RevokeMessageRequestTest extends TestCase
{
    private RevokeMessageRequest $request;
    private Agent $agent;

    protected function setUp(): void
    {
        $this->request = new RevokeMessageRequest();
        
        $this->agent = $this->createMock(Agent::class);
        $this->agent->method('getAgentId')->willReturn('1000001');
        
        $this->request->setAgent($this->agent);
        $this->request->setMsgId('123456789');
    }

    public function testGetRequestPath_returnsCorrectPath(): void
    {
        $this->assertEquals('/cgi-bin/message/recall', $this->request->getRequestPath());
    }

    public function testGetRequestOptions_returnsCorrectOptions(): void
    {
        $options = $this->request->getRequestOptions();
        
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('msgid', $options['json']);
        $this->assertEquals('123456789', $options['json']['msgid']);
    }

    public function testGetMsgId_returnsSetMsgId(): void
    {
        $this->assertEquals('123456789', $this->request->getMsgId());
    }

    public function testSetMsgId_setsMsgId(): void
    {
        $this->request->setMsgId('987654321');
        $this->assertEquals('987654321', $this->request->getMsgId());
    }
} 