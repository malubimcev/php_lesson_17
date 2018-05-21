<?php

require_once 'Controller.php';
include 'model/TaskModel.php';
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
    
    public function getList()
    {
        
    }
    
}//end class TaskController

