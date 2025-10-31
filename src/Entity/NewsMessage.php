<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\NewsMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#图文消息
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: NewsMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_news_message', options: ['comment' => '图文消息'])]
class NewsMessage implements AppMessage, AdminArrayInterface, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use SafeTrait;
    use DuplicateCheckTrait;

    /**
     * @var string 标题，不超过128个字节，超过会自动截断（支持id转译）
     */
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '标题'])]
    #[Assert\Length(max: 128)]
    private string $title;

    /**
     * @var ?string 描述，不超过512个字节，超过会自动截断（支持id转译）
     */
    #[ORM\Column(length: 512, nullable: true, options: ['comment' => '描述'])]
    #[Assert\Length(max: 512)]
    private ?string $description = null;

    /**
     * @var ?string 点击后跳转的链接。 最长2048字节，请确保包含了协议头(http/https)，小程序或者url必须填写一个
     */
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '点击后跳转的链接'])]
    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    private ?string $url = null;

    /**
     * @var ?string 图文消息的图片链接，最长2048字节，支持JPG、PNG格式，较好的效果为大图 1068*455，小图150*150
     */
    #[ORM\Column(length: 2048, nullable: true, options: ['comment' => '图文消息的图片链接'])]
    #[Assert\Url]
    #[Assert\Length(max: 2048)]
    private ?string $picUrl = null;

    /**
     * @var ?string 小程序appid，必须是与当前应用关联的小程序，appid和pagepath必须同时填写，填写后会忽略url字段
     */
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '小程序appid'])]
    #[Assert\Length(max: 64)]
    private ?string $appId = null;

    /**
     * @var ?string 点击消息卡片后的小程序页面，最长128字节，仅限本小程序内的页面。appid和pagepath必须同时填写，填写后会忽略url字段
     */
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '点击消息卡片后的小程序页面'])]
    #[Assert\Length(max: 128)]
    private ?string $pagePath = null;

    public function getMsgType(): string
    {
        return 'news';
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getPicUrl(): ?string
    {
        return $this->picUrl;
    }

    public function setPicUrl(?string $picUrl): void
    {
        $this->picUrl = $picUrl;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(?string $appId): void
    {
        $this->appId = $appId;
    }

    public function getPagePath(): ?string
    {
        return $this->pagePath;
    }

    public function setPagePath(?string $pagePath): void
    {
        $this->pagePath = $pagePath;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $articles = [
            'title' => $this->getTitle(),
        ];
        if (null !== $this->getDescription()) {
            $articles['description'] = $this->getDescription();
        }
        if (null !== $this->getUrl()) {
            $articles['url'] = $this->getUrl();
        }
        if (null !== $this->getPicUrl()) {
            $articles['picurl'] = $this->getPicUrl();
        }
        if (null !== $this->getAppId()) {
            $articles['appid'] = $this->getAppId();
        }
        if (null !== $this->getPagePath()) {
            $articles['pagepath'] = $this->getPagePath();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'news' => [
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
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'url' => $this->getUrl(),
            'picUrl' => $this->getPicUrl(),
            'appId' => $this->getAppId(),
            'pagePath' => $this->getPagePath(),
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
