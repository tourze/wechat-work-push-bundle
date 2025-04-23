<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DuplicateCheckTrait
{
    /**
     * @var bool|null 0表示否，1表示是，默认0
     */
    #[ORM\Column(nullable: true, options: ['comment' => '是否开启重复消息检查'])]
    private ?bool $enableDuplicateCheck = false;

    /**
     * @var int|null 表示是否重复消息检查的时间间隔，默认1800s，最大不超过4小时
     */
    #[ORM\Column(nullable: true, options: ['comment' => '重复消息检查的时间间隔'])]
    private ?int $duplicateCheckInterval = 1800;

    public function isEnableDuplicateCheck(): ?bool
    {
        return $this->enableDuplicateCheck;
    }

    public function setEnableDuplicateCheck(?bool $enableDuplicateCheck): static
    {
        $this->enableDuplicateCheck = $enableDuplicateCheck;

        return $this;
    }

    public function getDuplicateCheckInterval(): ?int
    {
        return $this->duplicateCheckInterval;
    }

    public function setDuplicateCheckInterval(?int $duplicateCheckInterval): static
    {
        $this->duplicateCheckInterval = $duplicateCheckInterval;

        return $this;
    }

    public function getDuplicateCheckArray(): array
    {
        return [
            'enable_duplicate_check' => $this->isEnableDuplicateCheck() ? 1 : 0,
            'duplicate_check_interval' => $this->getDuplicateCheckInterval(),
        ];
    }
}
