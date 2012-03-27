<?php
/**
 * Created by IntelliJ IDEA.
 * User: Mikee
 * Date: 25-Jul-2010
 * Time: 20:44:22
 * To change this template use File | Settings | File Templates.
 */

class cpCmsRoute extends sfRoute {

    private $_object = null;
    public function setObject($obj)
    {

        $this->_object = $obj;
    }
    public function getObject()
    {
        return $this->_object;
    }


}
