<?php

class Validator
{
    static function validGender($gender)
    {
        return in_array($gender, DataLayer::getGender());
    }

    static function validName($name)
    {
        if($name == ""){
            return false;
        }
        return true;
    }

    static function validAge($age)
    {
        if($age < 18 || $age > 118){
            return false;
        }
        return true;
    }

    static function validPhone($phone)
    {
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

    static function validEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false;
        }
        return true;
    }

    static function validOutdoor($outdoorChoices)
    {
        $choices = DataLayer::getOutdoor();

        foreach ($outdoorChoices as $selection){
            if(!in_array($selection, $choices)){
                return false;
            }
        }
        return true;
    }

    static function validIndoor($indoorChoices)
    {
        $choices = DataLayer::getOutdoor();

        foreach ($indoorChoices as $selection){
            if(!in_array($selection, $choices)){
                return false;
            }
        }
        return true;
    }
}


