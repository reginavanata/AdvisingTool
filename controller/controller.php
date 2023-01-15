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
        $view = new Template();
        echo $view->render('views/advising-home.html');
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

    function newPlan()
    {
        $view = new Template();
        echo $view->render('views/blank-plan.html');
    }

    function savePlan()
    {

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $planIdentifier = '666666';
            $fallClasses = $_POST['fallClasses'];
            $winterClasses = $_POST['winterClasses'];
            $springClasses = $_POST['springClasses'];
            $summerClasses = $_POST['summerClasses'];


            $_SESSION['advisee'] = new Advisee($planIdentifier, $fallClasses, $winterClasses, $springClasses, $summerClasses);

            $GLOBALS['dataLayer']->insertPlan($_SESSION['advisee']);
            session_destroy();
        }
    }

    function retrievePlan()    {
        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $adviseeID = array_pop($uriSegments);

            $retrievedPlanArray =  $GLOBALS['dataLayer']->getPlan($adviseeID);

//            $planIdentifier = '666666';
//            $fallClasses = $_POST['fallClasses'];
//            $winterClasses = $_POST['winterClasses'];
//            $springClasses = $_POST['springClasses'];
//            $summerClasses = $_POST['summerClasses'];


            $_SESSION['retrievedPlan'] = new Advisee($retrievedPlanArray[0], $retrievedPlanArray[1], $retrievedPlanArray[2], $retrievedPlanArray[3], $retrievedPlanArray[4]);
        }
    }


}