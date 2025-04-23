<?php

namespace WechatWorkPushBundle\Model;

use WechatWorkBundle\Entity\Agent;

/**
 * 基础的应用消息实体类
 */
interface AppMessage
{
    public function getMsgType(): string;

    public function getMsgId(): ?string;

    public function setMsgId(?string $msgId): static;

    public function getAgent(): ?Agent;

    public function toRequestArray(): array;
}
