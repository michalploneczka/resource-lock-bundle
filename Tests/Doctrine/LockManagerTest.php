<?php
namespace Abc\Bundle\ResourceLockBundle\Tests\Doctrine;

use Abc\Bundle\ResourceLockBundle\Doctrine\LockManager;
use Abc\Bundle\ResourceLockBundle\Entity\ResourceLock;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\DriverException;
use Doctrine\DBAL\Exception\ServerException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @author Wojciech Ciolko <w.ciolko@gmail.com>
 */
class LockManagerTest extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    private $class;
    /** @var ClassMetadata|\PHPUnit_Framework_MockObject_MockObject */
    private $classMetaData;
    /** @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject */
    private $objectManager;
    /** @var ObjectRepository|\PHPUnit_Framework_MockObject_MockObject */
    private $repository;

    /** @var LockManager */
    private $subject;

    public function setUp()
    {
        $this->class         = 'Abc\Bundle\ResourceLockBundle\Entity\ResourceLock';
        $this->classMetaData = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->objectManager = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository    = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        $this->objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue($this->classMetaData));

        $this->classMetaData->expects($this->any())
            ->method('getName')
            ->will($this->returnValue($this->class));

        $this->objectManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->subject = new LockManager($this->objectManager, $this->class);
    }

    public function testGetClass()
    {
        $this->assertEquals($this->class, $this->subject->getClass());
    }

    public function testIsLockedWithNoExistingLockReturnsFalse()
    {
        $lockName = 'ABC';
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $lockName))
            ->willReturn(null);

        $result = $this->subject->isLocked($lockName);

        $this->assertFalse($result);
    }


    public function testIsLockedWithExistingLockReturnsTrue()
    {
        $lockName = 'ABC';
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $lockName))
            ->willReturn(new ResourceLock());

        $result = $this->subject->isLocked($lockName);

        $this->assertTrue($result);
    }

    public function testLockWithNoExistingLockReturnsTrue()
    {
        $lockName = 'ABC';

        $this->objectManager->expects($this->once())
            ->method('persist');
        $this->objectManager->expects($this->once())
            ->method('flush');

        $result = $this->subject->lock($lockName);

        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\ResourceLockInterface', $result);
    }

    public function testReleaseWithNoExistingLockReturnsFalse()
    {
        $lockName = 'ABC';
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $lockName))
            ->willReturn(null);

        $result = $this->subject->release($lockName);

        $this->assertFalse($result);
    }

    public function testReleaseWithExistingLockReturnsTrue()
    {
        $lockName = 'ABC';
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(array('name' => $lockName))
            ->willReturn(new ResourceLock());

        $this->objectManager->expects($this->once())
            ->method('remove');
        $this->objectManager->expects($this->once())
            ->method('flush');

        $result = $this->subject->release($lockName);

        $this->assertTrue($result);
    }

    /**
     * @expectedException \Abc\Bundle\ResourceLockBundle\Exception\LockException
     * @expectedExceptionMessage Lock with provided name already exists
     */
    public function testLockWithExistingLocThrowsException()
    {
        $lockName = 'ABC';

        $ex = $this->getMock('\Doctrine\DBAL\Driver\DriverException');
        $this->objectManager->expects($this->once())
            ->method('persist')->willThrowException(
                new UniqueConstraintViolationException('Unique', $ex)
            );

        $this->subject->lock($lockName);
    }


    /**
     * @expectedException \Abc\Bundle\ResourceLockBundle\Exception\LockException
     * @expectedExceptionMessage Lock with provided name can not be set
     */
    public function testLockThrowsServerException()
    {
        $lockName = 'ABC';

        $ex = $this->getMock('\Doctrine\DBAL\Driver\DriverException');
        $this->objectManager->expects($this->once())
            ->method('persist')->willThrowException(
                new ServerException('ServerException', $ex)
            );

        $this->subject->lock($lockName);
    }
}
 