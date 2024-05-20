<?php
// SESSION開始
session_start();
session_regenerate_id(true);

// SESSIONにuser情報があれば削除
if(isset($_SESSION['my_shop']['user'])){
    unset($_SESSION['my_shop']['user']);
}

// ログインページにリダイレクト
header('Location: ../login/input.php');