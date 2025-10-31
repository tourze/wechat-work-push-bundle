<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\TextCardMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\IdTransTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#文本卡片消息
 */
#[ORM\Entity(repositoryClass: TextCardMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_text_card_message', options: ['comment' => '文本卡片消息'])]
class TextCardMessage implements AppMessage, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use IdTransTrait;
    use DuplicateCheckTrait;

    #[ORM\Column(length: 128, options: ['comment' => '标题'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    private string $title;

    #[ORM\Column(length: 512, options: ['comment' => '描述'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 512)]
    private string $description;

    /**
     * @var string 点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
     */
    #[ORM\Column(length: 2048, options: ['comment' => '点击后跳转的链接'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 2048)]
    #[Assert\Url]
    private string $url;

    #[ORM\Column(length: 4, options: ['comment' => '按钮文字'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 4)]
    private string $btnText = '详情';

    public function getMsgType(): string
    {
        return 'textcard';
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getBtnText(): string
    {
        return $this->btnText;
    }

    public function setBtnText(string $btnText): void
    {
        $this->btnText = $btnText;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $textcard = [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'url' => $this->getUrl(),
        ];
        if ('' !== $this->getBtnText()) {
            $textcard['btntxt'] = $this->getBtnText();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'textcard' => $textcard,
        ];
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
