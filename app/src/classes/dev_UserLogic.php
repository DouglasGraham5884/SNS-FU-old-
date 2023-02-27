<?php

require_once "../dbconnect.php";

class dev_UserLogic {

    /**
     * user_idからユーザ名を取得し、それをuser_nameへ挿入する（開発時使用）。
     * @param int $user_id
     * @return void
     */
    public static function insertUserName($user_id) {
        
        $user_name = self::getUserNameByUserId($user_id);
        
        $sql =  "
            UPDATE
                posts
            SET
                user_name = ?
            WHERE
                user_id = ?;
        ";

        $arr = [];
        $arr[] = $user_name;
        $arr[] = $user_id;

        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);
            
        } catch(\Exception $e) {

            exit($e -> getMessage());
            
        }
        
    }

    /**
     * user_idからuser_nameを取得しそれを返す（開発時使用）。
     * @param int $user_id
     * @return string $user_name["user_name"]
     */
    public static function getUserNameByUserId($user_id) {

        $sql = "SELECT user_name FROM users WHERE user_id = ?;";

        $arr = [];
        $arr[] = $user_id;

        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);
    
            $user_name = $stmt -> fetch();
    
            return $user_name["user_name"];
            
        } catch(\Exception $e) {

            exit();
            
        }
        
    }
    
}