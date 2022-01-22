<?php

//turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);

//require the autoload file
require_once ('vendor/autoload.php');

//create instance of Base class
$f3 = Base::instance();

//define a default route
$f3->route('GET /', function() {
    echo "<h1>Hello World!</h1>";
});

//run fat-free
$f3->run();