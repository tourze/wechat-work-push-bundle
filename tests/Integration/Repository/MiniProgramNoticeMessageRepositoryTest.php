<?php

namespace WechatWorkPushBundle\Tests\Integration\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatWorkPushBundle\Repository\MiniProgramNoticeMessageRepository;

class MiniProgramNoticeMessageRepositoryTest extends TestCase
{
    private EntityManagerInterface&\PHPUnit\Framework\MockObject\MockObject $entityManager;
    private ManagerRegistry&\PHPUnit\Framework\MockObject\MockObject $registry;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);

        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->willReturn($this->entityManager);
    }

    public function testCanBeInstantiated(): void
    {
        $repository = new MiniProgramNoticeMessageRepository($this->registry);
        $this->assertInstanceOf(MiniProgramNoticeMessageRepository::class, $repository);
    }
}