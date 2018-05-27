<?php

require_once 'Controller.php';
require_once 'model/TaskModel.php';

class TaskController extends Controller
{
    private $tasks = [];//список задач пользователя
    private $users = [];//список пользователей
    private $assigned_tasks = [];//список порученных задач
    private $data = [];//параметры для запроса в модель
    private $errors = [];//массив для записи ошибок
    
    public function add($params)
    {
        $task = new Task();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $task -> add($this -> data);
                if ($idAdd) {
                    //header('Location: /?/user/login/');
                }
            }
        }
    }
    
    public function delete($params)
    {
        $task = new Task();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $task -> delete($this -> data);
                if ($idAdd) {
                    //header('Location: /?/user/login/');
                }
            }
        }
    }

    public function update($id, $params)
    {
        
    }

    public function getList()
    {
        
    }
    
    public function getAssignedTasks($user_name)
    {
        
    }

    public function sort($params)
    {
        $task = new Task();
        if (count($params) > 0) {
            $this -> data = $this -> parseData($params);
            if (count($this -> errors) == 0) {
                $idAdd = $task -> getByAuthor($this -> data);
                if ($idAdd) {
                    //header('Location: /?/user/login/');
                }
            }
        }
    }

    public function done($params)
    {
        
    }
    
    private function parseData($data)
    {
        if (isset($data['id']) && preg_match('/[0-9\s]+/', $data['id'])) {
            $this -> data['id'] = $data['id'];
        } else {
            $this -> errors['id'] = 'Error id';
        }
        if (isset($data['name']) && preg_match('/[0-9A-z\s]+/', $data['name'])) {
            $this -> data['name'] = $data['name'];
        } else {
            $this -> errors['password'] = 'Error name';
        }
        //выборка и сортировка
        if (isset($data['sort'])) {
            switch ($data['sort_by']) {//выбираем поле сортировки
                case 'is_done':
                    $this -> data['sort_by'] = 'is_done';
                    break;
                case 'description':
                    $this -> data['sort_by']  = 'description';
                    break;
                default:
                    $this -> data['sort_by'] = 'date_added';
                    break;
            }
        }
        //назначение ответственного
        if (isset($data['assigned_user_id'])) {
            $this -> data['assigned_user_id'] = $data['assigned_user_id'];
        }
        
    }

}//end class TaskController

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
               
    }
