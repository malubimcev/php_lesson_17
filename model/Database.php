<?php

class Database
{
    private $db = NULL;
    private $recordset = [];
    
    private function get_connection()//создаем и возвращаем объект PDO
    {
        require_once 'config.php';//подключение файла конфигурации параметров соединения
        try {
            $pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            return $pdo;
        } catch (Exception $error) {
            return NULL;
        }
    }
    
    public function __construct() {
        $this -> db = $this -> get_connection();//тестируем подключение
        if (!isset($this -> db)) {
            die('Не удалось подключиться к базе данных');
        }
    }

    public function do_request($request, $params)//выполняет запрос с параметрами
    {
        $results = [];
        $stmt = NULL;
        try {
            $this -> db = $this -> get_connection();
            $stmt = $this -> db -> prepare($request);
            var_dump($params);
            echo '<br>';
            foreach ($params as $key => $value) {
                $stmt -> bindValue($key, $value);
            }
            $stmt -> execute();
            $this -> db = NULL;
            if (isset($stmt)) {
                while ($row = $stmt -> fetch()) {
                    $results[] = $row;
                }
            } else {
                $results = NULL;
            }
        } catch (Exception $error) {
            echo $error -> getMessage();
        }
        return $results;
    }
        
}//===end class===