<?php
    require_once 'router/router.php';
    
    if (isset($_POST)) {
        $router = new Router();
        $router -> start();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Авторизация</title>
        <meta charset="UTF-8">
    </head>
    <body>
      <section class="main-container">
        <h1>Задание к лекции 4.3 «SELECT из нескольких таблиц»</h1>
        <div class="form-container">
            <form action="?/user/auth" method="POST" class="user-input-form">
                <input type="text" name="user_name" placeholder="Имя пользователя" value=""> 
                <input type="submit" name="enter" value="Вход" class="button select-button">
            </form><br>
            <a class="logout-button" href="?/user/logouth">Выход</a>
        </div>
    </section>
    </body>
</html>
