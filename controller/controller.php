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

    function personalInfo()
    {
        //initialize input variables
        $fName = "";
        $lName = "";
        $age = "";
        $gender = "";
        $phone = "";

        //If the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            $fName = $_POST['fName'];
            $lName = $_POST['lName'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $phone = $_POST['phone'];

            //instantiate a member object
            $_SESSION['member'] = new Member();


            //Validate the data
            if(validName($fName)){
                $this->_f3->set('SESSION.fName', $fName);
            }
            else{
                $this->_f3->set('errors["fName"]', 'Please enter a first name');
            }

            if(validName($lName)){
                $this->_f3->set('SESSION.lName', $lName);
            }
            else{
                $this->_f3->set('errors["lName"]', 'Please enter a last name');
            }

            if(validAge($age)){
                $this->_f3->set('SESSION.age', $age);
            }
            else{
                $this->_f3->set('errors["age"]', 'Please enter an age between 18 and 118');
            }

            if(validPhone($phone)){
                $this->_f3->set('SESSION.phone', $phone);
            }
            else{
                $this->_f3->set('errors["phone"]', 'Please enter a valid phone number');
            }


            if(validGender($gender)){
                $this->_f3->set('SESSION.genderOptions', $gender);
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

        $this->_f3->set('fName', $fName);
        $this->_f3->set('lName', $lName);
        $this->_f3->set('age', $age);
        $this->_f3->set('phone', $phone);
        $this->_f3->set('userGender', $gender);
        $this->_f3->set('genders', getGender());

        $view = new Template();
        echo $view->render('views/personal-info.html');
    }
}