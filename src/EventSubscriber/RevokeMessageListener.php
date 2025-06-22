<?php

namespace WechatWorkPushBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use WechatWorkBundle\Service\WorkService;
use WechatWorkPushBundle\Entity\FileMessage;
use WechatWorkPushBundle\Entity\ImageMessage;
use WechatWorkPushBundle\Entity\MarkdownMessage;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Entity\NewsMessage;
use WechatWorkPushBundle\Entity\TextCardMessage;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Entity\VideoMessage;
use WechatWorkPushBundle\Entity\VoiceMessage;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Request\RevokeMessageRequest;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: FileMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: ImageMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: MarkdownMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: MiniProgramNoticeMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: TextCardMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: TextMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: VideoMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: VoiceMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: NewsMessage::class)]
#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: MpnewsMessage::class)]
class RevokeMessageListener
{
    public function __construct(
        private readonly WorkService $workService,
    ) {}

    public function postRemove(AppMessage $object): void
    {
        if (null === $object->getMsgId()) {
            return;
        }

        $request = new RevokeMessageRequest();
        $request->setMsgId($object->getMsgId());
        $request->setAgent($object->getAgent());
        $this->workService->asyncRequest($request);
    }
}
