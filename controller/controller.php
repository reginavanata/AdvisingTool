<?php

class Controller
{
    private $_f3; //f3 object

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

/*    function home()
    {
        $view = new Template();
        echo $view->render('views/home.html');
    }*/

    function home()
    {
        //Clear the session data

        $view = new Template();
        echo $view->render('views/advising-home.html');
        session_destroy();
    }

    function personal()
    {
        //initialize input variables
        $fname = "";
        $lname = "";
        $age = "";
        $gender = "";
        $phone = "";

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $fname = $_POST['fName'];
            $lname = $_POST['lName'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $phone = $_POST['phone'];
            $premium = $_POST['premiumAccount'];

            //instantiate a member object
            if(isset($premium)){
                $_SESSION['member'] = new PremiumMember($fname, $lname, $age, $gender, $phone);
            }
            else{
                $_SESSION['member'] = new Member($fname, $lname, $age, $gender, $phone);
            }
            //Validate the data
            if(Validator::validName($fname)){
                //add the data to the session variable
                $_SESSION['member']->setFname($fname);
            }
            else{
                $this->_f3->set('errors["fName"]', 'Please enter a first name');
            }

            if(Validator::validName($lname)){
                $_SESSION['member']->setLname($lname);
            }
            else{
                $this->_f3->set('errors["lName"]', 'Please enter a last name');
            }

            if(Validator::validAge($age)){
                $_SESSION['member']->setAge($age);
            }
            else{
                $this->_f3->set('errors["age"]', 'Please enter an age between 18 and 118');
            }

            if(Validator::validPhone($phone)){
                $_SESSION['member']->setPhone($phone);
            }
            else{
                $this->_f3->set('errors["phone"]', 'Please enter a valid phone number');
            }


            if(Validator::validGender($gender)){
                $_SESSION['member']->setGender($gender);
            }
            //gender is optional, no error checking
            /*else{
               $f3->set('errors["gender"]', 'Please select a gender option');
           }*/

            //redirect user to next page if there are no errors
            if(empty($this->_f3->get('errors'))){
                $this->_f3->reroute('profile');
            }
        }

        $this->_f3->set('fName', $fname);
        $this->_f3->set('lName', $lname);
        $this->_f3->set('age', $age);
        $this->_f3->set('phone', $phone);
        $this->_f3->set('userGender', $gender);
        $this->_f3->set('genders', DataLayer::getGender());

        $view = new Template();
        echo $view->render('views/personal-info.html');
    }

    function profile()
    {
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

            if(Validator::validEmail($email)){
                $_SESSION['member']->setEmail($email);
            }
            else{
                $this->_f3->set('errors["email"]', 'Please enter a valid email address');
            }

            $_SESSION['member']->setState($state);
            $_SESSION['member']->setSeeking($seeking);
            $_SESSION['member']->setBio($inputBio);

            //redirect user to next page
            if(empty($this->_f3->get('errors'))){
                if($_SESSION['member'] instanceof PremiumMember){
                    $this->_f3->reroute('interests');
                }
                else{
                    $this->_f3->reroute('summary');
                }

            }
        }

        $view = new Template();
        echo $view->render('views/profile.html');

    }

    /*function interests()
    {

        //get interests from the model and add to F3 hive
        $this->_f3->set('indoor', DataLayer::getIndoor());
        $this->_f3->set('outdoor', DataLayer::getOutdoor());
        print_r($this->_f3->get('errors'));
        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $indoorInterests = $_POST['indoor'];
            $outdoorInterests = $_POST['outdoor'];
            //add the data to the session variable
            if(isset($_POST['indoor'])){

                if(Validator::validIndoor($indoorInterests)){
                    $indoorInterests = implode(", ", $_POST['indoor']);
                    //$_SESSION['member']->setInDoorInterests($indoorInterests);
                }
                else{
                    $this->_f3->set("errors['indoor']", "Invalid selection");
                }
            }
            if(isset($_POST['outdoor'])){


                if(Validator::validOutdoor($outdoorInterests)){
                    $outdoorInterests = implode(", ", $_POST['outdoor']);
                    //$_SESSION['member']->setOutdoorInterests($outdoorInterests);
                }
                else{
                    $this->_f3->set("errors['outdoor']", "Invalid selection");
                }
            }

            //redirect user to next page
            if(empty($this->_f3->get('errors'))){
                if(!empty($_POST['indoor'])){
                    $_SESSION['member']->setInDoorInterests(implode(", " , $indoorInterests));
                }

                if(!empty($_POST['outdoor'])){
                    $_SESSION['member']->setOutdoorInterests(implode(", " , $outdoorInterests));
                }

                $this->_f3->reroute('summary');
            }
        }

        $view = new Template();
        echo $view->render('views/interests.html');
    }*/
    function interests()
    {

        //get interests from the model and add to F3 hive
        $this->_f3->set('indoor', DataLayer::getIndoor());
        $this->_f3->set('outdoor', DataLayer::getOutdoor());
        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $indoorInterests = $_POST['indoor'];
            $outdoorInterests = $_POST['outdoor'];
            //add the data to the session variable
            if(!empty($_POST['indoor'])){

                if(Validator::validIndoor($indoorInterests)){
                    $indoorInterests = implode(", ", $_POST['indoor']);
                    $_SESSION['member']->setInDoorInterests($indoorInterests);
                }
                else{
                    $this->_f3->set("errors['indoor']", "Invalid selection");
                }

            }
            if(!empty($_POST['outdoor'])){


                if(Validator::validOutdoor($outdoorInterests)){
                    $outdoorInterests = implode(", ", $_POST['outdoor']);
                    $_SESSION['member']->setOutdoorInterests($outdoorInterests);
                }
                else{
                    $this->_f3->set("errors['outdoor']", "Invalid selection");
                }
            }

            //redirect user to next page
            if(empty($this->_f3->get('errors'))){
                if(!empty($_POST['indoor'])){
                    $_SESSION['member']->setInDoorInterests(implode(", " , $indoorInterests));
                }

                if(!empty($_POST['outdoor'])){
                    $_SESSION['member']->setOutdoorInterests(implode(", " , $outdoorInterests));
                }


            }
            $this->_f3->reroute('summary');
        }

        $view = new Template();
        echo $view->render('views/interests.html');
    }

    function summary()
    {
        //TODO: Send data to the model
        $GLOBALS['dataLayer']->insertMember($_SESSION['member']);
        if($_SESSION['member'] instanceof PremiumMember){
            $this->_f3->set('accountPremium', true);
        }
        else{
            $this->_f3->set('accountPremium', false);
        }
        $view = new Template();
        echo $view->render('views/profile-summary.html');

        //Clear the session data
        session_destroy();
    }

    function admin()
    {
        $members = $GLOBALS['dataLayer']->getMembers();
        $this->_f3->set('members', $members);

        //display the view page
        $view = new Template();
        echo $view->render('views/admin.html');
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