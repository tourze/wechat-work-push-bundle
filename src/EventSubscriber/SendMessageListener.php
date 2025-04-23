<?php

namespace WechatWorkPushBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use WechatWorkBundle\Service\WorkService;
use WechatWorkMediaBundle\Entity\TempMedia;
use WechatWorkMediaBundle\Enum\MediaType;
use WechatWorkPushBundle\Entity\ButtonTemplateMessage;
use WechatWorkPushBundle\Entity\FileMessage;
use WechatWorkPushBundle\Entity\ImageMessage;
use WechatWorkPushBundle\Entity\MarkdownMessage;
use WechatWorkPushBundle\Entity\MiniProgramNoticeMessage;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Entity\MultipleTemplateMessage;
use WechatWorkPushBundle\Entity\NewsMessage;
use WechatWorkPushBundle\Entity\NewsTemplateMessage;
use WechatWorkPushBundle\Entity\TextCardMessage;
use WechatWorkPushBundle\Entity\TextMessage;
use WechatWorkPushBundle\Entity\TextNoticeTemplateMessage;
use WechatWorkPushBundle\Entity\VideoMessage;
use WechatWorkPushBundle\Entity\VoiceMessage;
use WechatWorkPushBundle\Entity\VoteTemplateMessage;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: FileMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: ImageMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: MarkdownMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: MiniProgramNoticeMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: TextCardMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: TextMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: VideoMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: VoiceMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: NewsMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: MpnewsMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: TextNoticeTemplateMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: NewsTemplateMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: ButtonTemplateMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: VoteTemplateMessage::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: MultipleTemplateMessage::class)]
class SendMessageListener
{
    public function __construct(
        private readonly WorkService $workService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function postPersist(AppMessage $object, PostPersistEventArgs $eventArgs): void
    {
        if ($object instanceof MpnewsMessage) {
            $tempMedia = new TempMedia();
            $tempMedia->setType(MediaType::IMAGE);
            $tempMedia->setAgent($object->getAgent());
            $tempMedia->setFileUrl($object->getThumbMediaUrl());
            $this->entityManager->persist($tempMedia);
            $this->entityManager->flush();

            $object->setThumbMediaId($tempMedia->getMediaId());
        }

        $request = new SendMessageRequest();
        $request->setAgent($object->getAgent());
        $request->setMessage($object);
        $response = $this->workService->request($request);
        if (isset($response['msgid'])) {
            $object->setMsgId($response['msgid']);
            $eventArgs->getObjectManager()->persist($object);
            $eventArgs->getObjectManager()->flush();
        }
    }
}
