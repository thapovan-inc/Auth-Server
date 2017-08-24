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
      $spParams =  array("p_loginId"=>$emailorMob);
      $loginResult = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);
      // get the hash password 
      $hashPassword =  AuthModel::getHashedPassword($password);
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
   	  }
      catch (Exception $ex){
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
   	 	 $sqlQuery = "CALL sp_create_user(?,?,?,?)";
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
   // This method get the user detail by email id 
   /*
    * Params
    *  $email => user email id 
    */
   public function getUserDetailByEmail($email){
     try{
   	   	Connection::getInstance();
   	   	$sqlQuery = "CALL sp_get_user_by_email(?)";
   	   	$spParams =  array("p_loginId"=>$email);
   	   	$result = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);;
        $result = array_shift($result);
        if(isset($result)){
          $result["reqstat"] = 200;
        }
        print_r($result);
        return $result;
     }
     catch(Exception $ex){
       throw $ex;
     }
   }

    // This method insert the data in table related to forgot password 
   /*
    * Params
    *  $email => Email id for which password needs to change
    * $token=> unique token for fogot password
    * $userId => user unique user id
    * $mobNo -> user mobile number
    * $requestBy => request comes from 
    */
   public function forgotPassword($email,$token,$userId,$mobNo,$requestBy){
   	 $isValidAction = array();
   	try{
   	 	 Connection::getInstance();
   	 	 $sqlQuery = "CALL sp_manage_forgot_password(?,?,?,?,?)";
   	 	 $spParams =  array("p_email"=>$email,"p_token"=>$token,"p_user_id"=>$userId,"p_mob_no"=>$mobNo,"p_request"=>$requestBy);
   	 	 $isValidAction = Connection::$repoInstance->executeInsertUpdateQuery($sqlQuery,$spParams);	 
   	 }
   	 catch(Exception $ex){
   		throw $ex;
   	}
   	return $isValidAction;
   }
    // This method will verify the token against the one generated in forgot password 
   /* this will throw token mismatch,tocken expiry errors 
    * Params
    * $token=> unique token generated in forgot password
    * $email => user email id 
    */
   public function verifyToken($token,$email){
     $isValidToken = array();
     try{
        Connection::getInstance();
        $sqlQuery = "CALL sp_verify_token(?,?)";
        $spParams =  array("p_email"=>$email,"p_token"=>$token);
        $isValidToken = Connection::$repoInstance->executeSelectQuery($sqlQuery,$spParams);
        $isValidToken = array_shift($isValidToken);
        return $isValidToken;
     }
     catch(Exception $ex){
        throw $ex;
     }
   }
   // This method will  reset the user password based 
   /* this will throw token mismatch,tocken expiry errors 
    * Params
    * $email => user email id 
    * $password => user password 
    * $doneBy => request from 
    */
   public function resetPassword($email,$password,$doneBy){
     $result = array();
     try{
        Connection::getInstance();
        $sqlQuery = "CALL sp_reset_password(?,?,?)";
        $password = AuthModel::getHashedPassword($password);
        $spParams =  array("p_email"=>$email,"p_password"=>$password,"p_dobe_by"=>$doneBy);
        $result = Connection::$repoInstance->executeInsertUpdateQuery($sqlQuery,$spParams); 
        $result["message"] = "password reset succesfully";
        return $result;
     }
     catch(Exception $ex){
        throw $ex;
     }
   }
   
}
?>