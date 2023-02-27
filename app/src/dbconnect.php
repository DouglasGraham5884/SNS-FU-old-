<?php

require_once "env.php";
ini_set("display_errors", true);

// 1.ユーザ登録フォームの作成
// 2.登録完了画面の作成
// 3.バリデーションの作成
// 4.ユーザ登録ロジックの作成
// 5.ユーザ登録機能の実装

function connect() {

    $host = DB_HOST;
    $name = DB_NAME;
    $user = DB_USER;
    $pass = DB_PASS;

    $dsn = "mysql:host=$host;dbname=$name;charset=utf8mb4";

    try {
        
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        return $pdo;

    } catch(PDOException $e) {

        exit("接続失敗" . $e -> getMessage() . PHP_EOL);
        
    }
    
}