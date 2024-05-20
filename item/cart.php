<?php
// env.php を読み込み
require_once '../env.php';

// lib/DB.php を読み込み
require_once '../lib/DB.php';

// セッション開始
session_start();
session_regenerate_id(true);

// item_id パラメータがあればカート追加
if (isset($_GET['item_id'])) {
    // カートに追加
    addCart($_GET['item_id']);
}

// カートデータの取得
$cart_items = loadCartItems();

function addCart($item_id)
{
    // DBに接続する
    $db = new DB();

    // DBから items.id を使って商品の取得
    $sql = "SELECT * FROM items WHERE id = :id;";
    $stmt = $db->pdo->prepare($sql);
    $stmt->execute(['id' => $item_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // 商品があればセッションに登録
    if ($item) {
        $_SESSION['my_shop']['cart_items'][$item_id] = $item;
    }
}

function loadCartItems()
{
    if (!empty($_SESSION['my_shop']['cart_items'])) {
        return $_SESSION['my_shop']['cart_items'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>


<body>
    <main class="container">
        <h2 class="p-2 text-center">ショッピングカート</h2>

        <div>
            <a href="./">商品一覧</a>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if ($cart_items) : ?>
                <?php foreach ($cart_items as $cart_item) : ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= $cart_item['name'] ?></h5>
                                <p class="card-text text-danger">&yen;<?= $cart_item['price'] ?></p>
                                <p class="card-text">
                                    <a href="remove.php?item_id=<?= $cart_item['id'] ?>">削除</a>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
        </div>

        <div class="mt-4 text-center">
            <?php if (!empty($cart_items)) : ?>
                <p>
                    この内容で購入しますか？
                </p>
                <form action="purchase.php" method="post">
                    <button class="btn btn-primary">購入</button>
                </form>
            <?php else: ?>
                <p>カートに商品がありません</p>
            <?php endif ?>
        </div>
    </main>
</body>

</html>