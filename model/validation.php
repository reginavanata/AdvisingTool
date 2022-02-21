<?php

    function validGender($gender){
        return in_array($gender, getGender());
    }

    function validName($name){
        if($name == ""){
            return false;
        }
        return true;
    }

    function validAge($age){
        if($age < 18 || $age > 118){
            return false;
        }
        return true;
    }

    function validPhone($phone){
        $isValid = true;
        //if phone is less than 10 digits
        //or more than 14 digits
        //return false
        //allow for + and country code
        if (!empty($phone)) {
            $phoneLength = strlen($phone);
            if ($phoneLength < 10 || $phoneLength > 15) {
                $isValid = false;
            }
            else{
                if (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $phone)){
                    $isValid = false;
                }
            }
            return $isValid;
        } else {
            return false;
        }
    }

    function validEmail($email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }

    function validOutdoor($outdoorChoices){
        $choices = getOutdoor();

        foreach ($outdoorChoices as $selection){
            if(!in_array($selection, $choices)){
                return false;
            }
        }
        return true;
    }

    function validIndoor($indoorChoices){
        $choices = getOutdoor();

        foreach ($indoorChoices as $selection){
            if(!in_array($selection, $choices)){
                return false;
            }
        }
        return true;
    }
