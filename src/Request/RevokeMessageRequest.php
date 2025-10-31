<?php

namespace WechatWorkPushBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;

/**
 * 撤回应用消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96460
 */
class RevokeMessageRequest extends ApiRequest
{
    use AgentAware;

    /**
     * @var string 消息ID。从应用发送消息接口处获得。
     */
    private string $msgId;

    public function getRequestPath(): string
    {
        return 'cgi-bin/message/recall';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'msgid' => $this->getMsgId(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getMsgId(): string
    {
        return $this->msgId;
    }

    public function setMsgId(string $msgId): void
    {
        $this->msgId = $msgId;
    }
}
