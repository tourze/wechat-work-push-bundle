<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * 模板卡片消息基类
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458
 */
#[ORM\MappedSuperclass]
abstract class TemplateCardMessage implements AppMessage, AdminArrayInterface
{
    use TimestampableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    /**
     * @var string 标题，不超过128个字节，超过会自动截断
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 128, options: ['comment' => '标题'])]
    protected string $title;

    /**
     * @var string 描述，不超过512个字节，超过会自动截断
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 512, options: ['comment' => '描述'])]
    protected string $description;

    /**
     * @var string|null 任务id，同一个应用发送的任务id不能重复，只能由数字、字母和"_-@"组成，最长128字节
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '任务id'])]
    protected ?string $taskId = null;

    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '创建人'])]
    private ?string $createdBy = null;

    #[UpdatedByColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '更新人'])]
    private ?string $updatedBy = null;

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMsgType(): string
    {
        return 'template_card';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTaskId(): ?string
    {
        return $this->taskId;
    }

    public function setTaskId(?string $taskId): static
    {
        $this->taskId = $taskId;

        return $this;
    }

    public function setCreatedBy(?string $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function setUpdatedBy(?string $updatedBy): self
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

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
    }public function toRequestArray(): array
    {
        $card = [
            'card_type' => $this->getCardType(),
            'source' => [
                'icon_url' => $this->getAgent()->getSquareLogoUrl(),
                'desc' => $this->getAgent()->getName(),
            ],
            'main_title' => [
                'title' => $this->getTitle(),
            ],
        ];

        if ($this->getTaskId()) {
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
     * 获取模板卡片���型
     */
    abstract protected function getCardType(): string;
}
