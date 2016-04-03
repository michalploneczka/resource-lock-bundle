<?php

namespace Abc\Bundle\ResourceLockBundle\Tests\Model;

use Abc\Bundle\ResourceLockBundle\Model\LockManager;

/**
 * @author Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
 */
class LockManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $manager = $this->getManager();

        $manager->expects($this->once())
            ->method('getClass')
            ->will($this->returnValue('Abc\Bundle\ResourceLockBundle\Model\ResourceLock'));

        $schedule = $manager->create('test');

        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\ResourceLock', $schedule);
    }

    /**
     * @return LockManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getManager()
    {
        return $this->getMockForAbstractClass('Abc\Bundle\ResourceLockBundle\Model\LockManager');
    }
}
 