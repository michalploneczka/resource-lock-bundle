<?php

namespace Abc\Bundle\ResourceLockBundle\Model;

/**
 * @author Wojciech Ciolko <w.ciolko@gmail.com>
 */
interface LockManagerInterface extends LockInterface
{

    /**
     * Returns new resource lock instance.
     *
     * @param string $name Resource lock name
     * @return ResourceLockInterface
     */
    public function create($name);

    /**
     * Returns the fully qualified class name.
     *
     * @return string
     */
    public function getClass();
}