<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DuplicateCheckTrait
{
    #[ORM\Column(nullable: true, options: ['comment' => '是否开启重复消息检查'])]
    private ?bool $enableDuplicateCheck = false;

    #[ORM\Column(nullable: true, options: ['comment' => '重复消息检查的时间间隔'])]
    private ?int $duplicateCheckInterval = 1800;

    public function isEnableDuplicateCheck(): ?bool
    {
        return $this->enableDuplicateCheck;
    }

    public function setEnableDuplicateCheck(?bool $enableDuplicateCheck): void
    {
        $this->enableDuplicateCheck = $enableDuplicateCheck;
    }

    public function getEnableDuplicateCheck(): ?bool
    {
        return $this->enableDuplicateCheck;
    }

    public function getDuplicateCheckInterval(): ?int
    {
        return $this->duplicateCheckInterval;
    }

    public function setDuplicateCheckInterval(?int $duplicateCheckInterval): void
    {
        $this->duplicateCheckInterval = $duplicateCheckInterval;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDuplicateCheckArray(): array
    {
        return [
            'enable_duplicate_check' => true === $this->isEnableDuplicateCheck() ? 1 : 0,
            'duplicate_check_interval' => $this->getDuplicateCheckInterval(),
        ];
    }
}
