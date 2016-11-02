<?php
/*
* This file is part of the resource-lock-bundle package.
*
* (c) Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\ResourceLockBundle\Tests\Model;

use Abc\Bundle\ResourceLockBundle\Model\LockManager;
use Abc\Bundle\ResourceLockBundle\Model\ResourceLock;

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
            ->will($this->returnValue(ResourceLock::class));

        $schedule = $manager->create('test');

        $this->assertInstanceOf(ResourceLock::class, $schedule);
    }

    /**
     * @return LockManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getManager()
    {
        return $this->getMockForAbstractClass(LockManager::class);
    }
}