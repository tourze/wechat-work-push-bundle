<?php

namespace WechatWorkPushBundle\Entity;

use AntdCpBundle\Builder\Field\BraftEditor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIpBundle\Attribute\CreateIpColumn;
use Tourze\DoctrineIpBundle\Attribute\UpdateIpColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Attribute\CreatedByColumn;
use Tourze\DoctrineUserBundle\Attribute\UpdatedByColumn;
use Tourze\EasyAdmin\Attribute\Column\PictureColumn;
use Tourze\EasyAdmin\Attribute\Field\ImagePickerField;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\MpnewsMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#图文消息（mpnews）
 */
#[ORM\Entity(repositoryClass: MpnewsMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_mpnews_message', options: ['comment' => '图文消息（mpnews）'])]
class MpnewsMessage implements AppMessage, AdminArrayInterface
, \Stringable{
    use TimestampableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

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

    /**
     * @var string 标题，不超过128个字节，超过会自动截断（支持id转译）
     */
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '标题'])]
    private string $title;

    /**
     * @BraftEditor()
     *
     * @var string 图文消息的内容，支持html标签，不超过666 K个字节（支持id转译）
     */
    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '内容'])]
    private string $content;

    #[ImagePickerField]
    #[PictureColumn]
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '图文消息缩略图的url'])]
    private string $thumbMediaUrl;

    /**
     * @var string 图文消息缩略图的media_id, 可以通过素材管理接口获得。此处thumb_media_id即上传接口返回的media_id
     */
    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '图文消息缩略图的media_id'])]
    private string $thumbMediaId;

    /**
     * @var ?string 图文消息的描述，不超过512个字节，超过会自动截断（支持id转译）
     */
    #[ORM\Column(length: 512, nullable: true, options: ['comment' => '描述'])]
    private ?string $digest = null;

    /**
     * @var ?string 图文消息点击“阅读原文”之后的页面链接
     */
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '点击“阅读原文”之后的页面链接'])]
    private ?string $contentSourceUrl = null;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getMsgType(): string
    {
        return 'mpnews';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getThumbMediaUrl(): string
    {
        return $this->thumbMediaUrl;
    }

    public function setThumbMediaUrl(string $thumbMediaUrl): void
    {
        $this->thumbMediaUrl = $thumbMediaUrl;
    }

    public function getThumbMediaId(): string
    {
        return $this->thumbMediaId;
    }

    public function setThumbMediaId(string $thumbMediaId): void
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
    }public function toRequestArray(): array
    {
        $articles = [
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'thumb_media_id' => $this->getThumbMediaId(),
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

    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'thumbMediaUrl' => $this->getThumbMediaUrl(),
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
