<?php
//require the database credentials
require_once $_SERVER['DOCUMENT_ROOT'].'/../pdo-dating-config.php';

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

    function insertMember($member)
    {
        if($_SESSION['member'] instanceof Member){
            //define the query
            $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, 0)";

            //prepare the statement
            $statement = $this->_dbh->prepare($sql);

            //bind the parameters
            $statement->bindParam(':fname', $member->getFname());
            $statement->bindParam(':lname', $member->getLname());
            $statement->bindParam(':age', $member->getAge());
            $statement->bindParam(':gender', $member->getGender());
            $statement->bindParam(':phone', $member->getPhone());
            $statement->bindParam(':email', $member->getEmail());
            $statement->bindParam(':state', $member->getState());
            $statement->bindParam(':seeking', $member->getSeeking());
            $statement->bindParam(':bio', $member->getBio());
        }
        if($_SESSION['member'] instanceof PremiumMember){
            $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium, interests)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, 1, :interests)";

            //prepare the statement
            $statement = $this->_dbh->prepare($sql);

            //bind the parameters
            //$allInterests = implode(",", $member->getOutDoorInterests()) . ", " . implode("," , $member->getInDoorInterests());
            $statement->bindParam(':fname', $member->getFname());
            $statement->bindParam(':lname', $member->getLname());
            $statement->bindParam(':age', $member->getAge());
            $statement->bindParam(':gender', $member->getGender());
            $statement->bindParam(':phone', $member->getPhone());
            $statement->bindParam(':email', $member->getEmail());
            $statement->bindParam(':state', $member->getState());
            $statement->bindParam(':seeking', $member->getSeeking());
            $statement->bindParam(':bio', $member->getBio());
            $statement->bindParam(':interests', $member->getOutDoorInterests());
        }




        //execute the query
        $statement->execute();

        //process the results (get the primary key)
        $id = $this->_dbh->lastInsertId();
        return $id;
    }

    function getMembers()
    {
        //1. Define the query
        $sql = "SELECT * FROM member ORDER BY lname";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getMember($member_id)
    {
        //1. Define the query
        $sql = "SELECT $member_id FROM member";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getInterests($member_id)
    {
        //1. Define the query
        $sql = "SELECT interests FROM member WHERE $member_id";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    static function getGender()
    {
        return array('female', 'non-binary', 'male');
    }

    static function getIndoor()
    {
        return array('TV', 'Movies', 'Cooking', 'Board Games', 'Puzzles',
            'Reading', 'Playing Cards', 'Video Games');
    }

    static function getOutdoor()
    {
        return array('Camping', 'Kayaking', 'Rock Climbing', 'Hiking', 'Surfing',
            'Snowboarding', 'Swimming');
    }
}
