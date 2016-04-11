<?php

namespace Abc\Bundle\ResourceLockBundle\Tests\Integration;

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

        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\LockManagerInterface', $subject);
    }

    public function testCustomLockManager()
    {
        $manager1 = $this->container->get('abc.resource_lock.lock_manager_new_manager');
        $manager2 = $this->container->get('abc.resource_lock.lock_manager_one_more_manager');

        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\LockManagerInterface', $manager1);
        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\LockManagerInterface', $manager2);
    }
}