<?php
//This is my index

//turn on output buffering
ob_start();

//turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require the autoload file
require_once ('vendor/autoload.php');
//Start the session
session_start();

/*echo("Session VarDump: ");
var_dump($_SESSION);
echo("\nPost VarDump: ");
var_dump($_POST);*/

//create instance of Base class
$f3 = Base::instance();
$con = new Controller($f3);
$dataLayer = new DataLayer();

//define a default route
$f3->route('GET /', function() {
    $GLOBALS['con']->home();

});

//Routing to admin LOGIN page
$f3->route('GET|POST /adminlogin', function($f3) {
    $GLOBALS['con']->adminLogin();
});

//Routing to admin PANEL page, post-login
$f3->route('GET|POST /adminpanel', function($f3) {
    $GLOBALS['con']->adminPanel();
});

//Routing to a new plan
$f3->route('GET /newplan', function($f3) {
    $GLOBALS['con']->newPlan();
});

//Routing for saving from a new plan
$f3->route('POST /newplan', function($f3) {
    $GLOBALS['con']->savePlan();
});

//Routing for saving from an existing plan
$f3->route('POST /savedplan*', function($f3) {
    $GLOBALS['con']->updateExistingPlan();
});

//Routing for retrieval of a saved plan
$f3->route('GET /savedplan*', function($f3) {
    $GLOBALS['con']->retrievePlan();
});

//run fat-free
$f3->run();

//send output to the browser
ob_flush();