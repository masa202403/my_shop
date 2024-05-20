<?php
// env.php を読み込み
require_once '../env.php';
// lib/DB.php を読み込み
require_once '../lib/DB.php';

// POST以外を拒否
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit;
}


// データベース接続
$db = new DB();
// 
$posts = $db->sanitize(($_POST));

// itemsテーブルにインサート SQL
$sql = "INSERT INTO items(code, name, price, stock) 
        VALUES(:code, :name, :price, :stock);";
$stmt = $db->pdo->prepare($sql);


// 異常時 入力画面へリダイレクト
try{
    $stmt->execute($posts);
}catch(\Throwable $th){
    header("Location: input.php");
    exit;
}

// 正常時 一覧画面にリダイレクト
header('Location: ./');
?>