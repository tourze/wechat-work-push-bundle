<?php

namespace WechatWorkPushBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

/**
 * @internal
 */
#[CoversClass(RevokeMessageRequest::class)]
final class RevokeMessageRequestTest extends RequestTestCase
{
    private function getRequest(): RevokeMessageRequest
    {
        return new RevokeMessageRequest();
    }

    public function testGetRequestPathReturnsCorrectPath(): void
    {
        $request = $this->getRequest();
        $this->assertEquals('cgi-bin/message/recall', $request->getRequestPath());
    }

    public function testSetMsgIdAndGetMsgId(): void
    {
        $msgId = 'msg_123456789';
        $request = $this->getRequest();

        $request->setMsgId($msgId);

        $this->assertEquals($msgId, $request->getMsgId());
    }

    public function testGetRequestOptionsWithValidMsgId(): void
    {
        $msgId = 'msg_987654321';
        $request = $this->getRequest();
        $request->setMsgId($msgId);

        $options = $request->getRequestOptions();

        $expectedJson = [
            'msgid' => $msgId,
        ];

        $this->assertEquals(['json' => $expectedJson], $options);
    }

    public function testGetRequestOptionsWithDifferentMsgId(): void
    {
        $msgId = 'test_message_id_123';
        $request = $this->getRequest();
        $request->setMsgId($msgId);

        $options = $request->getRequestOptions();

        $this->assertEquals(['json' => ['msgid' => $msgId]], $options);
    }
}
