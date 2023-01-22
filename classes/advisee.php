<?php

class Advisee
{
    private $_user_id;
    private $_fall;
    private $_winter;
    private $_spring;
    private $_summer;
    private $_advisor;

    /**
     * @param $_user_id
     * @param $_fall
     * @param $_winter
     * @param $_spring
     * @param $_summer
     * * @param $_advisor
     */
    public function __construct($_user_id, $_fall, $_winter, $_spring, $_summer, $_advisor)
    {
        $this->_user_id = $_user_id;
        $this->_fall = $_fall;
        $this->_winter = $_winter;
        $this->_spring = $_spring;
        $this->_summer = $_summer;
        $this->_advisor = $_advisor;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->_user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getFall()
    {
        return $this->_fall;
    }

    /**
     * @param mixed $fall
     */
    public function setFall($fall): void
    {
        $this->_fall = $fall;
    }

    /**
     * @return mixed
     */
    public function getWinter()
    {
        return $this->_winter;
    }

    /**
     * @param mixed $winter
     */
    public function setWinter($winter): void
    {
        $this->_winter = $winter;
    }

    /**
     * @return mixed
     */
    public function getSpring()
    {
        return $this->_spring;
    }

    /**
     * @param mixed $spring
     */
    public function setSpring($spring): void
    {
        $this->_spring = $spring;
    }

    /**
     * @return mixed
     */
    public function getSummer()
    {
        return $this->_summer;
    }

    /**
     * @param mixed $summer
     */
    public function setSummer($summer): void
    {
        $this->_summer = $summer;
    }

    /**
     * @return mixed
     */
    public function getAdvisor()
    {
        return $this->_advisor;
    }

    /**
     * @param mixed $advisor
     */
    public function setAdvisor($advisor): void
    {
        $this->_advisor = $advisor;
    }

}
