<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * 模板卡片消息基类
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\MappedSuperclass]
abstract class TemplateCardMessage implements AppMessage, AdminArrayInterface
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    #[ORM\Column(length: 128, options: ['comment' => '标题，不超过128个字节，超过会自动截断'])]
    protected string $title;

    #[ORM\Column(length: 512, options: ['comment' => '描述，不超过512个字节，超过会自动截断'])]
    protected string $description;

    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '任务id，同一个应用发送的任务id不能重复，只能由数字、字母和"_-@"组成，最长128字节'])]
    protected ?string $taskId = null;

    public function getMsgType(): string
    {
        return 'template_card';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function setTaskId(?string $taskId): void
    {
        $this->taskId = $taskId;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $card = [
            'card_type' => $this->getCardType(),
            'source' => [
                'icon_url' => '', // TODO: 需要 AgentInterface 提供 getSquareLogoUrl() 方法
                'desc' => '', // TODO: 需要 AgentInterface 提供 getName() 方法
            ],
            'main_title' => [
                'title' => $this->getTitle(),
            ],
        ];

        if (null !== $this->getTaskId()) {
            $card['task_id'] = $this->getTaskId();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'template_card' => $card,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'taskId' => $this->getTaskId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            ...$this->getAgentArray(),
        ];
    }

    /**
     * 获取模板卡片类型
     */
    abstract protected function getCardType(): string;
}
