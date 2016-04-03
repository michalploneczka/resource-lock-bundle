<?php

namespace Abc\Bundle\ResourceLockBundle\Entity;

use Abc\Bundle\ResourceLockBundle\Doctrine\LockManager as BaseLockManager;
use Doctrine\ORM\EntityManager;

/**
 * @author Wojciech Ciolko <w.ciolko@gmail.com>
 */
class LockManager extends BaseLockManager
{
    /** @var EntityManager */
    protected $em;


    /**
     * @param EntityManager $em
     * @param string        $class
     */
    public function __construct(EntityManager $em, $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }
}