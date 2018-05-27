<?php

require_once 'Controller.php';
include 'model/User.php';
include 'view/BookView.php';

class UserController extends Controller {

    private $data = [];
    private $errors = [];
    private $result ='';

    public function add($params)
    {
        $user = new User();
        if (count($params) > 0) {
            $this -> data = $this -> parseUserData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $user -> add($this -> data);
                if ($idAdd) {
                    header('Location: /?/user/login/');
                }
            }
        }
    }

    public function delete($id)
    {
        if (isset($id) && is_numeric($id)) {
            $user = new User();
            $isDelete = $user -> delete($id);
            if ($isDelete) {
                header('Location: /?/user/login/');
            }
        }
    }

    public function update($id, $params)
    {
        //
    }

    public function getList()
    {
        $user = new User();
        $this -> data = $user -> getList();
        if (!empty($this -> data)) {
            $view = new BookView();
            $view -> render($this -> data);
        }
    }

    public function logout()
    {
        session_destroy();
        parent::redirect('index.php');
    }

    public function login($data)
    {
        $this -> data = $this -> parseUserData($data);

//        if (!($this -> isAuthorized())) {
//            $this ->  result = 'Введите имя и пароль';
//            $this -> redirect('index.php');
//        } else {
//            $authorized_user = get_authorized_user();
//        }

        if ((isset($this -> data['user_name'])) && (isset($this -> data['user_password']))) {
            $login = $_POST['user_name'];
            $password = $_POST['user_password'];
            if (isset($_POST['enter'])) {
                if ($this -> checkUser($login, $password)) {
                    parent::redirect('index.php');//========================================================
                } else {
                    $this -> result = 'Пользователь не зарегистрирован';
                }
            }
            if (isset($_POST['register'])) {
                $this -> add($login, $password);
                $this -> result = 'Вы зарегистрированы';
            }
        } else {
            $this -> result = 'Введите имя и пароль';
        }
    }
    
    public function getResult()
    {
        return $this -> result;
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

    private function getUser($userName)
    { //функция получения пользователя по имени
        if (isset($userName)) {
            $userModel = new User();
            $user = $userModel -> getByName($userName);
            if (isset($user)) {
                return $user;
            } else {
                return NULL;
            }
        }
    }

    private function get_authorized_user()
    {
        return $_SESSION['user']['login'];
        //$pass = password_hash("1234", PASSWORD_DEFAULT);
    }

    private function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }
    
    private function checkUser($login, $password)
    {
        $user = $this -> getUser($login);
        if (!$user) {
            return FALSE;
        } else {
            if ($user['password'] === $password) {
                session_start();
                $_SESSION['user'] = $user;
                return TRUE;
            }
        }
        
    }

}//end class Usercontroller