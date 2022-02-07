<?php
//This is my CONTROLLER

//turn on output buffering
ob_start();

//turn on error reporting
ini_set("display_errors", 1);
error_reporting(E_ALL);

//Start the session
session_start();
var_dump($_SESSION);

//require the autoload file
require_once ('vendor/autoload.php');

//create instance of Base class
$f3 = Base::instance();

//define a default route
$f3->route('GET /', function() {
    //echo "<h1>Hello World!</h1>";

    $view = new Template();
    echo $view->render('views/home.html');

});

//define a personal-information route
$f3->route('GET|POST /personal', function($f3) {
    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //TODO: Validate the data

        //add the data to the session variable
        $_SESSION['fName'] = $_POST['fName'];
        $_SESSION['lName'] = $_POST['lName'];
        $_SESSION['age'] = $_POST['age'];
        $_SESSION['genderOptions'] = $_POST['genderOptions'];
        $_SESSION['phone'] = $_POST['phone'];

        //redirect user to next page
        $f3->reroute('profile');

    }

    $view = new Template();
    echo $view->render('views/personal-info.html');
});

//define a profile route
$f3->route('GET|POST /profile', function($f3) {
    //If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        //TODO: Validate the data

        //add the data to the session variable
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['state'] = $_POST['state'];
        $_SESSION['seeking'] = $_POST['seeking'];
        $_SESSION['inputBio'] = $_POST['inputBio'];

        //redirect user to next page
        $f3->reroute('interests');

    }

    $view = new Template();
    echo $view->render('views/profile.html');

});

//define a interests route
$f3->route('GET|POST /interests', function($f3) {
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