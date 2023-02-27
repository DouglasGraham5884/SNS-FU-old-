<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";

$place_name = "Login";

// エラーメッセージ
$err = [];

$_POST["email"] = $email = h(filter_input(INPUT_POST, "email"));
$_POST["password"] = $password = h(filter_input(INPUT_POST, "password"));

// バリデーション
if(!$email) {
    $err["email"] = "メールアドレスを記入してください";
}
if(!$password) {
    $err["password"] = "パスワードを記入してください";
}

// エラー時の処理
if(count($err) > 0) {

    $_SESSION = $err;
    header("Location: ./login_form.php");
    
    return;
    
}

// ログイン成功時の処理
$result = UserLogic::login($email, $password);

// ログイン失敗時の処理
if(!$result) {

    header("Location: ./login_form.php");

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

                <h2>ログイン完了</h2>
                <a class="mypage-link" href="./mypage.php">マイページへ</a>

            </div><!-- container -->
        </main>
    </div><!-- main-box -->

</body>
</html>