<?php
/**
 * Created by IntelliJ IDEA.
 * User: Mikee
 * Date: 24-Jul-2010
 * Time: 23:28:30
 * To change this template use File | Settings | File Templates.
 */

class Doctrine_Template_Lockable extends Doctrine_Template
{
    private $lockingManager;
    public function __construct(array $options = array())
    {
    }
    public function getLockIdent()
    {
         if ($this->lockingManager == null)
         {
            $this->lockingManager = new Locking_Manager($this->getTable()->getConnection());
         }
         $this->lockingManager->releaseAgedLocks(300);
         return $this->lockingManager->getLockOwner($this->getInvoker());
    }
    public function getLock()
    {
        if ($this->lockingManager == null)
        {
            $this->lockingManager = new Locking_Manager($this->getTable()->getConnection());
        }
        $this->lockingManager->getLock($this->getInvoker(), sfContext::getInstance()->getUser()->getUsername());
    }
    public function isLockedByAnotherUser()
    {
        if ($this->lockingManager == null)
        {
            $this->lockingManager = new Locking_Manager($this->getTable()->getConnection());
        }
        try
        {
            $this->lockingManager->releaseAgedLocks(300);
            return !$this->lockingManager->isNotLockedByUser($this->getInvoker(), sfContext::getInstance()->getUser()->getUsername());
        } catch(Doctrine_Locking_Exception $dle) {
            echo $dle->getMessage();
            // handle the error
        }
    }
}
