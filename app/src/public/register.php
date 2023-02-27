<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";

$place_name = "Register";

validateToken();

// エラーメッセージ
$err = [];

$user_name = $_POST["user_name"] = h(filter_input(INPUT_POST, "user_name"));
$email = $_POST["email"] = h(filter_input(INPUT_POST, "email"));
$password = $_POST["password"] = h(filter_input(INPUT_POST, "password"));
$password_conf = $_POST["password_conf"] = h(filter_input(INPUT_POST, "password_conf"));

// バリデーション
if(!$user_name) {
    $err[] = "ユーザ名を記入してください。";
}
if(!$email) {
    $err[] = "メールアドレスを記入してください。";
}

if(!preg_match("/\A[a-z\d]{8,100}+\z/i", $password)) {
    $err[] = "パスワードは英数字8文字以上100文字以下にしてください。";
}

if($password !== $password_conf) {
    $err[] = "確認用パスワードと異なっています。";
}

if(count($err) === 0) {

    // ユーザを登録する処理
    $hasCreated = UserLogic::createUser($_POST);
    
    if(!$hasCreated) {

        $err[] = "登録に失敗しました。";
        
    }
    
}

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>

    <div class="main-box">
        <main>
            <div class="container">

                <?php if(count($err) > 0) : ?>
            
                    <?php foreach($err as $e) : ?>
            
                        <p><?= $e ?></p>
            
                    <?php endforeach; ?>
                    
                <?php else : ?>
            
                    <p>ユーザ登録が完了しました。</p>
                    
                <?php endif; ?>
            
                <a href="./signup_form.php">戻る</a>
                
            </div><!-- container -->
        </main>
    </div><!-- main-box -->

</body>
</html>