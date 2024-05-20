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
// サニタイズ
$posts = $db->sanitize(($_POST));

// itemsテーブルにインサート SQL
$sql = "UPDATE items SET 
        name = :name,
        code = :code,
        price = :price,
        stock = :stock
        WHERE id = :id;";

$stmt = $db->pdo->prepare($sql);
$stmt->execute($posts);

// 正常時 にリダイレクト
header("Location: edit.php?id={$posts['id']}");
?>