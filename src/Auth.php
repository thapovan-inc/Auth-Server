<?php
  /*
  Author : Anand Ramani
  Dscription: This class provide all the behaviour for authentication  
  such as  login,resgitration,password management
  */
  require_once __DIR__."/Validator.php";
  require_once __DIR__."/AuthModel.php";
  require_once __DIR__."/Config.php";
  class Auth{
      public function nativeLogin($emailorMob,$password,$doneBy){
       try{
             $isMobNo = false;
             $errorArray= array();
             $validator = new Validator();
             $errorArray = $validator->validateRequiredField(array("email"=>$emailorMob,"password"=>$password));
             $errorArray = array_filter($errorArray);
             // check for field empty 
             if(!empty($errorArray)){
               $isValidLogin["message"] = 'required parameter missing';
               $isValidLogin["reqstat"] = 400;
               $isValidLogin["parameters"]= $errorArray;
               return $isValidLogin;
             }
             // check whether the given id is email or mobile number
             if(is_numeric($emailorMob)){
               $isMobNo = true;
               if(!$validator->isValidMob($emailorMob)){
                  $isValidLogin["message"] = 'invalid';
                  $isValidLogin["reqstat"] = 400;
                  $errorArray= array();
                  array_push($errorArray,array("field"=>"MobNo","desc"=>"Invalid mobile no"));
                  $isValidLogin["parameters"] = $errorArray;
                  return $isValidLogin;
               }
             }
             // validation for email 
             else if(!$validator->isValidEmail($emailorMob)){
                $isValidLogin["message"] = 'invalid';
                $isValidLogin["reqstat"] = 400;
                $errorArray= array();
                array_push($errorArray,array("field"=>"email","desc"=>"Invalid emailId"));
                $isValidLogin["parameters"] = $errorArray;
                return $isValidLogin;
             }

             // This section will invoke the method for business model and database
            // connectivity
             $authModel = new AuthModel();
             $isValidLogin = $authModel->isValidLogin($emailorMob,$password,$isMobNo,$doneBy);
             return $isValidLogin;
          }
          catch(Exception $ex){
            $isValidLogin["reqstat"] = 500;
			      $isValidLogin["message"] = "Something went wrong. Please try again later";
            $isValidLogin["error"] =  $ex;
            throw $isValidLogin;
          }
          
      }

      public function nativeSignupAction($email,$mobNo,$password,$confirmPassword,$doneBy){
        	$isValidSignUp = array();
          try{
             	$errorArray= array();
              $validator = new Validator();
		        	$errorArray = $validator->validateRequiredField(array("email"=>$email,
		          "password"=>$password,"confirmpassword"=>$confirmPassword,"mobNo"=>$mobNo));
			        $errorArray = array_filter($errorArray);
              // check for field empty 
              if(!empty($errorArray)){
				        $isValidSignUp["message"] = 'required parameter missing';
				        $isValidSignUp["reqstat"] = 400;
				        $isValidSignUp["parameters"]= $errorArray;
                return $isValidSignUp;
			        }
              // Email validation
              if(!$validator->isValidEmail($email)){
                $isValidSignUp["message"] = 'invalid';
                $isValidSignUp["reqstat"] = 400;
                $errorArray= array();
                array_push($errorArray,array("field"=>"email","desc"=>"Invalid emailId"));
                $isValidSignUp["parameters"] = $errorArray; 
                return $isValidSignUp;
              }
              // Mobile number validation 
              if(!$validator->isValidMob($mobNo)){
                $isValidSignUp["message"] = 'invalid';
                $isValidSignUp["reqstat"] = 400;
                $errorArray= array();
                array_push($errorArray,array("field"=>"MobNo","desc"=>"Invalid mobile no"));
                $isValidSignUp["parameters"] = $errorArray;
                return $isValidSignUp;
              }
              
              Config::getConfig();
              $regExp  = strlen(Config::$config["regexp"]["pwd"]) > 2 ? Config::$config["regexp"]["pwd"] : "#.*^(?=.{6,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#";
              // password validation 
              if(!preg_match($regExp, $password)){
				        $isValidSignUp["message"] = 'invalid';
				        $isValidSignUp["reqstat"] = 400;
				        $errorArray= array();
				        array_push($errorArray,array("field"=>"password","desc"=>"Password length should be greater than 6 and it should contain at least 1 number,1 caps & 1 symbol"));
			        	$isValidSignUp["parameters"] = $errorArray;
			        	return $isValidSignUp;
			        }

              // confirm password check 
              if(strcmp($password, $confirmPassword)!=0){
				        $isValidSignUp["message"] = 'Confirm password and password doesnt match';
				        $isValidSignUp["reqstat"] = 409;
				        return $isValidSignUp;
			        }
              
              $authModel = new AuthModel();
              $isValidSignUp = $authModel->checkUserAlreadyRegistered($email,$mobNo);
              if($isValidSignUp["reqstat"]!=200){
                return $isValidSignUp;
              }

              $result = $authModel->nativeSignup($email,$mobNo,$password,$doneBy);
              return $result;
              


          }
          catch(Exception $ex){
            throw $ex;
          }
      }

  

  }
?>