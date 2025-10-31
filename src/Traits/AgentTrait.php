<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\WechatWorkContracts\AgentInterface;

trait AgentTrait
{
    /**
     * @var AgentInterface
     */
    #[ORM\ManyToOne(targetEntity: AgentInterface::class)]
    #[ORM\JoinColumn(nullable: false)]
    private AgentInterface $agent;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '消息ID'])]
    #[Assert\Length(max: 120, maxMessage: '消息ID长度不能超过{{ limit }}个字符')]
    private ?string $msgId = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的成员'])]
    private ?array $toUser = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的部门'])]
    private ?array $toParty = null;

    /**
     * @var array<string>|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '指定接收消息的标签'])]
    private ?array $toTag = null;

    public function getAgent(): AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(AgentInterface $agent): void
    {
        $this->agent = $agent;
    }

    public function getMsgId(): ?string
    {
        return $this->msgId;
    }

    public function setMsgId(?string $msgId): void
    {
        $this->msgId = $msgId;
    }

    /**
     * @return array<string>|null
     */
    public function getToUser(): ?array
    {
        return $this->toUser;
    }

    /**
     * @param array<string>|null $toUser
     */
    public function setToUser(?array $toUser): void
    {
        $this->toUser = $toUser;
    }

    /**
     * @return array<string>|null
     */
    public function getToParty(): ?array
    {
        return $this->toParty;
    }

    /**
     * @param array<string>|null $toParty
     */
    public function setToParty(?array $toParty): void
    {
        $this->toParty = $toParty;
    }

    /**
     * @return array<string>|null
     */
    public function getToTag(): ?array
    {
        return $this->toTag;
    }

    /**
     * @param array<string>|null $toTag
     */
    public function setToTag(?array $toTag): void
    {
        $this->toTag = $toTag;
    }

    /**
     * @return array<string, mixed>
     */
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
