<?php

namespace Abc\Bundle\ResourceLockBundle\Doctrine;

use Abc\Bundle\ResourceLockBundle\Exception\LockException;
use Abc\Bundle\ResourceLockBundle\Model\LockManager as BaseLockManager;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Exception\ServerException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @author Wojciech Ciolko <w.ciolko@gmail.com>
 */
class LockManager extends BaseLockManager
{
    /** @var ObjectManager */
    protected $objectManager;
    /** @var string */
    protected $class;
    /** @var ObjectRepository */
    protected $repository;


    /**
     * @param ObjectManager $om
     * @param string        $class
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository    = $om->getRepository($class);

        $metadata    = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * @param string $name
     * @return object
     */
    private function findByName($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function lock($name)
    {
        $lock = $this->create($name);
        try {
            $this->objectManager->persist($lock);
            $this->objectManager->flush();
            return $lock;
        } catch (UniqueConstraintViolationException $e) {
            throw new LockException('Lock with provided name already exists', $e->getErrorCode(), $e);
        } catch (ServerException $e) {
            throw new LockException('Lock with provided name can not be set', $e->getErrorCode(), $e);
        }
    }

    public function isLocked($name)
    {
        $lock = $this->findByName($name);
        return $lock ? true : false;
    }

    public function release($name)
    {
        $lock = $this->repository->findOneBy(array('name' => $name));
        if ($lock) {
            $this->objectManager->remove($lock);
            $this->objectManager->flush();
            return true;
        }
        return false;
    }


    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}