<?php
// Страница регистрации нового пользователя

// Соединямся с БД
$link=mysqli_connect("localhost", "root", "root123", "testdb");

if(isset($_POST['submit']))
{
    $err = [];

    // проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
        $err[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($_POST['login']) < 3 or strlen($_POST['login']) > 30)
    {
        $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    // проверяем, не сущестует ли пользователя с таким именем
    $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_login='".mysqli_real_escape_string($link, $_POST['login'])."'");
    if(mysqli_num_rows($query) > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
    }

    // Если нет ошибок, то добавляем в БД нового пользователя
    if(count($err) == 0)
    {

        $login = $_POST['login'];

        // Убераем лишние пробелы
        $password = trim(md5(md5($_POST['password'])));

        mysqli_query($link,"INSERT INTO users SET user_login='".$login."', user_password='".$password."'");
        header("Location: login.php"); exit();
    }
    else
    {
        print "<b>При регистрации произошли следующие ошибки:</b><br>";
        foreach($err AS $error)
        {
            print $error."<br>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация</title>
</head>
<body>
  <div id="modal">
    <div class="popup auth">
      <div class="popup__header">
        <div class="popup__title-container">
          <h3 class="popup__title">Регистрация</h3>
        </div>
        <a href="/index.php" class="popup__close-btn" type="button">X</a>
      </div>

      <form class="popup__form auth" method="POST">
        <input name="login" placeholder="Логин" type="text" class="input-login" />
        <input name="password" placeholder="Пароль" type="password" class="input-password" />
        <input name="submit" type="submit" class="send-auth-form" value="Зарегистрироваться"></input>
      </form>
      <div>
        Уже есть аккаунт?<a href="/login.php">Авторизуйтесь</a>
      </div>
    </div>
    </div>
</body>
<style>

#modal {
 
      position: fixed;
      z-index: var(--joy-zIndex-modal);
      inset: 0px;
    }

    .popup__form {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .popup__header {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .popup__title {
      margin: 0;
    }
    .popup__close-btn {
      background-color: transparent;
      padding: 5px;
      border-color: transparent;
      cursor: pointer;
      position: absolute;
      right: 20px;
      top: 20px;
    }
 
    .popup.open {
      display: block;
    }
    .popup {
      border: 1px solid #c3c3c3;
    }
    .send-auth-form {
      padding: 10px;
      border: 1px solid #b9b5b5;
      border-radius: 6px;
    }
    .popup__form input {
      padding: 10px;
      border: 1px solid #b9b5b5;
      border-radius: 6px;
    }
    @media screen and (min-width: 0px) {
      .popup {
        border: 1px solid #c3c3c3;
        position: fixed;
        inset: 90px 0px 0px;
        border-radius: 0px;
        padding: 0px 0px 30px;
        width: 100%;
        max-height: 100%;
        overflow: unset;
      }
    }
    @media screen and (min-width: 480px) {
      .popup {
        position: absolute;
        top: 50%;
        left: 50%;
        bottom: auto;
        transform: translate(-50%, -50%);
        border-radius: 20px;
        width: 400px;
        max-height: 500px;
        overflow: hidden;
      }
    }
    @media screen and (min-width: 768px) {
      .popup {
        width: 700px;
        padding: 20px;
      }
    }
</style>
</html>