<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";
require_once "../classes/DataLogic.php";

validateToken();

// エラーメッセージ
$err = [];

$login_user = $_SESSION["login_user"];
$user_id = $login_user["user_id"];
$user_name = $login_user["user_name"];

$title = $_POST["title"] = h(filter_input(INPUT_POST, "title"));
$message = $_POST["message"] = nl2br(h(filter_input(INPUT_POST, "message")));

if(isset($_FILES)) {

    $file = $_FILES["file"];
    $file_name = [];
    for($i = 0; $i < count($file["name"]); $i++) {
        $file_name[] = basename($file["name"][$i]);
    }
    $file_tmp_name = $file["tmp_name"];
    $file_error = $file["error"];
    $file_size = $file["size"];

    $allow_ext = array("jpg", "jpeg", "png");
    $file_ext = [];
    for($i = 0; $i < count($file["name"]); $i++) {
        $file_ext[] = strtolower(pathinfo($file_name[$i], PATHINFO_EXTENSION));
    }
    
    $upload_dir = "/var/www/html/public/img/post_img/";
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
    foreach($file_ext as $ext) {
        if(!in_array($ext, $allow_ext)) {
            $ext_num++;
        }
    }
    if($ext_num > 0) {
        $err["file_ext"] = "画像ファイルのみ対応しています。";
    }
    if(count($file_name) > 4) {
        $err["file_quantity"] = "ファイルは4つ以下になるようにしてください。";
    }
    
}

// 画像の説明（ディスクリプション）
$description = isset($_POST["description"]) ? filter_input(INPUT_POST, "description", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) : NULL;
for($i = 0; $i < 4; $i++) {
    $description[] = h($description[$i]) ?? NULL;
}

// メッセージのバリデーション
if(!$title) {
    $err["title_emp"] = "タイトルを入力してください。";
}
if(strlen($title) > 50) {
    $err["title_emp"] = "タイトルは50字以内で入力してください。";
}
if(!$message) {
    $err["message_emp"] = "本文を入力してください。";
}
if(strlen($message) > 1000) {
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
    "file_path" => $file_path,
    "file_path" => $file_path
];

// アップロード成功時の処理
if(count($err) === 0) {

    // 投稿をする処理
    $result = DataLogic::insertPost($postData);
    $hasPost = $result[0];
    $lastPostId = $result[1];
    
    if($hasPost) {

        $dir_err_num_0 = 0;
        $dir_err_num_1 = 0;
    
        for($i = 0; $i < 4; $i++) {

            $fileData = [
                "user_id" => $user_id,
                "lastPostId" => $lastPostId,
                "save_file_name" => $save_file_name[$i],
                "file_path" => $file_path[$i],
                "description" => $description[$i],
                "file_tmp_name" => $file_tmp_name[$i]
            ];

            $result[] = DataLogic::uploadFile($fileData);

            if(!$result[0]) {
                $dir_err_num_0++;
            
            }
            if(!$result[1]) {
                $dir_err_num_1++;
            
            }

        }
        // echo $result;

        if($dir_err_num_0 > 0) {
            $err["file_err_0"] = "FILE_ERROR($dir_err_num_0)：予期しないエラーが発生しました。";
        }
        if($dir_err_num_1 > 0) {
            $err["file_err_1"] = "FILE_ERROR($dir_err_num_1)：予期しないエラーが発生しました。";
        }

    } else {

        $err["post_err"] = "POST_ERROR：予期しないエラーが発生しました。";

    }
    
}

// アップロード失敗時の処理
if(!$result) {
    echo "ファイルを保存できませんでした。";
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
            
            </div>
        </main>
    </div>
</body>