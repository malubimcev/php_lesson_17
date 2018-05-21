<?php

require_once 'database.class.php';

class Model
{
    private $db = NULL;

    abstract function add($params);
    abstract function update($id, $params);
    abstract function delete($id);
    abstract function getList();
    abstract function getById($id);
    
    public function doRequest($request, $params)
    {
       $this -> get_database(); 
       return $this -> db -> do_request($request, $params);
    }
    
    private function get_database()
    {
        if ($this -> db === NULL) {
            $this -> db = new Database();
        }
    }
    
}//end class Model

