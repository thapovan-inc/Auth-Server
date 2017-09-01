<?php
/*
  Author : Anand Ramani
  Dscription: This class will deal with database connectivity and db operations 
  like select,insert e.t.c
*/
 require_once __DIR__."/Config.php";
 require_once __DIR__."/Constants.php";
 class Connection{
     public static $repoInstance;
     public static $pdoObj;

     //This will create only one objedt for Repository class 
     public static function getInstance(){
        try{
        if(Connection::$repoInstance==null){
            Connection::$repoInstance = new Connection();
        }
        }
        catch(Exception $ex){
             throw $ex;
        }
     }

     // This method will set the pdo object for database connectivity
     public function getConnection(){
        try{
             Config::getConfig();
             if(Connection::$pdoObj == null)  {
                $dsn = "mysql:host=".Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$Host].";port=".Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$Port].";dbname=".Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$DB].";charset=".Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$charset];
                $opt = [
                            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                            PDO::ATTR_EMULATE_PREPARES   => false,
                ];
                $user = Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$UserName];
                $pass = Config::$config[ApplicationConstants::$DBConfigRoot][ApplicationConstants::$Password];  
                Connection::$pdoObj = new PDO($dsn, $user, $pass, $opt); 
             }     
        }
        catch(Exception $ex){
            throw $ex;
        }
     }

     // This method is used for any select statement in the application 
	/*
	 * params
	 * $sqlQuery = Query to be executed 
	 * $paramArray = Stored procedure parameters
	 */
     public function executeSelectQuery($sqlQuery,$paramArray){
        Connection::$repoInstance->getConnection();
        try{
        
          $stmt = Connection::$pdoObj->prepare($sqlQuery);
          $count = 1;
          foreach ($paramArray as $key => $value) {
             $stmt->bindParam($count++,$paramArray[$key]);
          }
         
          $stmt->execute();
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
          return $result;
     }
     catch(Exception $ex){
         throw $ex;
     }
     }

     // This method is used to run insert / update statements
	/*
	 * params
	 * $sqlQuery = Query to be executed 
	 * $paramArray = Stored procedure parameters
	 */
     public function executeInsertUpdateQuery($sqlQuery,$paramArray){
        Connection::$repoInstance->getConnection();
        try{
        
          $stmt = Connection::$pdoObj->prepare($sqlQuery);
          $count = 1;
          foreach ($paramArray as $key => $value) { 
             $stmt->bindParam($count++,$paramArray[$key]);
          }
          $stmt->execute();
          $result = array();
          $result[ApplicationConstants::$ReqStat] = 200;
          return $result;
     }
     catch(Exception $ex){
         throw $ex;
     }
     }
         

 }
?>