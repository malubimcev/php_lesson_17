<?php
    require_once 'router/Router.php';
    
    echo 'REQUEST_URI='.$_SERVER['REQUEST_URI'].'<br>=======<br>';
    echo 'REQUEST_METHOD='.$_SERVER['REQUEST_METHOD'].'<br>=======<br>';
//    echo '__FILE__='.__FILE__.'<br>=======<br>';
//    echo 'DOCUMENT_ROOT='.$_SERVER['DOCUMENT_ROOT'];
//    echo '<br>===POST===<br>';
//    if (!empty($_POST)) {
        $router = new Router('controller/');
        $router -> start();
//        var_dump($_POST);
//    }
//    echo '<br>===GET===<br>';
//    if (!empty($_GET)) {
//        $router = new Router('controller/');
//        $router -> start();
////        var_dump($_GET);
//    }
    
    include 'template/header.html';
    include 'template/login.html';
    include 'template/userList.html';//===================
    include 'template/footer.html';
?>
