<?php

require_once 'Controller.php';
include 'model/User.php';
include 'view/BookView.php';

class UserController extends Controller {

    private $data = [];
    private $errors = [];

    public function add($params)
    {
        $user = new User();
        if (count($params) > 0) {
            $this -> data = $this -> parseUserData($params);
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
        $this -> data = $user -> getList();
        if (!enpty($this -> data)) {
            $view = new BookView();
            $view -> render($this -> data);
        }
    }

    public function logout()
    {
        session_destroy();
        $this -> redirect('index.php');
    }

    public function login($data)
    {
        $result = ''; //для вывода результата на страницу
        $this -> parseUserData($data);

        if (!($this -> isAuthorized())) {
            $result = 'Введите имя и пароль';
            $this -> redirect('index.php');
        } else {
            $authorized_user = get_authorized_user();
        }
        $errorArray = []; //массив для записи ошибок

        if ((isset($_POST['user_name'])) && (isset($_POST['user_password']))) {
            $login = $_POST['user_name'];
            $password = $_POST['user_password'];
            if (isset($_POST['enter'])) {
                if (login($login, $password)) {
                    redirect('tasks.php');
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
        if (!$user) {
            return FALSE;
        } else {
            if ($user['password'] === $password) {
                $_SESSION['user'] = $user;
                return TRUE;
            }
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
        session_start();
        $errors = [];
    }

}//end class Usercontroller