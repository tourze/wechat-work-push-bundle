<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SafeTrait
{
    #[ORM\Column(nullable: true, options: ['comment' => '是否是保密消息'])]
    private ?bool $safe = false;

    public function isSafe(): ?bool
    {
        return $this->safe;
    }

    public function setSafe(?bool $safe): void
    {
        $this->safe = $safe;
    }

    public function getSafe(): ?bool
    {
        return $this->safe;
    }

    /**
     * @return array<string, mixed>
     */
    public function getSafeArray(): array
    {
        return [
            'safe' => true === $this->safe ? 1 : 0,
        ];
    }
}
