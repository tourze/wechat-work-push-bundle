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
use WechatWorkPushBundle\Repository\TextMessageRepository;
use WechatWorkPushBundle\Traits\AgentTrait;
use WechatWorkPushBundle\Traits\DuplicateCheckTrait;
use WechatWorkPushBundle\Traits\IdTransTrait;
use WechatWorkPushBundle\Traits\SafeTrait;

/**
 * @see https://developer.work.weixin.qq.com/document/path/96458#文本消息
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: TextMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_text_message', options: ['comment' => '文本消息'])]
class TextMessage implements AppMessage, AdminArrayInterface, \Stringable
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
     * @var string 最长不超过2048个字节，超过将截断（支持id转译）
     */
    #[Assert\NotBlank(message: '消息内容不能为空')]
    #[Assert\Length(max: 2048, maxMessage: '消息内容长度不能超过{{ limit }}个字符')]
    #[ORM\Column(length: 2048, options: ['comment' => '消息内容'])]
    private string $content;

    public function getMsgType(): string
    {
        return 'text';
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
            ...$this->getSafeArray(),
            ...$this->getIdTransArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'text' => [
                'content' => $this->getContent(),
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
            'content' => $this->getContent(),
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
