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
        //echo "<h1>My Diner</h1>";

        $view = new Template();
        echo $view->render('views/home.html');
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

    function interests()
    {
        $outdoorInterests = "";
        $indoorInterests = "";
        //get interests from the model and add to F3 hive
        $this->_f3->set('indoor', DataLayer::getIndoor());
        $this->_f3->set('outdoor', DataLayer::getOutdoor());
        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            //add the data to the session variable
            if(isset($_POST['indoor'])){
                $indoorInterests = $_POST['indoor'];
                if(Validator::validIndoor($indoorInterests)){
                    $_SESSION['indoor'] = implode(", ", $_POST['indoor']);
                }
            }
            if(isset($_POST['outdoor'])){
                $outdoorInterests = $_POST['outdoor'];
                if(Validator::validOutdoor($outdoorInterests)){
                    $_SESSION['outdoor'] = implode(", ", $_POST['outdoor']);
                }
            }
            //redirect user to next page
            if(empty($this->_f3->get('errors'))){
                if(!empty($_SESSION['indoor'])){
                    $_SESSION['member']->setInDoorInterests($indoorInterests);
                }

                if(!empty($_SESSION['outdoor'])){
                    $_SESSION['member']->setOutdoorInterests($outdoorInterests);
                }

                $this->_f3->reroute('summary');
            }
        }

        $view = new Template();
        echo $view->render('views/interests.html');
    }

    function summary()
    {
        //$GLOBALS['dataLayer']->saveMember($_SESSION['member']);
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
}