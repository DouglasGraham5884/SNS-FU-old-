<?php

require_once "../dbconnect.php";

class DataLogic {
    
    /**
     * 投稿の一覧を表示する
     * @param void
     * @return array|bool $postData|false
     */
    public static function showPostAll() {

        $result = false;
        
        $sql = "SELECT * FROM posts";

        try {

            $stmt = connect() -> query($sql);
            $result = $stmt -> fetchAll();

            return $result;

        } catch(\Exception $e) {
            
            return $result;
            
        }
        
    }
    
    /**
     * 特定のユーザの投稿の一覧を表示する
     * @param int $user_id
     * @return array|bool $postData|false
     */
    public static function showPostByUserId($user_id) {

        $result = false;
        
        $sql = "SELECT * FROM posts WHERE user_id = ?";

        $arr = [];
        $arr[] = $user_id;
        
        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);

            $result = $stmt -> fetchAll();

            return $result;

        } catch(\Exception $e) {
            
            return $result;
            
        }
        
    }

    /**
     * 投稿をする処理
     * 投稿の結果とpost_idを返す
     * @param array $postData
     * @return array $result
     */
    public static function insertPost($postData, $user_id, $user_name) {

        $result = [
            false,
            NULL
        ];

        $log = [];
        $log[] = $user_id; // $user_id
        $log[] = $user_name; // $user_name
        $log[] = 3; // $action
        $log[] = 2; // $target
        $log[] = 2; // $result
        $log[] = NULL; // $post_id

        $sql = "
            INSERT INTO
                posts (
                    user_id,
                    user_name,
                    title,
                    message,
                    file_type_01,
                    file_type_02,
                    file_type_03,
                    file_type_04,
                    file_path_01,
                    file_path_02,
                    file_path_03,
                    file_path_04
                )
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        $arr = [];
        $arr[] = $postData["user_id"];
        $arr[] = $postData["user_name"];
        $arr[] = $postData["title"];
        $arr[] = $postData["message"];

        foreach($postData["file_type"] as $type) {
            $arr[] = $type;
        }
        foreach($postData["file_path"] as $path) {
            $arr[] = $path;
        }

        try {

            $stmt = connect() -> prepare($sql);
            $result[0] = $stmt -> execute($arr);
            $log[4] = 1;
            
        } catch(\Exeption $e) {

            return $result;
            
        }

        $lastPost = self::showLastPost();
        if($lastPost) {

            $result[1] = $lastPost["post_id"];
            $log[5] = $result[1];
        }

        self::insert_log($log);
        
        return $result;
        
    }

    /**
     * 全投稿の中で最新の投稿を返す
     * @param void
     * @return array|bool $lastPost|false
     */
    public static function showLastPost() {

        $sql = "
            SELECT * FROM
                posts
            ORDER BY 
                post_id
            DESC LIMIT 1;
        ";

        try {

            $stmt = connect() -> prepare($sql);
            $result = $stmt -> execute();

            $lastPost = $stmt -> fetch();

            return $lastPost;

        } catch(\Exception $e) {

            return false;
            
        }
        
    }

    /**
     * ファイルをアップロードする処理
     * $insertFileを呼び出してDBへのファイルデータ挿入を同時に行う
     * @param array $fileData
     * @return bool $result
     */
    public static function uploadFile($fileData) {

        $result = false;

        $log = [];
        $log[] = $fileData["user_id"]; // $user_id
        $log[] = $fileData["user_name"]; // $user_name
        $log[] = 3; // $action
        $log[] = 3; // $target
        $log[] = 2; // $result
        $log[] = $fileData["lastPostId"]; // $post_id

        $file_tmp_name = $fileData["file_tmp_name"];
        $file_path = $fileData["file_path"];
            
        $hasUpload = move_uploaded_file($file_tmp_name, $file_path);

        if($hasUpload) {

            $result = self::insertFile($fileData);
            if($result) $log[4] = 1;
            
        }

        self::insert_log($log);

        return $result;
        
    }

    /**
     * ファイルデータをDBに挿入（格納）する
     * @param int $user_id
     * @param string $file_name
     * @param string $file_path
     * @param string $description
     * @return bool $result
     */
    public static function insertFile($fileData) {

        $result = false;

        $sql = "
            INSERT INTO 
                images (user_id, post_id, file_name, file_path, description)
            VALUES
                (?, ?, ?, ?, ?);
        ";
        
        $arr = [];
        $arr[] = $fileData["user_id"];
        $arr[] = $fileData["lastPostId"];
        $arr[] = $fileData["save_file_name"];
        $arr[] = $fileData["file_path"];
        $arr[] = $fileData["description"];

        try {
    
            $stmt = connect() -> prepare($sql);
            $result = $stmt -> execute($arr);
    
            return $result;

        } catch(\Exception $e) {

            return $result;
            
        }

    }

    /**
     * ページング機能を追加
     * @param int $page 現在のページ
     * @param int|null $user_id
     * @param int $mode HOME（全ユーザか）MYPAGE（ログインユーザだけか）の選択。"1"=全ての投稿, "2"=ログインユーザのみ
     * @return array $result
     */
    public static function paging($page, $user_id, $mode) {

        define("ONE_PAGE_VOL", 5);
        
        // $posts = DataLogic::showPostAll();
        $offset = ONE_PAGE_VOL * ($page - 1);
        $total = self::getTotal($user_id, $mode);
        $totalPages = ceil($total / ONE_PAGE_VOL);
        
        $from = $offset + 1;
        $to = ($offset + ONE_PAGE_VOL) < $total ? ($offset + ONE_PAGE_VOL) : $total;
        
        $posts = self::showPostLimit($offset, ONE_PAGE_VOL, $user_id, $mode);
        
        $result = [
            "total" => $total,
            "totalPages" => $totalPages,
            "from" => $from,
            "to" => $to,
            "posts" => $posts
        ];

        return $result;

    }

    /**
     * 投稿の総数を取得して返す
     * @param int|null $user_id
     * @param int $mode 上のfunctionの説明見て
     * @return int|bool $result
     */
    public static function getTotal($user_id, $mode) {

        $result = false;

        $arr = [];
        switch($mode) {
            case 1:
                $sql = "SELECT COUNT(*) FROM posts";
                break;
            case 2:
                $sql = "SELECT COUNT(*) FROM posts WHERE user_id = ?";
                $arr[] = $user_id;
                break;
        }

        try {

            $stmt = connect() -> prepare($sql);
            switch($mode) {
                case 1:
                    $stmt -> execute();
                    break;
                case 2:
                    $stmt -> execute($arr);
                    break;
            }

            $resultArray = $stmt -> fetch();
            $result = $resultArray["COUNT(*)"];

            return $result;
            
        } catch(\Exeption $e) {

            return $result;
            
        }
        
    }

    /**
     * 最新数件の投稿を取得し返す
     * @param int $offset どこから数えるか
     * @param int $count 何個分数えるか
     * @param int|null $user_id
     * @param int $mode 上のfunctionの説明見て
     * @return array|bool $result
     */
    public static function showPostLimit($offset, $count, $user_id, $mode) {

        $result = false;

        switch($mode) {
            case 1:
                $sql = "SELECT * FROM posts LIMIT :offset, :count";
                break;
            case 2:
                $sql = "SELECT * FROM posts WHERE user_id = :user_id LIMIT :offset, :count";
                break;
        }

        try {

            $stmt = connect() -> prepare($sql);
            
            switch($mode) {
                case 1:
                    $stmt -> bindValue("offset", $offset, PDO::PARAM_INT);
                    $stmt -> bindValue("count", $count, PDO::PARAM_INT);
                    break;
                case 2:
                    $stmt -> bindValue("user_id", $user_id, PDO::PARAM_STR);
                    $stmt -> bindValue("offset", $offset, PDO::PARAM_INT);
                    $stmt -> bindValue("count", $count, PDO::PARAM_INT);
                    break;
            }
            
            $stmt -> execute();

            $result = $stmt -> fetchAll();

            return $result;
            
        } catch(\Exeption $e) {

            return $result;
            
        }
        
    }

    /**
     * 23/02/27 description表示をしばらく断念することになったので要らない子になりました。
     * getDescriptionByFilePathを呼び出してdescription（写真の説明）を返す
     * @param string $path
     * @return string description
     */
    public static function showDescription($path) {

        $image = self::getDescriptionByFilePath($path);

        return $image["description"];
        
    }

    /**
     * 23/02/27 同上。
     * file_pathを使用してimageの情報を返す
     * @param string $path
     * @return array|bool $result
     */
    public static function getDescriptionByFilePath($path) {

        $result = false;

        $sql = "SELECT * FROM images WHERE file_path = ?;";

        $arr = [];
        $arr[] = $path;

        try {

            $stmt = connect() -> prepare($sql);
            $stmt -> execute($arr);

            $result = $stmt -> fetch();

            return $result;
            
        } catch(\Exeption $e) {

            return $result;
            
        }
        
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