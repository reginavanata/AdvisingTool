<?php

class DataLayer
{
    //add a field to store the database connection
    private $_dbh;

    //define a default constructor
    //TODO add doc blocks
    function __construct()
    {
        try{
            //instantiate a PDO object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            echo "Yay!";
        }
        catch(PDOException $e){
            echo "Error connecting to DB " . $e->getMessage();
        }
    }

    function insertMember()
    {
//define the query
        $sql = "INSERT INTO `member` (fname, lname, age, gender, phone, email, state, seeking, bio)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio)";

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the parameters
        $statement->bindParam(':fname', $member->getFname());
        $statement->bindParam(':meal', $order->getMeal());
        $statement->bindParam(':condiments', $order->getCondiments());

        //execute the query
        $statement->execute();

        //process the results (get the primary key)
        $id = $this->_dbh->lastInsertId();
        return $id;
    }

    function getMembers()
    {

    }

    function getMember($member_id)
    {

    }

    function getInterests($member_id)
    {

    }

    static function getGender()
    {
        return array('female', 'non-binary', 'male');
    }

    static function getIndoor()
    {
        return array('TV', 'Movies', 'Cooking', 'Cooking', 'Board Games', 'Puzzles',
            'Reading', 'Playing Cards', 'Video Games');
    }

    static function getOutdoor()
    {
        return array('Camping', 'Kayaking', 'Rock Climbing', 'Hiking', 'Surfing',
            'Snowboarding', 'Swimming');
    }
}
