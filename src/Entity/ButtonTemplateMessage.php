<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use WechatWorkPushBundle\Repository\ButtonTemplateMessageRepository;

/**
 * 按钮交互型模板卡片消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#按钮交互型
 */
#[ORM\Entity(repositoryClass: ButtonTemplateMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_button_template_message', options: ['comment' => '按钮交互型模板卡片消息'])]
class ButtonTemplateMessage extends TemplateCardMessage
{
    /**
     * @var string 点击后跳转的链接。最长2048字节，请确保包含了协议头(http/https)
     */
    #[ORM\Column(length: 2048, options: ['comment' => '跳转链接'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 2048)]
    #[Assert\Url]
    private string $url;

    /**
     * @var string 按钮文案，建议不超过10个字
     */
    #[ORM\Column(length: 32, options: ['comment' => '按钮文案'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 32)]
    private string $buttonText;

    /**
     * @var string|null 点击按钮后返回给企业微信的回调key，最长128字节
     */
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => '按钮key'])]
    #[Assert\Length(max: 128)]
    private ?string $buttonKey = null;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getButtonText(): string
    {
        return $this->buttonText;
    }

    public function setButtonText(string $buttonText): void
    {
        $this->buttonText = $buttonText;
    }

    public function getButtonKey(): ?string
    {
        return $this->buttonKey;
    }

    public function setButtonKey(?string $buttonKey): void
    {
        $this->buttonKey = $buttonKey;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $parentArray = parent::toRequestArray();
        assert(isset($parentArray['template_card']) && is_array($parentArray['template_card']));
        $card = $parentArray['template_card'];

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

        if (null !== $this->getButtonKey()) {
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

    /**
     * @return array<string, mixed>
     */
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

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
