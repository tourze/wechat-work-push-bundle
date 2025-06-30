<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\MiniProgramNoticeMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\IdTransTrait;

/**
 * 小程序通知消息只允许绑定了小程序的应用发送，之前，消息会通过统一的会话【小程序通知】发送给用户。
 * 从2019年6月28日起，用户收到的小程序通知会出现在各个独立的应用中。
 * 不支持@all全员发送
 * 微工作台（原企业号）不支持展示小程序通知消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#小程序通知消息
 */
#[ORM\Entity(repositoryClass: MiniProgramNoticeMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_mini_program_notice_message', options: ['comment' => '小程序通知消息'])]
class MiniProgramNoticeMessage implements
    AppMessage,
    \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use AgentTrait;
    use IdTransTrait;
    use DuplicateCheckTrait;


    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    /**
     * @var string 必须是与当前应用关联的小程序
     */
    #[ORM\Column(length: 64, options: ['comment' => '小程序appid'])]
    private string $appId;

    /**
     * @var string|null 仅限本小程序内的页面。该字段不填则消息点击后不跳转。
     */
    #[ORM\Column(length: 1024, nullable: true, options: ['comment' => '点击消息卡片后的小程序页面'])]
    private ?string $page = null;

    #[ORM\Column(length: 12, options: ['comment' => '消息标题'])]
    private string $title;

    #[ORM\Column(length: 12, nullable: true, options: ['comment' => '消息描述'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true, options: ['comment' => '是否放大第一个content_item'])]
    private ?bool $emphasisFirstItem = null;

    #[ORM\Column(nullable: true, options: ['comment' => '消息内容键值对，最多允许10个item'])]
    private ?array $contentItem = null;


    public function setCreatedFromIp(?string $createdFromIp): self
    {
        $this->createdFromIp = $createdFromIp;

        return $this;
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setUpdatedFromIp(?string $updatedFromIp): self
    {
        $this->updatedFromIp = $updatedFromIp;

        return $this;
    }

    public function getUpdatedFromIp(): ?string
    {
        return $this->updatedFromIp;
    }

    public function getMsgType(): string
    {
        return 'miniprogram_notice';
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): static
    {
        $this->appId = $appId;

        return $this;
    }

    public function getPage(): ?string
    {
        return $this->page;
    }

    public function setPage(?string $page): void
    {
        $this->page = $page;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function isEmphasisFirstItem(): ?bool
    {
        return $this->emphasisFirstItem;
    }

    public function setEmphasisFirstItem(?bool $emphasisFirstItem): void
    {
        $this->emphasisFirstItem = $emphasisFirstItem;
    }

    public function getContentItem(): ?array
    {
        return $this->contentItem;
    }

    public function setContentItem(?array $contentItem): void
    {
        $this->contentItem = $contentItem;
    }

    public function toRequestArray(): array
    {
        $notice = [
            'appid' => $this->getAppId(),
            'title' => $this->getTitle(),
        ];
        if (null !== $this->getPage()) {
            $notice['page'] = $this->getPage();
        }
        if (null !== $this->getDescription()) {
            $notice['description'] = $this->getDescription();
        }
        if (null !== $this->isEmphasisFirstItem()) {
            $notice['emphasis_first_item'] = $this->isEmphasisFirstItem();
        }
        if (null !== $this->getContentItem()) {
            $notice['content_item'] = $this->getContentItem();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getIdTransArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'miniprogram_notice' => $notice,
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
