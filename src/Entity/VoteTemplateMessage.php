<?php

namespace WechatWorkPushBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use WechatWorkPushBundle\Repository\VoteTemplateMessageRepository;

/**
 * 投票选择型模板卡片消息
 *
 * @see https://developer.work.weixin.qq.com/document/path/96458#投票选择型
 */
#[ORM\Entity(repositoryClass: VoteTemplateMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_push_vote_template_message', options: ['comment' => '投票选择型模板卡片消息'])]
class VoteTemplateMessage extends TemplateCardMessage
{
    /**
     * @var string 选择题key值，用户提交选项后，会产生回调事件，回调事件会带上该key值表示该题，最长支持128字节
     */
    #[Assert\NotBlank]
    #[Assert\Length(max: 128)]
    #[ORM\Column(length: 128, options: ['comment' => '选择题key'])]
    private string $questionKey;

    /**
     * @var array<int, array<string, string>> 选项列表，最多4个选项，json格式，如：[{"id": "1", "text": "选项1"}, {"id": "2", "text": "选项2"}]
     */
    #[Assert\NotBlank]
    #[Assert\Count(min: 1, max: 4)]
    #[ORM\Column(type: Types::JSON, options: ['comment' => '选项列表'])]
    private array $options;

    public function getQuestionKey(): string
    {
        return $this->questionKey;
    }

    public function setQuestionKey(string $questionKey): void
    {
        $this->questionKey = $questionKey;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array<int, array<string, string>> $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * @return array<string, mixed>
     */
    public function toRequestArray(): array
    {
        $parentArray = parent::toRequestArray();
        assert(isset($parentArray['template_card']) && is_array($parentArray['template_card']));
        $card = $parentArray['template_card'];

        $card['checkbox'] = [
            'question_key' => $this->getQuestionKey(),
            'mode' => 1,
            'option_list' => array_map(function ($option) {
                return [
                    'id' => $option['id'],
                    'text' => $option['text'],
                ];
            }, $this->getOptions()),
        ];

        $card['submit_button'] = [
            'text' => '提交',
            'key' => $this->getQuestionKey(),
        ];

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
            'questionKey' => $this->getQuestionKey(),
            'options' => $this->getOptions(),
        ];
    }

    protected function getCardType(): string
    {
        return 'vote_interaction';
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
