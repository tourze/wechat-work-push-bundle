<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WechatWorkPushBundle\Repository\TextNoticeTemplateMessageRepository;

/**
 * 文本通知型模板卡片消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#文本通知型
 */
#[ORM\Entity(repositoryClass: TextNoticeTemplateMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_text_notice_template_message', options: ['comment' => '文本通知型模板卡片消息'])]
class TextNoticeTemplateMessage extends TemplateCardMessage
{
    /**
     * @var string 点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
     */
    #[ORM\Column(length: 2048, options: ['comment' => '跳转链接'])]
    private string $url;

    /**
     * @var string|null 底部按钮文字，默认为"详情"
     */
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '底部按钮文字'])]
    private ?string $btnText = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getBtnText(): ?string
    {
        return $this->btnText;
    }

    public function setBtnText(?string $btnText): static
    {
        $this->btnText = $btnText;

        return $this;
    }

    public function toRequestArray(): array
    {
        $card = parent::toRequestArray()['template_card'];

        $card['horizontal_content_list'] = [
            [
                'keyname' => '通知内容',
                'value' => $this->getDescription(),
            ],
        ];

        $card['jump_list'] = [
            [
                'type' => 1,
                'url' => $this->getUrl(),
            ],
        ];

        $card['card_action'] = [
            'type' => 1,
            'url' => $this->getUrl(),
        ];

        if (null !== $this->getBtnText()) {
            $card['card_action']['text'] = $this->getBtnText();
        }

        return [
            ...$this->getAgentArray(),
            ...$this->getSafeArray(),
            ...$this->getDuplicateCheckArray(),
            'msgtype' => $this->getMsgType(),
            'template_card' => $card,
        ];
    }

    public function retrieveAdminArray(): array
    {
        return [
            ...parent::retrieveAdminArray(),
            'url' => $this->getUrl(),
            'btnText' => $this->getBtnText(),
        ];
    }

    protected function getCardType(): string
    {
        return 'text_notice';
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
