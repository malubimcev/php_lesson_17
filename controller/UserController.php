<?php

include 'model/User.php';
include './../view/BookView.php';

class UserController extends Controller
{
    private $data = [];
    private $errors =[];
    
    public function add($params)
    {
        $user = new User();
        if (count($_POST) > 0) {
            $this -> data = $this -> parseUserData($_POST);
            if (count($errors) == 0) {
                $idAdd = $user -> add($this -> data);
                if ($idAdd) {
                    header('Location: /');
                }
            }
        }
//        Di::get()->render('book/add.php');        
    }
    
    public function delete($id)
    {
        if (isset($id) && is_numeric($id)) {
            $user = new User();
            $isDelete = $user -> delete($id);
            if ($isDelete) {
                header('Location: /');
            }
        }        
    }

    public function update($id, $params)
    {
        
    }

    public function getList()
    {
        $user = new User();
        if ($user -> getList()) {
            $view = new BookView();
            $view ->render($user -> getList());
        }
    }
    
    private function parseUserData($data)
    {
        if (isset($data['login']) && preg_match('/[0-9A-z\s]+/', $data['login'])) {
            $this -> data['login'] = $data['login'];
        } else {
            $this -> errors['login'] = 'Error login';
        }
        if (isset($data['password']) && preg_match('/[0-9A-z\s]+/', $data['password'])) {
            $this -> data['password'] = $data['password'];
        } else {
            $this -> errors['password'] = 'Error password';
        }
    }
    
    public function logout()
    {
	session_destroy();
	$this -> redirect('login.html');
    }
    
    public function login($login, $password)
    {
        if (!isAuthorized()) {
            redirect('login.php');
        } else {
            $authorized_user = get_authorized_user();
        }
        $errorArray = [];//массив для записи ошибок
        $result = '';//для вывода результата на страницу
        if ((isset($_POST['user_name'])) && (isset($_POST['user_password']))) {
            $login = $_POST['user_name'];
            $password = $_POST['user_password'];
            if (isset($_POST['enter'])) {
                if (login($login, $password)) {
                    redirect('index.php');
                } else {
                    $result = 'Пользователь не зарегистрирован';
                }
            }
            if (isset($_POST['register'])) {
                saveUser($login, $password);
                $result = 'Вы зарегистрированы';
            } 
        } else {
            $result = 'Введите имя и пароль';
        }
    }

}//end class Usercontroller

