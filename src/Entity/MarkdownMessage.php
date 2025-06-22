<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\MarkdownMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#markdown消息
 */
#[ORM\Entity(repositoryClass: MarkdownMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_markdown_message', options: ['comment' => 'markdown消息'])]
class MarkdownMessage implements
    AppMessage,
    \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use AgentTrait;
    use DuplicateCheckTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    /**
     * @var string markdown内容，最长不超过2048个字节，必须是utf8编码
     */
    #[ORM\Column(length: 2048, options: ['comment' => 'markdown内容'])]
    private string $content;

    public function getId(): ?string
    {
        return $this->id;
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
    }

    public function getMsgType(): string
    {
        return 'markdown';
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
    public function toRequestArray(): array
    {
        return [
            ...$this->getAgentArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'markdown' => [
                'content' => $this->getContent(),
            ],
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
