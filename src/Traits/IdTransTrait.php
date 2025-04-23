<?php

namespace WechatWorkPushBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait IdTransTrait
{
    /**
     * @var bool|null 表示是否开启id转译，0表示否，1表示是，默认0
     */
    #[ORM\Column(nullable: true, options: ['comment' => '表示是否开启id转译'])]
    private ?bool $enableIdTrans = false;

    public function isEnableIdTrans(): ?bool
    {
        return $this->enableIdTrans;
    }

    public function setEnableIdTrans(?bool $enableIdTrans): static
    {
        $this->enableIdTrans = $enableIdTrans;

        return $this;
    }

    public function getIdTransArray(): array
    {
        return [
            'enable_id_trans' => $this->isEnableIdTrans() ? 1 : 0,
        ];
    }
}
