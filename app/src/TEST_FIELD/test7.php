<?php

require_once "../classes/dev_DataLogic.php";
    
if(preg_match("/^[1-9][0-9]*$/", $_GET["page"])) {
    $page = (int)$_GET["page"];
} else {
    $page = 1;
}

$postsLim = dev_DataLogic::paging($page);
$total = $postsLim["total"];
$totalPages = $postsLim["totalPages"];
$from = $postsLim["from"];
$to = $postsLim["to"];
$posts = $postsLim["posts"];

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>TEST 7</title>
</head>
<body>

    <h1>コメント一覧</h1>
    <p>全<?= $total ?>件中<?= $from ?>～<?= $to ?>件を表示</p>

    <?php foreach($posts as $post) : ?>
        <p><?= $post["title"]; ?></p>
    <?php endforeach; ?>

    <!-- 前へ -->
    <?php if($page > 1) : ?>
        <a href="?page=<?= $page - 1; ?>"><<</a>
    <?php endif; ?>

    <?php for($i = 1; $i <= $totalPages; $i++) : ?>

        <?php if($page == $i) : ?>
            <strong>
            <a href="?page=<?= $i; ?>"><?= $i; ?></a>
            </strong>
        <?php else : ?>
            <a href="?page=<?= $i; ?>"><?= $i; ?></a>
        <?php endif; ?>
        
    <?php endfor; ?>

    <!-- 次へ -->
    <?php if($page < $totalPages) : ?>
        <a href="?page=<?= $page + 1; ?>">>></a>
    <?php endif; ?>

</body>
</html>