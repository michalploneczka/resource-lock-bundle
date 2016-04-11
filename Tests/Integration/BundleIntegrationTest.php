<?php

namespace Abc\Bundle\ResourceLockBundle\Tests\Integration;

use Abc\Bundle\ResourceLockBundle\Model\LockManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
 */
class BundleIntegrationTest extends KernelTestCase
{
    /** @var Application */
    private $application;

    /** @var EntityManager */
    private $em;

    /** @var ContainerInterface */
    private $container;


    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        self::bootKernel();

        $this->container = static::$kernel->getContainer();

        $this->em = $this->container->get('doctrine')->getManager();

        $this->application = new Application(static::$kernel);

        $this->application->setAutoExit(false);
        $this->application->setCatchExceptions(false);

        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:update", array("--force" => true));
    }

    public function testResourceLockInheritanceMapping()
    {
        /** @var LockManagerInterface $manager */
        $manager = $this->container->get('abc.demo.lock_manager');

        $name = 'test';
        $lock = $manager->lock($name);

        $this->em->clear();

        $this->assertTrue($manager->isLocked($name));

        $this->assertInstanceOf('Abc\Bundle\ResourceLockBundle\Model\ResourceLockInterface', $lock);
    }

    public function testResourceRelease()
    {
        /** @var LockManagerInterface $manager */
        $manager = $this->container->get('abc.demo.lock_manager');

        $name = 'test';
        $lock = $manager->lock($name);

        $this->em->clear();
        $this->assertStringStartsWith("abc-lock-", $lock->getName());
        $this->assertNotNull($lock->getId());
        $this->assertInstanceOf("\DateTime", $lock->getCreatedAt());
        $this->assertTrue($manager->isLocked($name));

        $this->assertTrue($manager->release($name));
        $this->assertFalse($manager->isLocked($name));
    }

    /**
     * @expectedException \Abc\Bundle\ResourceLockBundle\Exception\LockException
     * @expectedExceptionMessage   Lock with provided name already exists
     */
    public function testLockResourceWhichIsAlreadyLocked()
    {
        /** @var LockManagerInterface $manager */
        $manager = $this->container->get('abc.demo.lock_manager');

        $name = 'test1';
        $lock = $manager->lock($name);
        $this->em->clear();
        $lock = $manager->lock($name);
        $this->em->clear();
    }

    public function testCustomManagerResourceRelease()
    {
        /** @var LockManagerInterface $manager */
        $manager = $this->container->get('abc.resource_lock.lock_manager_new_manager');

        $name = 'test';
        $lock = $manager->lock($name);

        $this->em->clear();

        $this->assertStringStartsWith("my_prefix-", $lock->getName());
        $this->assertTrue($manager->isLocked($name));

        $this->assertTrue($manager->release($name));
        $this->assertFalse($manager->isLocked($name));
    }

    protected function runConsole($command, array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options       = array_merge($options, array('command' => $command));

        return $this->application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }
}