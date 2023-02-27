<?php

require_once "../dbconnect.php";

function test2() {

    $result = [];
    $result[] = false;
    $result[] = 9;

    return $result;
    
}

function test3($fileDatas) {
    
    $data = $fileDatas["file"];

    $file_name = $data["name"];
    
    for($i = 0; $i < count($data["name"]); $i++) {

        echo nl2br(basename($file_name[$i]) . PHP_EOL);
        echo nl2br($data["type"][$i] . PHP_EOL);
        echo nl2br($data["size"][$i] . PHP_EOL);
        echo "<br>";
        
    }
    
}

function test8($path) {

    $result = false;
    
    $sql = "
        SELECT
            *
        FROM
            images
        WHERE
            file_path = :path
        LIMIT
            50;
    ";
    
    try {

        $stmt = connect() -> prepare($sql);
        $stmt -> bindValue("path", $path, PDO::PARAM_STR);
        $stmt -> execute();

        $result = $stmt -> fetch();

        return $result;
        
    } catch(\Exeption $e) {

        return $result;
        
    }
    
}