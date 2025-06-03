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

    public function setMsgId(?string $msgId): static;

    public function getAgent(): ?AgentInterface;

    public function toRequestArray(): array;
}
