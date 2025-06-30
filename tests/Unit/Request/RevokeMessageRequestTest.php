<?php

namespace WechatWorkPushBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

class RevokeMessageRequestTest extends TestCase
{
    private RevokeMessageRequest $request;

    protected function setUp(): void
    {
        $this->request = new RevokeMessageRequest();
    }

    public function test_getRequestPath_returnsCorrectPath(): void
    {
        $this->assertEquals('/cgi-bin/message/recall', $this->request->getRequestPath());
    }

    public function test_setMsgId_andGetMsgId(): void
    {
        $msgId = 'msg_123456789';
        
        $this->request->setMsgId($msgId);
        
        $this->assertEquals($msgId, $this->request->getMsgId());
    }

    public function test_getRequestOptions_withValidMsgId(): void
    {
        $msgId = 'msg_987654321';
        $this->request->setMsgId($msgId);

        $options = $this->request->getRequestOptions();

        $expectedJson = [
            'msgid' => $msgId
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }

    public function test_getRequestOptions_withDifferentMsgId(): void
    {
        $msgId = 'test_message_id_123';
        $this->request->setMsgId($msgId);

        $options = $this->request->getRequestOptions();

        $this->assertEquals(['json' => ['msgid' => $msgId]], $options);
    }
}