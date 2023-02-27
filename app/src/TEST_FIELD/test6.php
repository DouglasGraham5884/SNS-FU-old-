<?php

try {

    // $box = 1;
    if($box) echo "\$box is exist.";
    // throw new Exception("e");
    
} catch(Exception $e) {
    
    
    // $at = date("Y-m-d H:i:s");
    // $message = $e -> getMessage();
    // $line = $e -> getLine();
    
    // $newLog = $at . "\t" . $message . "\t" . $line . PHP_EOL;
    
    // $fp = fopen(FILENAME, "a");
    // fwrite($fp, $newLog);
    // fclose($fp);
    
    
} finally {
    define("FILENAME", "./test6.txt");
    error_log("kore", 3, FILENAME);

}



// define("FILENAME", "");

// $at = data("Y-m-d H:i:s");
// $message = $e -> getMessage();
// $line = $e -> getLine();

// $newLog = $at . "\t" . $message "\t" . $line . PHP_EOL;

// $fp = fopen(FILENAME, "a");
// fwrite($fp, $newData);
// fclose($fp);
