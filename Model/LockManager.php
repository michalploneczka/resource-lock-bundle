<?php
/*
* This file is part of the resource-lock-bundle package.
*
* (c) Wojciech Ciolko <wojciech.ciolko@aboutcoders.com>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Abc\Bundle\ResourceLockBundle\Model;

/**
 * @author Wojciech Ciolko <w.ciolko@aboutcoders.com>
 */
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