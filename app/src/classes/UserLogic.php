<?php

require_once "../dbconnect.php";

class UserLogic {

    /**
     * ユーザを登録する
     * @param array $userData
     * @return bool $result
     */
    public static function createUser($userData) {

        $result = false;

        $log = [];
        $log[] = NULL; // $user_id
        $log[] = $userData["user_name"]; // $user_name
        $log[] = 3; // $action
        $log[] = 1; // $target
        $log[] = 2; // $result
        $log[] = NULL; // $post_id
        
        $sql = "INSERT INTO users (user_name, email, password) VALUES (?, ?, ?);";

        // ユーザデータを配列に入れる
        $arr = [];
        $arr[] = $userData["user_name"];
        $arr[] = $userData["email"];
        $arr[] = password_hash($userData["password"], PASSWORD_DEFAULT);

        try {

            $stmt = connect() -> prepare($sql);
            $result = $stmt -> execute($arr);
            
            $logUser = self::getUserByUserName($userData["user_name"]);
            
            if($logUser) {
                
                $log[0] = $logUser["user_id"];
                $log[4] = 1;
                self::insert_log($log);

            } else {

                self::insert_log($log);
                
            }
            
            return $result;

        } catch(\Exception $e) {
            
            self::insert_log($log);
            
            return $e -> getMessage() . $e -> getLine();
            // return $result;
            
        }
        
    }

    public static function getUserByUserName($user_name) {

        $sql = "SELECT * FROM users WHERE user_name = ?";
        
        $arr = [];
        $arr[] = $user_name;
        
        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);
    
            $user = $stmt -> fetch();

            return $user;

        } catch(\Exeption $e) {

            return false;
            
        }
        
    }

    /**
     * ログイン処理
     * @param string $email
     * @param string $password
     * @return bool $result
     */
    public static function login($email, $password) {

        // 結果
        $result = false;

        $log = [];
        $log[] = NULL; // $user_id
        $log[] = NULL; // $user_name
        $log[] = 1; // $action
        $log[] = 1; // $target
        $log[] = NULL; // $result
        $log[] = NULL; // $post_id

        // ユーザをemailから検索して取得
        $user = self::getUserByEmail($email);

        // ユーザが見つからなかった場合
        if(!$user) {

            $_SESSION["message"] = "emailが一致しません";

            $log[4] = 2;
            self::insert_log($log);

            return $result;
            
        }

        // パスワードが一致しなかった場合
        if(!password_verify($password, $user["password"])) {

            $_SESSION["message"] = "パスワードが一致しません";

            $log[0] = $user["user_id"];
            $log[1] = $user["user_name"];
            $log[4] = 2;
            self::insert_log($log);

            return $result;
            
        }
            
        // ログイン成功
        session_regenerate_id(true);
        $_SESSION["login_user"] = $user;

        $log[0] = $user["user_id"];
        $log[1] = $user["user_name"];
        $log[4] = 1;
        self::insert_log($log);

        // $result = true;
        // return $result;
        return $result = true;
        
    }

    /**
     * emailからユーザを取得
     * @param string $email
     * @return array|bool $user|false
     */
    public static function getUserByEmail($email) {

        // SQLの準備
        // SQLの実行
        // SQLの結果を返す
        $sql = "SELECT * FROM users WHERE email = ?;";

        // emailを配列に入れる
        $arr = [];
        $arr[] = $email;

        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);

            // SQLの結果を返す
            $user = $stmt -> fetch();

            return $user;
            
        } catch(\Exception $e) {

            return false;
            
        }
        
    }

    /**
     * ログインチェック
     * @param void
     * @return bool $result
     */
    public static function checkLogin() {

        $result = false;

        if(
            isset($_SESSION["login_user"]) &&
            $_SESSION["login_user"]["user_id"] > 0
        ) {

            return $result = true;
            
        }

        return $result;
        
    }

    /**
     * ログアウト処理
     * down_session()を呼び出すだけ
     * @param int $user_id
     * @param string $user_name
     * @return void
     */
    public static function logout($user_id, $user_name) {

        $result = self::down_session();
        
        $log = [];
        $log[] = $user_id; // $user_id
        $log[] = $user_name; // $user_name
        $log[] = 2; // $action
        $log[] = 1; // $target
        $log[] = 2; // $result
        $log[] = NULL; // $post_id

        if($result) $log[4] = 1;
        
        self::insert_log($log);
        
    }

    /**
     * セッションを破壊する
     * @param void
     * @return void
     */
    public static function down_session() {

        $_SESSION = array();
        // $result = session_destroy();

        return true;
        // return $result;
        
    }

    /**
     * logsテーブルにログを挿入（格納）する
     * @param int $user_id 誰が行ったか（ID）
     * @param string $user_name 誰が行ったか
     * @param int $action 何を行ったか
     * @param int $target 何に対して行うのか
     * @param int $result 成功したかどうか
     * @param int $post_id POST系統の場合は必要
     * 
     * 2023/02/20 @param array $log なんかエラーが出て面倒だったので配列で受け取る
     * @return void
     */
    public static function insert_log($log) {

        $sql = "
            INSERT INTO
                logs (user_id, user_name, action, target, result, post_id)
            VALUES
                (?, ?, ?, ?, ?, ?);
        ";

        $arr = [];
        $arr[] = $log[0];
        $arr[] = $log[1];
        $arr[] = $log[2];
        $arr[] = $log[3];
        $arr[] = $log[4];
        $arr[] = $log[5];

        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);
            
        } catch(\Exeption $e) {

            $err = $e -> getMessage() . $e -> getLine();
            exit($err);
            
        }
        
    }
    
}