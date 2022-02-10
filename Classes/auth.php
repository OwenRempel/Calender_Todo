<?php

/*
    This is a stand alone autherizeation and login exstention
    simpy require this file at the start of the program and make sure 
    required mysql tables are suplied also it requires a class called database
    with a function called query  
    Author Owen Rempel Mar 4 2020
*/


//var decliration

$cstrong= True;

$token = bin2hex(openssl_random_pseudo_bytes(56, $cstrong));


// HTML for login

function login(){

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
      <link rel='stylesheet' type='text/css' href='css/login.css'>
    </head>
    <body>
        <div class="login">
            <form action="" method="post" class="log">
                <div class="input-field">
                <input type="text" name="user">
                    <label for="user" class="active">Username</label>
                </div>
                <div class="input-field">
                <input type="password" name="pass">
                    <label for="pass" class="active">Password</label>
                </div>
                <input type="submit" value="Login" name="auth_pass_send" class="btn blue">
            </form>
        </div>
    </body>
    </html>

    <?php

}

// loop through and delete any entrys that have expired

$get_entrys = Database::query('SELECT ID, Expire from Auth');

foreach($get_entrys as $row){
    if($row['Expire'] < time()){
        Database::query('DELETE from Auth where ID = :id', array('id'=>$row['ID']));
    }
}

// SQL for app

/*
CREATE TABLE `funds`.`auth` 
 ( `token` TEXT NOT NULL , 
    `expire` TEXT NOT NULL , 
    `user` TEXT NULL , 
    `adate` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `ID` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`ID`)) ENGINE = InnoDB;
CREATE TABLE `funds`.`users` 
  ( `user` TEXT NOT NULL , 
    `pass` TEXT NOT NULL , 
    `adate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
    `ID` INT NOT NULL AUTO_INCREMENT , PRIMARY KEY (`ID`)) ENGINE = InnoDB;
*/

// Check for logout parm

if(isset($_GET['auth_logout'])){
    if(isset($_COOKIE['CID'])){
        Database::query('DELETE FROM Auth WHERE Token=:token', array(':token'=>$_COOKIE['CID']));
    }
    setcookie('CID', 1, time() - 36000, '/', NULL, NULL, TRUE);
    header('location:./');
}


//check if cookies exist

if(isset($_COOKIE['CID']) and !isset($_POST['auth_pass_send'])){

    $data = Database::query("SELECT ID from Auth WHERE Token=:token", array('token'=>$_COOKIE['CID']));
    if(!$data){
        echo login();
        exit();
    }

}else{

    // post check

    if(isset($_POST['auth_pass_send']) and isset($_POST['user']) and isset($_POST['pass'])){
        $data = Database::query("SELECT User, Pass, ID FROM Users WHERE User = :user LIMIT 1", array('user'=>$_POST['user']));
        if(isset($data[0])){
            $pass = $_POST['pass'];
            $ver = $data[0]['Pass'];
            if(password_verify($pass, $ver)){
                Database::query('INSERT INTO Auth (Token, Ip, Expire, User) values ( "'.$token.'", "'. $_SERVER['REMOTE_ADDR'].'", '.(time() + 60 * 60 * 24 * 2 ).', :user )', array('user'=>$_POST['user']));
                setcookie('CID', $token, time() + 60 * 60 * 24 * 2, '/', NULL, NULL, TRUE);
                sleep(1);
                header('location:./');
            }else{
                echo '<div class="error"><h4>Incorect Password</h4></div>';
            }
        }else{
            echo '<div class="error"><h4>Incorect Username</h4></div>';
        }
    }

    // login form 
    
    echo login();

    // Exit to stop any other output

    exit();
}

?>