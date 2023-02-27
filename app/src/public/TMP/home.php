<?php

require_once "../functions.php";
require_once "../classes/UserLogic.php";
require_once "../classes/DataLogic.php";

$place_name = "Home";
if(isset($_SESSION["login_user"])) {
    $login_user = $_SESSION["login_user"];
}

if(!isset($_GET["page"])) {
    header("Location: /public/home.php/?page=1");
    return;
}

if(preg_match("/^[1-9][0-9]*$/", $_GET["page"])) {
    $page = (int)$_GET["page"];
} else {
    $page = 1;
}

// 全ユーザの投稿を取得
// $posts = DataLogic::showPostAll();
$postsLim = DataLogic::paging($page);
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
                                    <p class="post-user_id">
                                    Post_No.<span class="Post_No"><?= $post["post_id"]; ?></span>
                                    User:<span class="User"><?= $post["user_name"]; ?></span>
                                    </p>
                                    <h2 class="title">
                                        <?= $post["title"]; ?>
                                    </h2>
                                    <div class="small-container">
                                        <p class="message">
                                            <?= $post["message"]; ?>
                                        </p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li>投稿はまだありません。</li>
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
                    </div>
                    
                </div><!-- message-board -->
            </div><!-- message-board-box -->
            
        </div><!-- container -->
    </main>
</div><!-- main-box -->

<?php include "../_parts/_footer.php"; ?>