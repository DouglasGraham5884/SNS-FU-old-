<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";

$place_name = "Login Form";

// ログインしているかどうかのチェック
$result = UserLogic::checkLogin();

// ログインしていた場合マイページに戻す
if($result) {

    header("Location: ./mypage.php");

    return;
    
}

$err = $_SESSION;

// セッション削除
UserLogic::down_session();

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>

<div class="main-box">
    <main>
        <div class="container">

            <h2>ログインフォーム</h2>
            
            <?php if(isset($err["message"])) : ?>
                <p><?= $err["message"]; ?></p>
            <?php endif; ?>
            
            <form action="./login.php" method="POST">
        
                <p>
                    <label for="email">メールアドレス：</label>
                    <input type="email" name="email">
                    <?php if(isset($err["email"])) : ?>
                        <p><?= $err["email"]; ?></p>
                    <?php endif; ?>
                </p>
        
                <p>
                    <label for="password">パスワード：</label>
                    <input type="password" name="password">
                    <?php if(isset($err["password"])) : ?>
                        <p><?= $err["password"]; ?></p>
                    <?php endif; ?>
                </p>
        
                <p>
                    <input type="submit" value="ログイン">
                </p>
        
            </form>
        
            <a class="signup-link" href="./signup_form.php">新規登録はこちら</a>
            
        </div><!-- container -->
    </main>
</div><!-- main-box -->

</body>
</html>
                    