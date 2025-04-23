<?php

namespace WechatWorkPushBundle\Request;

use HttpClientBundle\Request\ApiRequest;
use WechatWorkBundle\Request\AgentAware;
use WechatWorkPushBundle\Model\AppMessage;

/**
 * 发送应用消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/90236
 */
class SendMessageRequest extends ApiRequest
{
    use AgentAware;

    private AppMessage $message;

    public function getRequestPath(): string
    {
        return '/cgi-bin/message/send';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'agentid' => $this->getAgent()->getAgentId(),
            ...$this->getMessage()->toRequestArray(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getMessage(): AppMessage
    {
        return $this->message;
    }

    public function setMessage(AppMessage $message): void
    {
        $this->message = $message;
    }
}
