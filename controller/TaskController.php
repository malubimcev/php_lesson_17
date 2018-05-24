<?php

require_once 'Controller.php';
require_once 'model/TaskModel.php';
    $tasks = [];//список задач пользователя
    $users = [];//список пользователей
    $assigned_tasks = [];//список порученных задач
    $params = [];//параметры запроса для передачи
    $id = 0;
    $users = get_users();
    if ((!empty($_GET)) && (empty($_POST))) {
        $params = filter_input_array(INPUT_GET, $_GET);
        $params['author'] = $authorized_user;
        unset($_GET);
    } else {
        if ((isset($_POST)) && (!isset($_POST['password']))) {
            unset($_GET);
            $params = filter_input_array(INPUT_POST, $_POST);
            $params['author'] = $authorized_user;
            unset($_POST);
        }
    }
    $tasks = do_command($params);
    $assigned_tasks = get_assigned_tasks($authorized_user);
    unset($params);

class TaskController extends Controller
{
    
    public function add($params)
    {
        
    }
    
    public function delete($id)
    {
        
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
