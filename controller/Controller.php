<?php

abstract class Controller
{
    abstract function add($params);
    abstract function update($id, $params);
    abstract function delete($id);
    abstract function getList();
    
    public function redirect($page)
    {
	header("Location: $page");
	die;
    }
    
}//end class Controller

