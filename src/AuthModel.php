<?php
// This is the wrapper class for Login ,sign up  and forget password .
 require_once __DIR__."/Config.php";
Class AuthModel
{
   // This method will have the business logic for native login 
   /*
    * Params
    * email -> user email id 
    * password -> user password
    * logArray -> This array will contain elements such as ipaddress,useragent,email id and source 
    */
   public function isValidLogin($emailorMob,$password,$isMob,$doneBy)
   {      
   	$loginResult = array();
   	try {
      
      Connection::getInstance();
      $sqlQuery = ($isMob) ? "CALL auth.sp_get_user_by_mob(?)": "CALL auth.sp_get_user_by_email(?)";
     // $spParams = array();
     // array_push($spParams,$emailorMob);
      $spParams =  array("p_loginId"=>$emailorMob);
      $loginResult = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);

      //return $loginResult;
      // get the hash password 
      $hashPassword =  AuthModel::getHashedPassword($password);
      //print_r($loginResult);
      
    /*  if($loginResult["reqstat"] == 500)
      {
      	$loginResult["message"]= "Something went wrong. Please try again later";
      }*/

     $loginResult = array_shift($loginResult);
    
      // This will check for password correctness
      if(count($loginResult) > 0 && array_key_exists("password", $loginResult) && $hashPassword != $loginResult['password']){
      	unset($loginResult);
      	$loginResult["message"] = 'invalid';
      	$errorArray= array();
      	array_push($errorArray,array("field"=>"password","desc"=>"incorrect password"));
	      $loginResult["reqstat"] = 400;
	       $loginResult["parameters"] = $errorArray;	
      }
	  
      // On success full login we will put an entry database using the WriteLoggedInUserLog method 
      // This willl have the folowing information 
      // email,ipaddress,useragent,source (mob/web) and login time .
      // This also update the last_login column in native user login table 
      if($loginResult["reqstat"]!=200){
      	 //Log_DataLog::WriteLoggedInUserLog($logArray);
      }
      
   	  }
      catch (Exception $ex){
      //  $loginResult["reqstat"] = 500;
   	 	//$loginResult["message"] = "Something went wrong. Please try again later";
   	 	//SysLogger::logError($ex);
        throw $ex;
      }
 
      return $loginResult;
   }

    // This method will return the hash password 
   public static function getHashedPassword($password)
   {
   		Config::getConfig();
   		return hash('sha256',Config::$config['application']['salt'].$password);
   		
   }
   
    // This method having the logic for user email id already exist or not 
   /*
    * Params
    * $email => Email id need to be checked 
    * $mobNo => Mobile no to be checked 
    */
   public function checkUserAlreadyRegistered($email,$mobNo){
      try{
   	   	Connection::getInstance();
   	   	$sqlQuery = "CALL sp_get_user_by_email(?)";
   	   	$spParams =  array("p_loginId"=>$email);
   	   	$result = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);;
        $result = array_shift($result);
        if(count($result) > 0 && array_key_exists("email_id",$result )){
           	$isValidSignUp["reqstat"] = 409;
				    $isValidSignUp["message"] = "Email already registered";
				    return $isValidSignUp;
        }

        $sqlQuery = "CALL auth.sp_get_user_by_mob(?)";
   	   	$spParams =  array("p_loginId"=>$mobNo);
        $result  = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);;
        $result  = array_shift( $result );
        if(count($result) > 0 && array_key_exists("mob_no",$result)){
           	$isValidSignUp["reqstat"] = 409;
				    $isValidSignUp["message"] = "Mobile number already registered";
				    return $isValidSignUp;
        }

        $isValidSignUp["reqstat"] = 200;
        return $isValidSignUp;
   	   	
   	   }
   	   catch(Exception $ex){
   	     	throw $ex;
   	   }
   	  
   }

    // This method having the logic for naticeSignup
   /*
    * Params
    * $email => Email id
    * $mobNo => Mobile no 
    * $password => password to be checked
    * $doneBy => request from 
    */
   public function nativeSignup($email,$mobNo,$password,$doneBy){
   	$signUpResult = array();
   	try{
   	 	 Connection::getInstance();
   	 	 $sqlQuery = "CALL create_user(?,?,?,?)";
   	 	 $spParams =  array("p_email"=>$email,"mob_no"=>$mobNo,"passwd"=>AuthModel::getHashedPassword($password),"created_by"=>$doneBy);
   	 	 $result = Connection::$repoInstance->executeInsertUpdateQuery($sqlQuery,$spParams);
   	 	 
   	 	 if($result["reqstat"] == 200)
   	 	 {
   	 	 	  $signUpResult = $this->getUserDetailByEmail($email);
   	 	 }

       return $signUpResult;
   	 }
   	 catch(Exception $ex){
   	 	 throw $ex;
   	 }
   	
   }

   public function getUserDetailByEmail($email){
     try{
   	   	Connection::getInstance();
   	   	$sqlQuery = "CALL sp_get_user_by_email(?)";
   	   	$spParams =  array("p_loginId"=>$email);
   	   	$result = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);;
        $result = array_shift($result);
        return $result;
     }
     catch(Exception $ex){
       throw $ex;
     }
   }
   
}
?>