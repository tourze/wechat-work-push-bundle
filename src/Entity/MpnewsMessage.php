<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\MpnewsMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#图文消息（mpnews）
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: MpnewsMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_mpnews_message', options: ['comment' => '图文消息（mpnews）'])]
class MpnewsMessage implements AppMessage, AdminArrayInterface, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    #[Assert\Length(max: 128)]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '标题'])]
    private ?string $title = null;

    #[Assert\Length(max: 65535)]
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '内容'])]
    private ?string $content = null;

    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '图文消息缩略图的url'])]
    private ?string $thumbMediaUrl = null;

    #[Assert\Length(max: 100)]
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '图文消息缩略图的media_id'])]
    private ?string $thumbMediaId = null;

    #[Assert\Length(max: 512)]
    #[ORM\Column(length: 512, nullable: true, options: ['comment' => '描述'])]
    private ?string $digest = null;

    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '点击"阅读原文"之后的页面链接'])]
    private ?string $contentSourceUrl = null;

    public function getMsgType(): string
    {
        return 'mpnews';
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getThumbMediaUrl(): ?string
    {
        return $this->thumbMediaUrl;
    }

    public function setThumbMediaUrl(?string $thumbMediaUrl): void
    {
        $this->thumbMediaUrl = $thumbMediaUrl;
    }

    public function getThumbMediaId(): ?string
    {
        return $this->thumbMediaId;
    }

    public function setThumbMediaId(?string $thumbMediaId): void
    {
        $this->thumbMediaId = $thumbMediaId;
    }

    public function getDigest(): ?string
    {
        return $this->digest;
    }

    public function setDigest(?string $digest): void
    {
        $this->digest = $digest;
    }

    public function getContentSourceUrl(): ?string
    {
        return $this->contentSourceUrl;
    }

    public function setContentSourceUrl(?string $contentSourceUrl): void
    {
        $this->contentSourceUrl = $contentSourceUrl;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $articles = [
            'title' => $this->getTitle() ?? '',
            'content' => $this->getContent() ?? '',
            'thumb_media_id' => $this->getThumbMediaId() ?? '',
        ];
        if (null !== $this->getDigest()) {
            $articles['digest'] = $this->getDigest();
        }
        if (null !== $this->getContentSourceUrl()) {
            $articles['content_source_url'] = $this->getContentSourceUrl();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'mpnews' => [
                'articles' => [$articles],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle() ?? '',
            'content' => $this->getContent() ?? '',
            'thumbMediaUrl' => $this->getThumbMediaUrl() ?? '',
            'digest' => $this->getDigest(),
            'contentSourceUrl' => $this->getContentSourceUrl(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            ...$this->getAgentArray(),
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
