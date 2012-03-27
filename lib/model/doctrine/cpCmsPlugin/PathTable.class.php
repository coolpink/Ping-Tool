<?php


class PathTable extends PluginPathTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Path');
    }
}