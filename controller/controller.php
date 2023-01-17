<?php

class Controller
{
    private $_f3; //f3 object

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    function home()
    {
        //Clear the session data

        $view = new Template();
        echo $view->render('views/advising-home.html');
        session_destroy();
    }

    function generateIdentifier(): string
    {
        $n = 3;
        return bin2hex(random_bytes($n));
    }

    function generateUniqueIdentifier(): string
    {

        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        $potentialIdentifier = null;
        $idIsUnique = false;

        while ($idIsUnique == false) {
            $potentialIdentifier = $this->generateIdentifier();
            //see if ID is in DB already
            foreach ($allIdentifiers as $id) {
                if ($id == $potentialIdentifier) {
                    $idIsUnique = false;
                    break;
                }
                $idIsUnique = true;
            }
        }

        return $potentialIdentifier;
    }

    function idInDatabase($passedID): bool {

        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        $isInDatabase = false;

        foreach ($allIdentifiers as $currentID) {
            if ($currentID == $passedID) {
                return true;
            }
        }
        return false;
    }


    function newPlan()
    {

//testing to see if repos are right, again
//        $n = 3;
//        $potentialID = bin2hex(random_bytes($n));
//        echo "Potential ID: ".$potentialID;
        //random 6-digit code here
        //perform 6-digit check here

//        $potentialID = $this->generateIdentifier();


        //retrieve all IDs
//        $allIdentifiers = $GLOBALS['dataLayer']->getAllIdentifiers();

        $_SESSION['newSixDigits'] = $this->generateUniqueIdentifier();



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


            $_SESSION['advisee'] = new Advisee($planIdentifier, $fallClasses, $winterClasses, $springClasses, $summerClasses);

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

            echo "Fall Classes: " .$fallClasses;
            echo "Winter Classes: " .$winterClasses;
            echo "Spring Classes: " .$springClasses;
            echo "Summer Classes: " .$summerClasses;

//            $_SESSION['advisee'] = new Advisee($planIdentifier, $fallClasses, $winterClasses, $springClasses, $summerClasses);

            $GLOBALS['dataLayer']->updatePlan($urlID, $fallClasses, $winterClasses, $springClasses, $summerClasses);

//            session_destroy();
            header("Location: https://ptagliavia.greenriverdev.com/AdvisingTool/savedplan/".$urlID);
        }
    }

    function retrievePlan()    {

        //if identifier doesn't exist, redirect to homepage

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {

            $urlID = substr($_SERVER['REQUEST_URI'], -6);

            $retrievedPlanArray =  $GLOBALS['dataLayer']->getPlan($urlID);

            $lastUpdated =  $GLOBALS['dataLayer']->getLastUpdated($urlID);

            echo "URL Substring: " .$urlID;
            echo "\n\nretrieved plan:  \n" .$retrievedPlanArray["user_id"][0];

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