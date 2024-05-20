<?php
// env.php を読み込み
require_once '../env.php';
// lib/DB.php を読み込み
require_once '../lib/DB.php';

// POST以外を拒否
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit;
}
// セッション開始
// セッションハイジャック対策
session_regenerate_id(true);

// SESSIONデータを変数に代入
$regist = $_SESSION['my_shop']['regist'];


// パスワードのハッシュ化
$regist['password'] = password_hash($regist['password'], PASSWORD_DEFAULT);

// データベース接続
$db = new DB();
// SQL
$sql = "INSERT INTO users(name, email, password) 
        VALUES(:name, :email, :password);";
$stmt = $db->pdo->prepare($sql);


// 異常時 入力画面へリダイレクト
try{
    $stmt->execute($regist);
}catch(\Throwable $th){
    header("Location: input.php");
    exit;
}

// 正常時 完了画面にリダイレクト
header('Location: complete.php');
?>