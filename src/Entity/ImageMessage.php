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
use WechatWorkPushBundle\Repository\ImageMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#图片消息
 */
#[ORM\Entity(repositoryClass: ImageMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_image_message', options: ['comment' => '图片消息'])]
class ImageMessage implements
    AppMessage,
    \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;


    #[CreateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '创建时IP'])]
    private ?string $createdFromIp = null;

    #[UpdateIpColumn]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '更新时IP'])]
    private ?string $updatedFromIp = null;

    /**
     * @var string 图片媒体文件id，可以调用上传临时素材接口获取
     */
    #[ORM\Column(length: 100, options: ['comment' => '图片媒体文件id'])]
    private string $mediaId;



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
        return 'image';
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    public function setMediaId(string $mediaId): static
    {
        $this->mediaId = $mediaId;

        return $this;
    }
    public function toRequestArray(): array
    {
        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'image' => [
                'media_id' => $this->getMediaId(),
            ],
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
