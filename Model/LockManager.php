<?php
namespace Abc\Bundle\ResourceLockBundle\Model;

abstract class LockManager implements LockManagerInterface
{

    /**
     * {@inheritDoc}
     */
    public function create($name)
    {
        $class = $this->getClass();

        $lock = new $class;
        $lock->setName($name);

        return $lock;
    }

}