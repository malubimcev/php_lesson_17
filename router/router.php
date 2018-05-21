<?php

    require_once '../controller/UserController.php';
    
    class Router
    {
        public function start()
        {
            $controller = new UserController();
            $controller -> getList();
        }
    }
    
    function do_command(&$params)
    {
        $result = [];//массив для результата запроса на выборку
        $sort_param = 'date_added';
        $author = $params['author'];
        $db = get_database();//получаем ссылку на текущую базу данных
        
        //сохранение новой задачи
        if (isset($params['save'])) {
            $db -> add_task($params['description'], $author, $author);
            $result = $db -> get_tasks_by_author($author, $sort_param);
            return $result;
        }
        
        //выборка и сортировка
        if (isset($params['sort'])) {
            switch ($params['sort_by']) {//выбираем поле сортировки
                case 'is_done':
                    $sort_param = 'is_done';
                    break;
                case 'description':
                    $sort_param = 'description';
                    break;
                default:
                    $sort_param = 'date_added';
                    break;
            }
            $result = $db -> get_tasks_by_author($author, $sort_param);
            return $result;
        }
        
        //назначение ответственного
        if (isset($params['assigned_user_id'])) {
            $db -> assign_task($params['task_id'], $params['assigned_user_id']);
            $result = $db -> get_tasks_by_author($author, $sort_param);
            return $result;
        }
        
        //изменение, удаление
        if (isset($params['id'])) {
            $request_params = [];//массив для параметров запросов
            $tmp_params = [];//временный массив для параметров запросов
            $tmp_params = explode(';',$params['id']);
            $request_params['id'] = $tmp_params[0];//получаем id записи
            $tmp_params[1] = explode('=', $tmp_params[1])[1];//находим значение action
            switch ($tmp_params[1]) {
                case 'done':
                    $request_params['is_done'] = 1;
                    $db -> close_task($request_params['id']);
                    break;
                case 'delete':
                    $db -> delete_task($request_params['id']);
                    break;
                default:
                    break;
            }
        }
        $result = $db -> get_tasks_by_author($author, $sort_param);
        return $result;
    }
    
    require_once 'db_functions.php';
    session_start();
    $errors = [];
    
    //$pass = password_hash("1234", PASSWORD_DEFAULT);
    
    function login($userName, $password) //функция проверки логина и пароля
    {
        $user = getUser($userName);
        if (!$user) {
            return FALSE;
        } else {
            if ($user['password'] === $password) {
                $_SESSION['user'] = $user;
                return TRUE;
            }
        }
    }

    function getUser($userName) //функция получения пользователя по имени
    {
        $db = get_database();
        $user = $db -> get_user_by_name($userName);
        if (isset($user)) {
            return $user;
        } else {
            return NULL;
        }
    }
    
    function get_authorized_user()
    {
        return $_SESSION['user']['login'];
    }

    function isAuthorized()
    {
        return !empty($_SESSION['user']);
    }
