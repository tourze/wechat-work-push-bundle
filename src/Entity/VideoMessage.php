<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\VideoMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#视频消息
 */
#[ORM\Entity(repositoryClass: VideoMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_video_message', options: ['comment' => '视频消息'])]
class VideoMessage implements
    AppMessage,
    \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    /**
     * @var string 图片媒体文件id，可以调用上传临时素材接口获取
     */
    #[ORM\Column(length: 100, options: ['comment' => '视频媒体文件id'])]
    private string $mediaId;

    /**
     * @var string|null 视频消息的标题，不超过128个字节，超过会自动截断
     */
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '视频消息的标题'])]
    private ?string $title = null;

    /**
     * @var string|null 视频消息的描述，不超过512个字节，超过会自动截断
     */
    #[ORM\Column(length: 512, nullable: true, options: ['comment' => '视频消息的描述'])]
    private ?string $description = null;

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
        return 'video';
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
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
    }

    public function toRequestArray(): array
    {
        $video = [
            'media_id' => $this->getMediaId(),
        ];
        if (null !== $this->getTitle()) {
            $video['title'] = $this->getTitle();
        }
        if (null !== $this->getDescription()) {
            $video['description'] = $this->getDescription();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'video' => $video,
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
