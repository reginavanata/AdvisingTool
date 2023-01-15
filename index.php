<?php
//This is my CONTROLLER

//turn on output buffering
ob_start();

//turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);

//require the autoload file
require_once ('vendor/autoload.php');
//Start the session
session_start();


var_dump($_SESSION);
//var_dump($_POST);

//create instance of Base class
$f3 = Base::instance();
$con = new Controller($f3);
$dataLayer = new DataLayer();

//define a default route
$f3->route('GET /', function() {
    $GLOBALS['con']->home();

});

//define a personal-information route
$f3->route('GET|POST /personal', function($f3) {
    $GLOBALS['con']->personal();
});

//define a profile route
$f3->route('GET|POST /profile', function($f3) {
    $GLOBALS['con']->profile();
});

//define a interests route
$f3->route('GET|POST /interests', function($f3) {
    $GLOBALS['con']->interests();

});

//Define a summary route
$f3->route('GET /summary', function() {
    $GLOBALS['con']->summary();
});

//Define an admin route
$f3->route('GET /admin', function() {

    $GLOBALS['con']->admin();
});

//define a new pan route
$f3->route('GET /newplan', function($f3) {
    $GLOBALS['con']->newPlan();
});

//define a personal-information route
$f3->route('POST /newplan', function($f3) {
    $GLOBALS['con']->savePlan();
});

//define a personal-information route
$f3->route('GET /savedplans', function($f3) {
    $GLOBALS['con']->retrievePlan();
});


//run fat-free
$f3->run();

//send output to the browser
ob_flush();