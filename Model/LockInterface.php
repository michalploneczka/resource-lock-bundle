<?php

namespace Abc\Bundle\ResourceLockBundle\Model;
use Abc\Bundle\ResourceLockBundle\Exception\LockException;

/**
 * LockInterface
 */
interface LockInterface
{

    /**
     * @param string $name Resource name
     * @return $mixed|void LockObject
     * @throws LockException Threw when lock can not be set or already set
     */
    public function lock($name);

    /**
     * @param string $name Resource name
     * @return boolean
     */
    public function isLocked($name);

    /**
     * @param string $name Resource name
     * @return boolean
     */
    public function release($name);

}