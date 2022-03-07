<?php
//This is my CONTROLLER

//turn on output buffering
ob_start();

//turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);

//Start the session
session_start();
//var_dump($_SESSION);

//require the autoload file
require_once ('vendor/autoload.php');
require ('model/data-layer.php');
require('model/validator.php');

//create instance of Base class
$f3 = Base::instance();
$con = new Controller($f3);

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
    $email = "";
    $state = "";
    $seeking = "";
    $inputBio = "";
    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //add the data to the session variable
        $email = $_POST['email'];
        $state = $_POST['state'];
        $seeking = $_POST['seeking'];
        $inputBio = $_POST['inputBio'];
        //TODO: Validate the data

        if(validEmail($email)){
            $f3->set('SESSION.email', $email);
        }
        else{
            $f3->set('errors["email"]', 'Please enter a valid email address');
        }

        $f3->set('SESSION.state', $state);
        $f3->set('SESSION.seeking', $seeking);
        $f3->set('SESSION.inputBio', $inputBio);

        //redirect user to next page
        if(empty($f3->get('errors'))){
            $f3->reroute('interests');
        }
    }

    $view = new Template();
    echo $view->render('views/profile.html');

});

//define a interests route
$f3->route('GET|POST /interests', function($f3) {

    //get interests from the model and add to F3 hive
    $f3->set('indoor', getIndoor());
    $f3->set('outdoor', getOutdoor());
    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //TODO: Validate the data

        //add the data to the session variable
        if(isset($_POST['interests'])){
            $_SESSION['interests'] = implode(", ", $_POST['interests']);

        }
        else{
            $_SESSION['interests'] = "None selected";
        }

        //redirect user to next page
        $f3->reroute('summary');

    }

    $view = new Template();
    echo $view->render('views/interests.html');

});

//Define a summary route
$f3->route('GET /summary', function() {
    //echo "<h1>My Diner</h1>";

    $view = new Template();
    echo $view->render('views/profile-summary.html');

    //clear the session data
    session_destroy();
});

//run fat-free
$f3->run();

//send output to the browser
ob_flush();