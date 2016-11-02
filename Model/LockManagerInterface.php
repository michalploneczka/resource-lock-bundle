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