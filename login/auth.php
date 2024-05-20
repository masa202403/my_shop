<?php
//設定ファイル「env.php」読み込み
require_once '../env.php';
require_once '../lib/DB.php';

// SESSION開始
session_start();
session_regenerate_id(true);

$db = new DB();
$pdo = $db->pdo;

//TODO: POSTデータ取得
$post = $_POST;
//TODO: セッション登録
$_SESSION = $post;

//TODO: Email検索
// $sql = 'SELECT email,password FROM users WHERE email = :email';

// 
$sql = 'SELECT * FROM users WHERE email = ?';
$email = $post['email'];
try{
    $stmt = $pdo->prepare($sql);
    // $stmt->bindParam(':email', $email);
    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    
}

//TODO: パスワードハッシュ検証
$is_scussess = false;
if($user){
    $pass = $post['password'];
    $is_scussess = password_verify($pass, $user['password']);
}

if($is_scussess){
    // SESSIONにユーザを登録
    $_SESSION['my_shop']['user'] = $user;
    //TODO: ログイン成功の場合、user/ にリダイレクト
    header('Location: ../user/');
}else{
    //TODO: ログイン失敗の場合、login/input.php にリダイレクト
    header('Location: input.php');
}
//TODO: セッション登録
//TODO: エラーメッセージをセッションに登録