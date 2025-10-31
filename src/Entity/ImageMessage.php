<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\ImageMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\IdTransTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#图片消息
 */
#[ORM\Entity(repositoryClass: ImageMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_image_message', options: ['comment' => '图片消息'])]
class ImageMessage implements AppMessage, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use SafeTrait;
    use IdTransTrait;
    use DuplicateCheckTrait;

    /**
     * @var string 图片媒体文件id，可以调用上传临时素材接口获取
     */
    #[ORM\Column(length: 100, options: ['comment' => '图片媒体文件id'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private string $mediaId;

    public function getMsgType(): string
    {
        return 'image';
    }

    public function getMediaId(): string
    {
        return $this->mediaId;
    }

    public function setMediaId(string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getIdTransArray(),
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
