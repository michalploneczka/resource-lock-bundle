<?php
/*
* This file is part of the resource-lock-bundle package.
*
* (c) Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\ResourceLockBundle\Tests\Integration;

use Abc\Bundle\ResourceLockBundle\Model\LockManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceTest extends KernelTestCase
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container = static::$kernel->getContainer();

        $application = new Application(static::$kernel);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
    }

    public function testLockManager()
    {
        $subject = $this->container->get('abc.resource_lock.lock_manager');

        $this->assertInstanceOf(LockManagerInterface::class, $subject);
    }

    public function testCustomLockManager()
    {
        $manager1 = $this->container->get('abc.resource_lock.lock_manager_new_manager');
        $manager2 = $this->container->get('abc.resource_lock.lock_manager_one_more_manager');

        $this->assertInstanceOf(LockManagerInterface::class, $manager1);
        $this->assertInstanceOf(LockManagerInterface::class, $manager2);
    }
}