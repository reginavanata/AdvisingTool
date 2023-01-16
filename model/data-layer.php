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

    /*
     * Inserts a new academic plan to the database
     * */
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

    /*
     * returns all academic plans in the database
     * */
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
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Returns the academic plan for the given user_id
     * */
    function getPlan($user_id)
    {
        //1. Define the query
        $sql = "SELECT * FROM advising_plans
                WHERE user_id = $user_id";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters
        //$statement->bindParam(':user_id', $advisee->getUserId());
        //4. Execute the query
        $statement->execute();

        //5. Process the results (get the primary key)
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
     * Update plan for the given user_id
     * all fields will be updated
     * */
    function updatePlan($user_id, $fall, $winter, $spring, $summer)
    {
        //define the query
        $sql = 'UPDATE advising_plans SET
                (fall, winter, spring, summer, last_updated)
                VALUES (:fall, :winter, :spring, :summer, CURRENT_TIMESTAMP
                WHERE (user_id) = VALUES :user_id';

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the parameters
        $statement->bindParam(':user_id', $user_id->getUserId());
        $statement->bindParam(':fall', $fall->getFall());
        $statement->bindParam(':winter', $winter->getWinter());
        $statement->bindParam(':spring', $spring->getSpring());
        $statement->bindParam(':summer', $summer->getSummer());

        //execute the query
        $statement->execute();
    }

    function getUsers()
    {
        //define the query
        $sql = 'SELECT user_id FROM advising_plans';

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the params

        //execute the query
        $statement->execute();
    }

    function getLastUpdated($user_id)
    {
        //define the query
        $sql = 'SELECT last_updated FROM advising_plans WHERE user_id = $user_id';

        //prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //bind the params

        //execute the query
        $statement->execute();
    }

}
