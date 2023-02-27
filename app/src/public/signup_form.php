<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";

$place_name = "Signup Form";

// ログインしているかどうかのチェック
$result = UserLogic::checkLogin();

// ログインしていた場合マイページに戻す
if($result) {

    header("Location: mypage.php");

    return;
    
}

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>
    
<div class="main-box">
    <main>
        <div class="container">

            <h2>ユーザ登録フォーム</h2>
            <form action="register.php" method="POST">
                <p>
                    <label for="user_name">ユーザ名：</label>
                    <input type="text" name="user_name" id="user_name">
                </p>
                <p>
                    <label for="email">メールアドレス：</label>
                    <input type="email" name="email" id="email">
                </p>
                <p>
                    <label for="password">パスワード：</label>
                    <input type="password" name="password" id="password">
                </p>
                <p>
                    <label for="password_conf">パスワード確認：</label>
                    <input type="password" name="password_conf" id="password_conf">
                </p>
                <p>
                    <input type="submit" value="新規登録">
                </p>
                <input type="hidden" name="token" value="<?= h(setToken()); ?>">
            </form>
        
            <a class="login-link" href="./login_form.php">ログイン</a>
            
        </div><!-- container -->
    </main>
</div><!-- main-box -->

</body>
</html>