<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait SafeTrait
{
    /**
     * @var bool|null 0表示可对外分享，1表示不能分享且内容显示水印
     */
    #[ORM\Column(nullable: true, options: ['comment' => '是否是保密消息'])]
    private ?bool $safe = false;

    public function isSafe(): ?bool
    {
        return $this->safe;
    }

    public function setSafe(?bool $safe): static
    {
        $this->safe = $safe;

        return $this;
    }

    public function getSafeArray(): array
    {
        return [
            'safe' => $this->safe ? 1 : 0,
        ];
    }
}
