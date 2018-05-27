<?php

require_once 'Model.php';

class User extends Model
{
    private $user = NULL;
    private $count = 0;
    private $users = [
        ['login' => 'user1', 'password' => '1111'],
        ['login' => 'user2', 'password' => '2222'],
        ['login' => 'admin', 'password' => '4321']
    ];

    public function add($params) 
    {
        $is_exist = $this -> getByName($login);
        if ($is_exist === 0) {
            $request = 'INSERT INTO user (
                            login,
                            password)
                        VALUES (
                            :login,
                            :password)';
            $params = [
                ':login' => $login,
                ':password' => $password
            ];
            parent::doRequest($request, $params);
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delete($id) 
    {
        //
    }
    
    public function update($id, $params) 
    {
        //
    }
    
    public function getList()
    {
        return $this -> users;//=================================
        $request = 'SELECT
                        id AS id,
                        login,
                        password
                    FROM
                        user
                    ORDER BY
                        login ASC';
        $params = [
            '' => ''
        ];
        $this -> recordset = parent::doRequest($request, $params);
        if (empty($this -> recordset)) {
            $this -> recordset = $this -> get_empty_users();
        }
        return $this -> recordset;
    }
    
    public function getById($id)
    {
        $request = 'SELECT
                        id AS id,
                        login,
                        password
                    FROM
                        user
                    WHERE
                        id = :id';
        $params = [
            ':id' => $id
        ];
        $this -> recordset = parent::doRequest($request, $params);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    public function getByName($name)
    {
        $request = 'SELECT
                        id AS id,
                        login,
                        password
                    FROM
                        user
                    WHERE
                        login = :login';
        $params = [
            ':login' => $name
        ];
        $this -> recordset = parent::doRequest($request, $params);
        if (empty($this -> recordset)) {
            return NULL;
        } else {
            return $this -> recordset[0];
        }
    }
    
    private function getEmptyList()//возвращает пустой набор пользователей
    {
        $empty_set = [
            'id' => 0,
            'login' => '-',
            'password' => '-',
        ];
        return $empty_set;
    }

    
}//end class UserModel
