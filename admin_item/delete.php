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

$posts = $db->sanitize(($_POST));

// itemsテーブルにインサート SQL
$sql = "DELETE FROM items WHERE id = :id";
$stmt = $db->pdo->prepare($sql);
$stmt->execute($posts);

// リダイレクト
header('Location: ./');
?>