<?php

// var_dump($_POST);
$data = filter_input(INPUT_POST, "text", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
var_dump($data);