<h3 class="dev"><a href="../index.html">DEV</a></h3>
<div class="route">ここまでのルートを表示</div>
<div class="header-box">
        <div class="container header-container">
            <header>
                <a class="logo" href="http://w-fu.com/"><img src="/public/img/fu-logo.png" alt="fu-logo" height="40px"></a>
                <nav class="header-nav">
                    <ul>
                        <li><a href="/public/home.php">Home</a></li>
                        <li><a href="/public/mypage.php">My Page</a></li>
                        <li><a href="/public/post_form.php">Post</a></li>
                        <li><a href="/public/login_form.php">Log in</a></li>
                    </ul>
                </nav>
            </header>
            <div class="navigate">
                <h1 class="page-title"><?= $place_name; ?> - FU SNS</h1>
                <?php if(isset($_SESSION["login_user"])) : ?>
                    <p><?= $login_user["user_name"]; ?>でログイン中</p>
                <?php else : ?>
                    <p>
                        ゲストモード：
                        <a class="login-link" href="./login_form.php">ログイン</a>
                    </p>
                <?php endif; ?>
            </div><!-- navigate -->
        </div><!-- container header-container -->
    </div><!-- header-box -->