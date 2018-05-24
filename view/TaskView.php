<?php
require_once 'view/View.php';

class TaskView extends View
{
    private $template = 'tasks.html';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template, array('tasks' => $data));    
    }

}//end class BookView