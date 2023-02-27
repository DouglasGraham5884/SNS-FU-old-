<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";

$place_name = "Logout";

// ログインしていなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if(!$result) {

    $_SESSION["message"] = "ログインしてください";
    
    header("Location: login_form.php");

    return;
    
}

$logout = filter_input(INPUT_POST, "logout");

if(!$logout) {

    exit("不正なリクエストです");
    
}

$user_id = $_SESSION["login_user"]["user_id"];
$user_name = $_SESSION["login_user"]["user_name"];

// ログインしているか判定し、セッションが切れていたらログインするように指示する
// $result = UserLogic::checkLogin();

// if(!$result) {

//     exit("セッションが切れました。再度ログインしてください。");
    
// }

// ログアウト
UserLogic::logout($user_id, $user_name);

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>

    <div class="main-box">
        <main>
            <div class="container">
                
                <h2>ログアウト完了</h2>
                <p>ログアウトしました</p>
                <a href="./login_form.php">ログイン画面へ</a>

            </div><!-- container -->
        </main>
    </div><!-- main-box -->

</body>
</html>