<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdTransTrait
{
    #[ORM\Column(nullable: true, options: ['comment' => '表示是否开启id转译'])]
    private ?bool $enableIdTrans = false;

    public function isEnableIdTrans(): ?bool
    {
        return $this->enableIdTrans;
    }

    public function setEnableIdTrans(?bool $enableIdTrans): void
    {
        $this->enableIdTrans = $enableIdTrans;
    }

    public function getEnableIdTrans(): ?bool
    {
        return $this->enableIdTrans;
    }

    /**
     * @return array<string, mixed>
     */
    public function getIdTransArray(): array
    {
        return [
            'enable_id_trans' => true === $this->isEnableIdTrans() ? 1 : 0,
        ];
    }
}
