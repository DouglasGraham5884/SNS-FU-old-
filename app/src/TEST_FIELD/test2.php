<?php

require_once("_TEST_functions.php");

$result = test2();

var_dump($result);

if(!$result[0]) {
    echo "Hello!";
}