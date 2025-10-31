<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Repository\MarkdownMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\IdTransTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#markdown消息
 */
#[ORM\Entity(repositoryClass: MarkdownMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_markdown_message', options: ['comment' => 'markdown消息'])]
class MarkdownMessage implements AppMessage, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;
    use AgentTrait;
    use DuplicateCheckTrait;
    use IdTransTrait;

    /**
     * @var string markdown内容，最长不超过2048个字节，必须是utf8编码
     */
    #[ORM\Column(length: 2048, options: ['comment' => 'markdown内容'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 2048)]
    private string $content;

    public function getMsgType(): string
    {
        return 'markdown';
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        return [
            ...$this->getAgentArray(),
            ...$this->getIdTransArray(),
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
