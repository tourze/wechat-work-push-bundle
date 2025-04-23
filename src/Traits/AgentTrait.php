<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use WechatWorkBundle\Entity\Agent;

trait AgentTrait
{
    /**
     * @var Agent 关联的应用
     */
    #[ListColumn(title: '应用')]
    #[FormField(title: '应用')]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Agent $agent;

    /**
     * @var string|null 消息ID
     */
    #[ORM\Column(length: 120, nullable: true)]
    private ?string $msgId = null;

    /**
     * @var array|null 成员ID列表（多个接收者用‘|’分隔，最多支持1000个）。
     *                 特殊情况：指定为"@all"，则向该企业应用的全部成员发送
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的成员'])]
    private ?array $toUser = null;

    /**
     * @var array|null 部门ID列表，多个接收者用‘|’分隔，最多支持100个。
     *                 当touser为"@all"时忽略本参数
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的部门'])]
    private ?array $toParty = null;

    /**
     * @var array|null 标签ID列表，多个接收者用‘|’分隔，最多支持100个。
     *                 当touser为"@all"时忽略本参数
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的标签'])]
    private ?array $toTag = null;

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): static
    {
        $this->msgId = $msgId;

        return $this;
    }

    public function getToUser(): ?array
    {
        return $this->toUser;
    }

    public function setToUser(?array $toUser): static
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function getToParty(): ?array
    {
        return $this->toParty;
    }

    public function setToParty(?array $toParty): void
    {
        $this->toParty = $toParty;
    }

    public function getToTag(): ?array
    {
        return $this->toTag;
    }

    public function setToTag(?array $toTag): void
    {
        $this->toTag = $toTag;
    }

    public function getAgentArray(): array
    {
        $result = [
            'agentid' => $this->getAgent()->getAgentId(),
        ];

        if (null !== $this->getToUser()) {
            $result['touser'] = implode('|', $this->getToUser());
        }
        if ($this->getToUser() !== ['@all']) {
            if (null !== $this->getToParty()) {
                $result['toparty'] = implode('|', $this->getToParty());
            }
            if (null !== $this->getToTag()) {
                $result['totag'] = implode('|', $this->getToTag());
            }
        }

        return $result;
    }
}
