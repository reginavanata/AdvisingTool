<?php

class Controller
{
    private $_f3; //f3 object

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function debug() {
        //displays session and post variables in a moderately readable way
        highlight_string("<?php\n\$_SESSION =\n" . var_export($_SESSION, true) . ";?>");
        highlight_string("<?php\n\$_POST =\n" . var_export($_POST, true) . ";?>");
/*        highlight_string("<?php\n\$GLOBALS =\n" . var_export($GLOBALS, true) . ";?>");*/
    }

    function home()
    {
        //Clear the session data
        $view = new Template();
        echo $view->render('views/advising-home.html');
        session_destroy();
    }



    function adminLogin() {

        $username = "admin";
        $password = "admin";
        $tryAgain = "";

        //Admin login page here

        //if we arrived at this function through routing, display admin login page
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $view = new Template();
            echo $view->render('views/admin-login.html');
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $inputName = $_POST["username"];
            $usernameErr = "";

            if (empty($_POST["username"])) {
                $usernameErr = "Please enter a username";
            } else {
                if ($inputName != $username) {
                    $usernameErr = "Invalid username entered";
                }
            }

            $inputPassword = $_POST["password"];
            $passwordErr = "";

            if (empty($_POST["password"])) {
                $passwordErr = "Please enter a password";
            } else {
                if ($inputPassword != $password) {
                    $passwordErr = "Invalid password entered";
                    $tryAgain = "Please try again.";
                }
            }


            if ($usernameErr == "" && $passwordErr=="") {
                $_SESSION['adminValid'] = $username;
                header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/admin-panel.html");
            }
        }

    }



    function generateIdentifier(): string
    {
        $n = 3;
        return bin2hex(random_bytes($n));
    }

    function generateUniqueIdentifier(): string
    {
        echo "generate unique ID func reached";
        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        $potentialIdentifier = null;
        $idIsUnique = false;

        while ($idIsUnique == false) {
            //generate a new potential ID until we confirm uniqueness
            $potentialIdentifier = $this->generateIdentifier();

            //see if ID is in DB already
            foreach ($allIdentifiers as $id) {

                //if we find a duplicate, break out of loop to avoid flag being set to true
                if ($id['user_id'] == $potentialIdentifier) {
//                    $idIsUnique = false;

                    //if we break, a new ID will be generated
                    break;
                }

                //if we make it here, we've run out of IDs to check against and know the ID is unique
                $idIsUnique = true;
            }
        }
        return $potentialIdentifier;
    }

    function idInDatabase($passedID): bool {

        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        foreach ($allIdentifiers as $currentID) {
            if ($currentID['user_id'] == $passedID) {
                return true;
            }
        }
        return false;
    }


    function newPlan()
    {

//        $n = 3;
//        $potentialID = bin2hex(random_bytes($n));
//        echo "Potential ID: ".$potentialID;
        //random 6-digit code here
        //perform 6-digit check here

//        $potentialID = $this->generateIdentifier();


        //retrieve all IDs
//        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        $_SESSION['newSixDigits'] = $this->generateUniqueIdentifier();
        $this->debug();
        $view = new Template();
        echo $view->render('views/blank-plan.html');
    }

    function savePlan()
    {

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $planIdentifier = $_SESSION['newSixDigits'];
            $fallClasses = $_POST['fallClasses'];
            $winterClasses = $_POST['winterClasses'];
            $springClasses = $_POST['springClasses'];
            $summerClasses = $_POST['summerClasses'];
            $advisorName = $_POST['advisorName'];


            $_SESSION['advisee'] = new Advisee($planIdentifier, $fallClasses, $winterClasses, $springClasses, $summerClasses, $advisorName);

            /*^in theory, you could post an existing id to this page and wipe the entry for that id, we should prevent
            insertion with existing IDs here*/


            $GLOBALS['dataLayer']->insertPlan($_SESSION['advisee']);
            session_destroy();
            header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/savedplan/".$planIdentifier);
        }
    }

    function updateExistingPlan()
    {

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $urlID = substr($_SERVER['REQUEST_URI'], -6);
            $planIdentifier = $_SESSION['newSixDigits'];
            $fallClasses = $_POST['fallClasses'];
            $winterClasses = $_POST['winterClasses'];
            $springClasses = $_POST['springClasses'];
            $summerClasses = $_POST['summerClasses'];
            $advisorName = $_POST['advisorName'];

            echo "Fall Classes: " .$fallClasses;
            echo "Winter Classes: " .$winterClasses;
            echo "Spring Classes: " .$springClasses;
            echo "Summer Classes: " .$summerClasses;
            echo "Advisor Name: " .$advisorName;

//            $_SESSION['advisee'] = new Advisee($planIdentifier, $fallClasses, $winterClasses, $springClasses, $summerClasses);

            $GLOBALS['dataLayer']->updatePlan($urlID, $fallClasses, $winterClasses, $springClasses, $summerClasses, $advisorName);

//            session_destroy();
            header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/savedplan/".$urlID);
        }
    }

    function retrievePlan()    {

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            //pull off the id from the url
            $urlID = substr($_SERVER['REQUEST_URI'], -6);


            //if the provided url is not in the db, reroute to home with header()
            if(!$this->idInDatabase($urlID)) {
                header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/");
            }
            else {
                //if it's in the db, continue

                //retrieve the information in the DB associated with the supplied ID
                $retrievedPlanArray =  $GLOBALS['dataLayer']->getPlan($urlID);

                //retrieves the 'last updated' timestamp
                $lastUpdated =  $GLOBALS['dataLayer']->getLastUpdated($urlID);

//                echo "URL Substring: " .$urlID;
//                echo "\n\nretrieved plan:  \n" .$retrievedPlanArray["user_id"][0];

//            $planIdentifier = '666666';
//            $fallClasses = $_POST['fallClasses'];
//            $winterClasses = $_POST['winterClasses'];
//            $springClasses = $_POST['springClasses'];
//            $summerClasses = $_POST['summerClasses'];

//            echo "Advisee Arr 0" .$retrievedPlanArray[0];

                $_SESSION['retrievedPlan'] = $retrievedPlanArray;
                $_SESSION['lastUpdated'] = $lastUpdated[0]['last_updated'];
//            printf ("%s (%s)\n", $retrievedPlanArray["user_id"], $retrievedPlanArray["winter"]);


//            $_SESSION['retrievedPlan'] = new Advisee($retrievedPlanArray[0], $retrievedPlanArray[1], $retrievedPlanArray[2], $retrievedPlanArray[3], $retrievedPlanArray[4]);
//            session_destroy();

//            header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/savedplan/".$urlID);
                $view = new Template();
                echo $view->render('views/retrieved-plan.html');
            }

        }
    }


}