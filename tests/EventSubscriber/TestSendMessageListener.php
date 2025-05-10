<?php

namespace WechatWorkPushBundle\Tests\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use WechatWorkBundle\Service\WorkService;
use WechatWorkMediaBundle\Entity\TempMedia;
use WechatWorkMediaBundle\Enum\MediaType;
use WechatWorkPushBundle\Entity\MpnewsMessage;
use WechatWorkPushBundle\Model\AppMessage;
use WechatWorkPushBundle\Request\SendMessageRequest;

/**
 * 测试专用的监听器类，不依赖 PostPersistEventArgs
 */
class TestSendMessageListener
{
    public function __construct(
        private readonly WorkService $workService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * 测试专用的 postPersist 方法
     */
    public function testPostPersist(AppMessage $object): void
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
        }
    }
} 