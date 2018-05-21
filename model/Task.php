<?php

require_once 'Model.php';

class Task extends Model
{
    private $task = NULL;
    private $count = 0;
    
    public function add($params) 
    {
        $author_id = $this -> get_user_id($author);
        $assigned_user_id = $this -> get_user_id($user);
        $request = 'INSERT INTO task (
                        description,
                        is_done,
                        user_id,
                        assigned_user_id)
                    VALUES (
                        :description,
                        0,
                        :user_id,
                        :assigned_user_id)';
        $params = [
            ':description' => $description,
            ':user_id' => $author_id,
            ':assigned_user_id' => $assigned_user_id
        ];
        $this -> do_request($request, $params);
        return;
    }
    
    public function delete($id) 
    {
        $request = 'DELETE FROM
                        task
                    WHERE
                        id=:id';
        $params = [
            ':id' => $id
        ];
        $this -> do_request($request, $params);
        return;
    }
    
    public function update($id, $params) 
    {
        ;
    }
    
    private function getList()
    {
        ;
    }
    
    public function getById($id)
    {
        ;
    }
    
    public function getByUser($user_id)
    {
        $user_id = $this -> get_user_id($user_name);
        $request = 'SELECT
                        task.id AS id,
                        task.description AS description,
                        task.date_added AS date_added,
                        user.login AS author,
                        task.is_done AS is_done
                    FROM
                        task 
                    INNER JOIN
                        user
                    ON
                        user.id = task.user_id
                    WHERE
                        task.assigned_user_id = :user_id
                        AND task.user_id <> :user_id
                    ORDER BY ';
        $request .= $sort_param;
        $params = [
            ':user_id' => $user_id
        ];
        $this -> recordset = $this -> do_request($request, $params);
        if (!isset($this -> recordset)) {
            $this -> recordset = $this -> get_empty_tasks();
        }
        return $this -> recordset;
    }
    
    public function getByAuthor($author_id)
    {
        $author_id = $this -> get_user_id($author_name);
        $request = 'SELECT
                        task.id AS id,
                        task.description AS description,
                        task.date_added AS date_added,
                        user.login AS assigned_user,
                        task.is_done AS is_done
                    FROM
                        task
                    INNER JOIN
                        user
                    ON
                        user.id = task.assigned_user_id
                    WHERE
                        task.user_id = :author_id 
                    ORDER BY ';
        $request .= $sort_param;
        $params = [
            ':author_id' => $author_id
        ];
        $this -> recordset = $this -> do_request($request, $params);
        if (!isset($this -> recordset)) {
            $this -> recordset = $this -> get_empty_tasks();
        }
        return $this -> recordset;
    }
    
    public function assign($task_id, $user_id)
    {
        $request = 'UPDATE
                        task
                    SET
                        assigned_user_id=:user_id
                    WHERE
                        id=:id';
        $params = [
            ':user_id' => $user_id,
            ':id' => $task_id
        ];
        $this -> do_request($request, $params);
        return;
    }

    public function close($id)
    {
        $request = 'UPDATE
                        task
                    SET
                        is_done=:is_done
                    WHERE 
                        id=:id';
        $params = [
            ':is_done' => 1,
            ':id' => $id
        ];
        $this -> do_request($request, $params);
        return;
    }
    
    private function getEmptyList()//возвращает пустой набор задач
    {
        $empty_set = [
            'description' => '-',
            'date_added' => '-',
            'is_done' => '-',
            'user_name' => '-',
            'author' => '-'
        ];
        return $empty_set;
    }

    
}//end class UserModel


