<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\FileMessage;

/**
 * 文件消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class FileMessageFixtures extends AbstractMessageFixtures
{
    public const FILE_MESSAGE_DOCUMENT = 'file-message-document';
    public const FILE_MESSAGE_REPORT = 'file-message-report';
    public const FILE_MESSAGE_MANUAL = 'file-message-manual';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建文件消息测试数据
        $fileMessage = new FileMessage();
        $fileMessage->setMediaId('test_file_media_' . uniqid());
        $fileMessage->setAgent($agent);

        $manager->persist($fileMessage);
        $manager->flush();

        $this->addReference(self::FILE_MESSAGE_DOCUMENT, $fileMessage);
    }
}
