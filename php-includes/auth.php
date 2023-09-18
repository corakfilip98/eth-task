<?php
    session_start();
    if(!isset($_SESSION["username"])) {
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'login.php';
        $final_url = 'http://'.$host.$uri.'/'.$extra;
        header('Location:'.$final_url);
        exit();
    }
?>