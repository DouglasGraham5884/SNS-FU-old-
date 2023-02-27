<?php

/**
 * XSS対策：エスケープ処理
 * @param string $str 対象の文字列
 * @return string 処理された文字列
 */
function h($str) {

    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
    
}

/**
 * CSRF対策：ワンタイムトークン
 * @param void
 * @return string $token
 */
function setToken() {
    // 旧タイプのトークンシステムを使用する場合はFU2以前のcreateToken（非推奨？）
    // 全体をif(!isset($_SESSION["csrf_token"])) {}で囲むかどうかの違い

    // $token = bin2hex(random_bytes(32));
    // $_SESSION["token"] = $token;
    $_SESSION["token"] = bin2hex(random_bytes(32));

    return $_SESSION["token"];
    
}

/**
 * バリデーション
 * @param void
 * @return void
 */
function validateToken() {
    // 旧タイプのトークンシステムを使用する場合はFU2以前のcreateToken（非推奨？）
    // 最後にunset($_SESSION["token"])をするかどうかの違い

    // $token = filter_input(INPUT_POST, "token");
    
    // if(empty($_SESSION["token"]) || $_SESSION["token"] !== $token) {}
    if(
        empty($_SESSION["token"]) ||
        $_SESSION["token"] !== filter_input(INPUT_POST, "token")
    ) {

        exit("Invalid post request：不正なアクセスです。");

    }

    unset($_SESSION["token"]);
    
}

/**
 * $_SESSION["err"]を削除する
 * @param void
 * @return void
 */
function err_destroy() {
    
    $_SESSION["err"] = array();
    
}

function token_destroy() {

    unset($_SESSION["token"]);
    
}

session_start();