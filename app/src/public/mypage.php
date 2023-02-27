<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";
require_once "../classes/DataLogic.php";

$place_name = "My Page";

// ログインしていなかったら新規登録画面へ返す
$result = UserLogic::checkLogin();

if(!$result) {

    $_SESSION["message"] = "ログインしてください";
    header("Location: login_form.php");

    return;
    
}

$login_user = $_SESSION["login_user"];

if(
    isset($_GET["page"]) &&
    preg_match("/^[1-9][0-9]*$/", $_GET["page"])
) {
    $page = (int)$_GET["page"];
} else {
    $page = 1;
}

// ユーザの投稿を取得
// $posts = DataLogic::showPostByUserId($login_user["user_id"]);
$postsLim = DataLogic::paging($page, $login_user["user_id"], 2);
$total = $postsLim["total"];
$totalPages = $postsLim["totalPages"];
$from = $postsLim["from"];
$to = $postsLim["to"];
$posts = $postsLim["posts"];

?>

<?php include "../_parts/_head1.php"; ?>
<title><?= $place_name; ?> - FU SNS</title>
<?php include "../_parts/_head2.php"; ?>

<body>
    
<?php include "../_parts/_header.php"; ?>

<div class="main-box">
    <main>
        
        <div class="overlay">
            <span class="material-icons" id="close">close</span>
        </div><!-- overlay -->

        <div class="container">

            <div class="message-board-box board-box-base">
            <h2>投稿一覧（全<?= $total ?>件中<?= $from ?>～<?= $to ?>件を表示）</h2>
                <div class="message-board board-base">
                    <ul>
                        <?php if($posts) : ?>
                            
                            <?php foreach($posts as $post) : ?>

                                <li>
                                    <p class="created_at">
                                        created_at[ <?= $post["created_at"] ?> ]
                                    </p>
                                    <p class="post_id">
                                        Post_No.<span class="Post_No"><?= $post["post_id"]; ?></span>
                                    </p>
                                    <h2 class="title">
                                        <?= $post["title"]; ?>
                                    </h2>
                                    <div class="small-container">
                                        <p class="message">
                                            <?= $post["message"]; ?>
                                        </p>

                                        <!-- 添付写真 -->
                                        <div class="post-imgs">
                                            <?php if(!is_null($post["file_path_01"])) : ?>
                                                <img class="post-img" src="<?= $post["file_path_01"] ?>" alt="写真１">
                                            <?php endif; ?>
                                            <?php if(!is_null($post["file_path_02"])) : ?>
                                                <img class="post-img" src="<?= $post["file_path_02"] ?>" alt="写真２">
                                            <?php endif; ?>
                                            <?php if(!is_null($post["file_path_03"])) : ?>
                                                <img class="post-img" src="<?= $post["file_path_03"] ?>" alt="写真３">
                                            <?php endif; ?>
                                            <?php if(!is_null($post["file_path_04"])) : ?>
                                                <img class="post-img" src="<?= $post["file_path_04"] ?>" alt="写真４">
                                            <?php endif; ?>
                                        </div>

                                    </div><!-- small-container -->
                                </li>
                            
                            <?php endforeach; ?>
                            
                        <?php elseif($login_user) : ?>

                            <li>まだ投稿がありません！</li>

                        <?php endif; ?>
                    </ul>

                    <div class="page-selecter">
                        
                        <!-- 前へ -->
                        <?php if($page > 1) : ?>
                            <a href="?page=<?= $page - 1; ?>"><</a>
                        <?php endif; ?>

                        <?php for($i = 1; $i <= $totalPages; $i++) : ?>

                            <?php if($page == $i) : ?>
                                <strong class="current-page">
                                <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                                </strong>
                            <?php else : ?>
                                <a href="?page=<?= $i; ?>"><?= $i; ?></a>
                            <?php endif; ?>
                            
                        <?php endfor; ?>
                        
                        <!-- 次へ -->
                        <?php if($page < $totalPages) : ?>
                            <a href="?page=<?= $page + 1; ?>">></a>
                        <?php endif; ?>

                    </div><!-- page-selecter -->
                </div><!-- message-board board-base -->
            </div><!-- message-board-box board-box-base -->

            <form id="logout" action="./logout.php" method="POST">
                <input type="submit" name="logout" value="ログアウト">
            </form>
            
        </div><!-- container -->
    </main>
</div><!-- main-box -->

<?php include "../_parts/_footer.php"; ?>