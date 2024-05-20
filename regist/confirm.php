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
session_start();
// セッションハイジャック対策
session_regenerate_id(true);

// SESSIONにPOSTデータ保存
$_SESSION['my_shop']['regist'] = $_POST;

// サニタイズしたものを変数に代入
$posts = sanitize($_POST);


// バリデーション
if(isset($_SESSION['my_shop']['errors'])){
    unset($_SESSION['my_shop']['errors']);
}
$errors = validate($posts);

// Email重複チェック
$db = new DB();
$sql = "SELECT * FROM users WHERE email = '{$posts['email']}';";
$stmt = $db->pdo->prepare($sql);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);
if($user) $errors['email'] = "Emailは既に登録されています";

if($errors){
    $_SESSION['my_shop']['errors'] = $errors;
    header('Location: input.php');
    exit;
}


/**
 *  サニタイズ
 */
function sanitize($array){
    if(!is_array($array)) return [];
    foreach($array as $key => $value){
        $array[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $array;
}

/**
 *  バリデーション関数
 */
function validate($posts){
    // Validation
    $errors = [];
    if(empty($posts['name'])){
        $errors['name'] = '名前を入力してください';
    }
    if(empty($posts['email'])){
        $errors['email'] = 'メールアドレスを入力してください';
    }
    if(empty($posts['password'])){
        $errors['password'] = 'パスワードを入力してください';
    }
    return $errors;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <main class="m-auto w-50">
        <h2 class="p-2 text-center">会員登録確認</h2>
        <form action="add.php" method="post">
            <div class="form-group mt-3">
                <label class="form-label" for="">名前</label>
                <p><?= $posts["name"] ?></p>
            </div>

            <div class="form-group mt-3">
                <label class="form-label" for="">Email</label>
                <p><?= $posts["email"] ?></p>
            </div>

            <div class="d-flex mt-3">
                <button class="btn btn-primary w-50 me-1">登録</button>
                <a href="./input.php" class="btn btn-outline-primary w-50">戻る</a>
            </div>
        </form>
    </main>
</body>
</html>