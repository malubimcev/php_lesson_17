<?php
require_once 'View.php';

class BookView extends View
{
    private $template = 'books.html';
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($data)
    {
        parent::render($this -> template, array('books' => $data));    
    }

}//end class BookView


