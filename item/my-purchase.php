<?php
// env.php を読み込み
require_once '../env.php';
// lib/DB.php を読み込み
require_once '../lib/DB.php';

// SESSION開始
session_start();
session_regenerate_id(true);

// user 情報
$user_data = $_SESSION['my_shop']['user'];
// なければ リダイレクト
if(!empty($user_data)){
    header('Location: cart.php');
}
// cart 情報
$cart_items = $_SESSION['my_shop']['cart_items'];
if(!empty($cart_items)){
    header('Location: ../login/input.php');
}


// 仮 購入数
$amount = 1;

// sql
$sql = "INSERT INTO user_items(user_id, item_id, amount, total_price) VALUES(:user_id, :item_id, :amount, :total_price);";
// DB接続
$db = new DB();
$stmt = $db->pdo->prepare($sql);


// インサート
foreach($cart_items as $cart_item){
    $stmt->execute(
        [
            'user_id' => $user_data['id'],
            'item_id' => $cart_item['id'],
            'amount' => $amount,
            'total_price' => $cart_item['price'] * $amount,
        ]
    );
}

// リダイレクト
header('Location: index.php');
