<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use WechatWorkPushBundle\Repository\ButtonTemplateMessageRepository;

/**
 * 按钮交互型模板卡片消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#按钮交互型
 */
#[Deletable]
#[Creatable]
#[ORM\Entity(repositoryClass: ButtonTemplateMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_button_template_message', options: ['comment' => '按钮交互型模板卡片消息'])]
class ButtonTemplateMessage extends TemplateCardMessage
{
    /**
     * @var string 点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 2048, options: ['comment' => '跳转链接'])]
    private string $url;

    /**
     * @var string 按钮文案，建议不超过10个字
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 32, options: ['comment' => '按钮文案'])]
    private string $buttonText;

    /**
     * @var string|null 点击按钮后返回给企业微信的回调key，最长128字节
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '按钮key'])]
    private ?string $buttonKey = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getButtonText(): string
    {
        return $this->buttonText;
    }

    public function setButtonText(string $buttonText): static
    {
        $this->buttonText = $buttonText;

        return $this;
    }

    public function getButtonKey(): ?string
    {
        return $this->buttonKey;
    }

    public function setButtonKey(?string $buttonKey): static
    {
        $this->buttonKey = $buttonKey;

        return $this;
    }

    public function toRequestArray(): array
    {
        $card = parent::toRequestArray()['template_card'];

        $card['horizontal_content_list'] = [
            [
                'keyname' => '详细内容',
                'value' => $this->getDescription(),
            ],
        ];

        $card['card_action'] = [
            'type' => 1,
            'url' => $this->getUrl(),
        ];

        $card['button_list'] = [
            [
                'text' => $this->getButtonText(),
                'style' => 1,
                'type' => 1,
                'url' => $this->getUrl(),
            ],
        ];

        if ($this->getButtonKey()) {
            $card['button_list'][0]['key'] = $this->getButtonKey();
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
            'buttonText' => $this->getButtonText(),
            'buttonKey' => $this->getButtonKey(),
        ];
    }

    protected function getCardType(): string
    {
        return 'button_interaction';
    }
}
