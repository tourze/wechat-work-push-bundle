<?php

namespace WechatWorkPushBundle\Model;

use Tourze\WechatWorkContracts\AgentInterface;

/**
 * 基础的应用消息实体类
 */
interface AppMessage
{
    public function getMsgType(): string;

    public function getMsgId(): ?string;

    public function setMsgId(?string $msgId): void;

    public function getAgent(): ?AgentInterface;

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array;
}
