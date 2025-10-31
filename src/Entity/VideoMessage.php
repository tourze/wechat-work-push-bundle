<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\VideoMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#视频消息
 */
#[ORM\Entity(repositoryClass: VideoMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_video_message', options: ['comment' => '视频消息'])]
class VideoMessage implements AppMessage, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    /**
     * @var string 图片媒体文件id，可以调用上传临时素材接口获取
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    #[ORM\Column(length: 100, options: ['comment' => '视频媒体文件id'])]
    private string $mediaId;

    /**
     * @var string|null 视频消息的标题，不超过128个字节，超过会自动截断
     */
    #[Assert\Length(max: 128)]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '视频消息的标题'])]
    private ?string $title = null;

    /**
     * @var string|null 视频消息的描述，不超过512个字节，超过会自动截断
     */
    #[Assert\Length(max: 512)]
    #[ORM\Column(length: 512, nullable: true, options: ['comment' => '视频消息的描述'])]
    private ?string $description = null;

    public function getMsgType(): string
    {
        return 'video';
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    public function setMediaId(string $mediaId): void
    {
        $this->mediaId = $mediaId;
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

    /**
     * @return array<string, mixed>
     */
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
