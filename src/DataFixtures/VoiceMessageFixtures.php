<?php

namespace WechatWorkPushBundle\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatWorkPushBundle\Entity\VoiceMessage;

/**
 * 语音消息测试数据
 */
#[When(env: 'test')]
#[When(env: 'dev')]
class VoiceMessageFixtures extends AbstractMessageFixtures
{
    public const VOICE_MESSAGE_MEETING = 'voice-message-meeting';
    public const VOICE_MESSAGE_ANNOUNCEMENT = 'voice-message-announcement';
    public const VOICE_MESSAGE_INSTRUCTION = 'voice-message-instruction';

    public function load(ObjectManager $manager): void
    {
        $agent = $this->getOrCreateAgent($manager);

        // 创建会议语音消息
        $meetingMessage = new VoiceMessage();
        $meetingMessage->setAgent($agent);
        $meetingMessage->setMediaId('media_meeting_voice_' . uniqid());
        $manager->persist($meetingMessage);
        $this->setReference(self::VOICE_MESSAGE_MEETING, $meetingMessage);

        // 创建公告语音消息
        $announcementMessage = new VoiceMessage();
        $announcementMessage->setAgent($agent);
        $announcementMessage->setMediaId('media_announcement_voice_' . uniqid());
        $manager->persist($announcementMessage);
        $this->setReference(self::VOICE_MESSAGE_ANNOUNCEMENT, $announcementMessage);

        // 创建指导语音消息
        $instructionMessage = new VoiceMessage();
        $instructionMessage->setAgent($agent);
        $instructionMessage->setMediaId('media_instruction_voice_' . uniqid());
        $manager->persist($instructionMessage);
        $this->setReference(self::VOICE_MESSAGE_INSTRUCTION, $instructionMessage);

        $manager->flush();
    }
}
