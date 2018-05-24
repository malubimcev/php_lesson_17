<?php
    
require_once 'vendor/autoload.php';

class View
{

    private $loader = NULL;
    private $twig = NULL;
    
    public function __construct() {
        $this -> loader = new Twig_Loader_Filesystem('./template');
        $this -> twig = new Twig_Environment($loader, array(
            'cache' => './cache',
            'auto_reload' => true,
        ));
    }
    
    public function render($template, $data = null)
    {
        $this -> twig ->render($template, $data);
    }
}
