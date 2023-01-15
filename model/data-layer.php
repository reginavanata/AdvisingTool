<?php
//require the database credentials
require_once $_SERVER['DOCUMENT_ROOT'].'/../db-creds.php';

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
            $this->_dbh = new PDO(DB_DSN_1, DB_USERNAME, DB_PASSWORD);
            echo "Yay!";
        }
        catch(PDOException $e){
            echo "Error connecting to DB " . $e->getMessage();
        }
    }

    function insertPlan($advisee)
    {
//define the query
        $sql = "INSERT INTO advising_plans (user_id, fall, winter, spring, summer)
                VALUES (:user_id, :fall, :winter, :spring, :summer)";

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the parameters
        $statement->bindParam(':user_id', $advisee->getUserId());
        $statement->bindParam(':fall', $advisee->getFall());
        $statement->bindParam(':winter', $advisee->getWinter());
        $statement->bindParam(':spring', $advisee->getSpring());
        $statement->bindParam(':summer', $advisee->getSummer());

        //execute the query
        $statement->execute();

        //process the results (get the primary key)
        $id = $this->_dbh->lastInsertId();
        return $id;
    }

    function getPlans()
    {
        //1. Define the query
        $sql = "SELECT * FROM advising_plans";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getPlan($user_id)
    {
        //1. Define the query
        $sql = "SELECT $user_id FROM advising_plans";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}
