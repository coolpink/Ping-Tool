<?php


class ClientTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Client');
    }

    public function findAllOrdered()
    {
      $q = Doctrine::getTable("client")
           ->createQuery("c")
           ->orderBy("c.title");
      return $q->execute();
    }
}