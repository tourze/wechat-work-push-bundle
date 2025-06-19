<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use WechatWorkPushBundle\Repository\NewsTemplateMessageRepository;

/**
 * 图文展示型模板卡片消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#图文展示型
 */
#[ORM\Entity(repositoryClass: NewsTemplateMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_news_template_message', options: ['comment' => '图文展示型模板卡片消息'])]
class NewsTemplateMessage extends TemplateCardMessage
{
    /**
     * @var string 点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
     */
    #[ORM\Column(length: 2048, options: ['comment' => '跳转链接'])]
    private string $url;

    /**
     * @var string 图片的url
     */
    #[ORM\Column(length: 2048, options: ['comment' => '图片链接'])]
    private string $imageUrl;

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

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

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

        $card['card_image'] = [
            'url' => $this->getImageUrl(),
            'aspect_ratio' => 1.3,
        ];

        $card['horizontal_content_list'] = [
            [
                'keyname' => '详细内容',
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

        if ($this->getBtnText()) {
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
            'imageUrl' => $this->getImageUrl(),
            'btnText' => $this->getBtnText(),
        ];
    }

    protected function getCardType(): string
    {
        return 'news_notice';
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
