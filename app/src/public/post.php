<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";
require_once "../classes/DataLogic.php";

$place_name = "Post";

validateToken();

// エラーメッセージ
$err = [];

$login_user = $_SESSION["login_user"];
$user_id = $login_user["user_id"];
$user_name = $login_user["user_name"];

$title = $_POST["title"] = h(filter_input(INPUT_POST, "title"));
// $message = $_POST["message"] = nl2br(filter_input(INPUT_POST, "message", FILTER_SANITIZE_SPECIAL_CHARS));
$message = $_POST["message"] = nl2br(h(filter_input(INPUT_POST, "message")));


if(
    isset($_FILES) &&
    !$_FILES["file"]["name"][0] == ""
) {

    $file = $_FILES["file"];
    $file_name = [];
    for($i = 0; $i < count($file["name"]); $i++) {
        $file_name[] = basename($file["name"][$i]);
    }
    $file_tmp_name = $file["tmp_name"];
    $file_error = $file["error"];
    $file_size = $file["size"];

    $allow_ext = array("jpg", "jpeg", "png", "svg", "raw");
    $file_ext = [];
    for($i = 0; $i < count($file["name"]); $i++) {
        $file_ext[] = strtolower(pathinfo($file_name[$i], PATHINFO_EXTENSION));
    }
    
    // $upload_dir = "/var/www/html/public/img/post_img/"; // 本番はこっち
    $upload_dir = "img/post_img/"; // サーバーに公開したら変更する
    $save_file_name = [];
    for($i = 0; $i < count($file["name"]); $i++) {
        $save_file_name[] = date("YmdHis") . $file_name[$i];
    }

    // ファイルのバリデーション
    $size_num = 0;
    foreach($file_size as $size) {
        $size_num = $size_num + $size;
    }
    if($size_num > 5242880 || in_array(2, $file_error)) {
        $err["file_size"] = "ファイルサイズの合計は5MB以下にしてください。";
    }
    $ext_num = 0;
    if(!$file_ext[0] == "") {
        foreach($file_ext as $ext) {
            if(!in_array($ext, $allow_ext)) {
                $ext_num++;
            }
        }
    }
    if($ext_num > 0) {
        $err["file_ext"] = "画像ファイルのみ対応しています。";
        $err["ext_err"] = $ext_num;
    }
    if(count($file_name) > 4) {
        $err["file_quantity"] = "ファイルは4つ以下になるようにしてください。";
    }
    
}

// 画像の説明（ディスクリプション）
// $description = isset($_POST["description"]) ? filter_input(INPUT_POST, "description", FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY) : NULL;
$description = isset($_POST["description"]) ? filter_input(INPUT_POST, "description", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) : NULL;
for($i = 0; $i < 4; $i++) {
    $description[] = $description[$i] ?? NULL;
}

// メッセージのバリデーション
if(!$title) {
    $err["title_emp"] = "タイトルを入力してください。";
}
if(!$title) {
    $err["title_len"] = "タイトルは50字以内で設定してください。";
}
if(!$message) {
    $err["message_emp"] = "本文を入力してください。";
}
if(!$message) {
    $err["message_len"] = "本文は1000字以内で入力してください。";
}

// エラー時の処理
if(count($err) > 0) {

    $_SESSION["err"] = $err;
    header("Location: ./post_form.php");

    return;
    
}
        
// $file_typeの準備
$file_type = [];
for($i = 0; $i < 4; $i++) {
    $file_type[] = $file_ext[$i] ?? NULL;
}
// $file_path準備
$file_path = [];
for($i = 0; $i < 4; $i++) {
    $file_path[] = isset($save_file_name[$i]) ? $upload_dir . $save_file_name[$i] : NULL;
}

$postData = [
    "user_id" => $user_id,
    "user_name" => $user_name,
    "title" => $title,
    "message" => $message,
    "file_type" => $file_type,
    "file_path" => $file_path
];

// アップロード成功時の処理
if(count($err) === 0) {

    // 投稿をする処理
    $result = DataLogic::insertPost($postData, $user_id, $user_name);
    $hasPost = $result[0];
    $lastPostId = $result[1];
    
    $dir_err_num = 0;

    if($hasPost) {
    
        if(
            isset($_FILES) &&
            !$_FILES["file"]["name"][0] == ""
        ) {

            for($i = 0; $i < count($file["name"]); $i++) {
                if(!$file_name[$i] == "") {
                    
                    // var_dump($file);
        
                    // if(count($file["name"]) === 1) {
                        
                    //     $fileData = [
                    //         "user_id" => $user_id,
                    //         "lastPostId" => $lastPostId,
                    //         "save_file_name" => $save_file_name,
                    //         "file_path" => $file_path,
                    //         "description" => $description,
                    //         "file_tmp_name" => $file_tmp_name
                    //     ];
                        
                    // } else {
                        
                    // }
                    
                    $fileData = [
                        "user_id" => $user_id,
                        "user_name" => $user_name,
                        "lastPostId" => $lastPostId,
                        "save_file_name" => $save_file_name[$i],
                        "file_path" => $file_path[$i],
                        "description" => $description[$i],
                        "file_tmp_name" => $file_tmp_name[$i]
                    ];
        
                    $result = DataLogic::uploadFile($fileData);
        
                    if(!$result) $dir_err_num++;
        
                }
            }
            
            // ファイルアップロード失敗時の処理
            if(!$result) {
                echo "ファイルを保存できませんでした。";
            }

        }

        if($dir_err_num > 0) {
            $err["dir_err"] = "Dir_Error($dir_err_num)：予期しないエラーが発生しました。";
        }

    } else {

        $err["db_err"] = "DB_Error：予期しないエラーが発生しました。";

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
                    <h2>Posted!</h2>
                <?php endif; ?>

                <a class="mypage-link" href="./mypage.php">マイページへ</a>
            
            </div><!-- container -->
        </main>
    </div><!-- main-box -->

</body>
</html>