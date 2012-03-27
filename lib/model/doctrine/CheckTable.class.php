<?php


class CheckTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Check');
    }

    public function getUptime()
    {
      $total = Doctrine::getTable("Check")
                   ->createQuery("c")
                   ->count();
      $successful = Doctrine::getTable("Check")
                   ->createQuery("c")
                   ->where("status = 'OK'")
                   ->count();
      return round(($successful / $total) * 100);
    }

    public function getDowntime()
    {
      $total = Doctrine::getTable("Check")
                   ->createQuery("c")
                   ->count();
      $successful = Doctrine::getTable("Check")
                   ->createQuery("c")
                   ->where("status = 'ERROR'")
                   ->count();
      return round(($successful / $total) * 100);
    }
}