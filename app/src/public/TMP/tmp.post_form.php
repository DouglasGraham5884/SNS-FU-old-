<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";
require_once "../classes/DataLogic.php";

$place_name = "Post Form";

// ログインしていなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if(!$result) {

    $_SESSION["message"] = "ログインしてください";
    
    header("Location: login_form.php");

    return;
    
}

$login_user = $_SESSION["login_user"];

$user_id = $login_user["user_id"];
$user_name = $login_user["user_name"];

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>

    <?php include "../_parts/_header.php"; ?>

    <div class="main-box">
        <main>
            <div class="container">

            <?php if(isset($err["file_err_0"])) : ?>
                <p><?= $err["file_err_0"]; ?></p>
            <?php endif; ?>
            <?php if(isset($err["file_err_1"])) : ?>
                <p><?= $err["file_err_1"]; ?></p>
            <?php endif; ?>
            <?php if(isset($err["post_err"])) : ?>
                <p><?= $err["post_err"]; ?></p>
            <?php endif; ?>

                <div class="post-board-box board-box-base">
                    <div class="post-board board-base">
                        
                        <form enctype="multipart/form-data" action="./post.php" method="POST">
                        
                            <!-- HIDDEN -->
                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="user_name" value="<?= $user_name ?>">
                            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
                            <input type="hidden" name="token" value="<?= h(setToken()); ?>">
                            <!-- /HIDDEN -->

                            <!-- TEXT -->
                            <!-- VALIDATION -->
                            <?php if(isset($err["title_emp"])) : ?>
                                <p><?= $err["title_emp"]; ?></p>
                            <?php endif; ?>
                            <?php if(isset($err["title_len"])) : ?>
                                <p><?= $err["title_len"]; ?></p>
                            <?php endif; ?>
                            <!-- TITLE -->
                            <label for="title">タイトル</label>
                            <input type="text" name="title" autofocusplaceholder="タイトルを入力してください（必須）" required id="title">
                            <!-- MESSAGE -->
                            <!-- VALIDATION -->
                            <?php if(isset($err["message_emp"])) : ?>
                                <p><?= $err["message_emp"]; ?></p>
                            <?php endif; ?>
                            <?php if(isset($err["message_len"])) : ?>
                                <p><?= $err["message_len"]; ?></p>
                            <?php endif; ?>
                            <label for="message">本文</label>
                            <textarea name="message" placeholder="本文を入力してください（必須）" required id="message"></textarea>
                            <!-- /TEXT -->

                            <!-- FILE -->
                            <!-- VALIDATION -->
                            <?php if(isset($err["file_size"])) : ?>
                                <p><?= $err["file_size"]; ?></p>
                            <?php endif; ?>
                            <?php if(isset($err["file_ext"])) : ?>
                                <p><?= $err["file_ext"]; ?></p>
                            <?php endif; ?>
                            <?php if(isset($err["file_quantity"])) : ?>
                                <p><?= $err["file_quantity"]; ?></p>
                            <?php endif; ?>
                            <!-- FILE SELECT -->
                            <input type="file" name="file[]" accept="image/*" multiple>

                            <!-- DESCRIPTION -->
                            <?php for($i = 0; $i < 4; $i++) : ?>
                                <input type="text" name="description[]" placeholder="<?= $i + 1 ?>つ目の画像の説明（省略可）">
                            <?php endfor; ?>
                            
                            <!-- SUBMIT -->
                            <input type="submit" value="Post">
                            
                        </form>

                    </div><!-- post-board board-base -->
                </div><!--post-board-box board-box-base -->

            </div><!-- container -->
        </main>
    </div><!-- main-box -->

    <?php  include "../_parts/_footer.php"; ?>
    
</body>