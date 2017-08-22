<?php
 class Validator{
    
    // This function wil validate the required field 
	/*
	 * $fieldArray -> Array contains the fields to be validated
	 */
	public function validateRequiredField($fieldArray){
        $validationMessage = array();
		foreach ($fieldArray as $key => $value) {
		 if(!isset($value) || empty($value)){
		 	array_push($validationMessage,array("field"=>$key,"desc"=>"missing"));
		 }
   	  	}
   	  	return $validationMessage;
	}

  // This method wil check for valid email
	/*
	 * params
	 * $email -> Email id to be validated
	 */
	public function isValidEmail($email){
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// This method wil check for valid email
	/*
	 * params
	 * $mobNo -> Mob No to be validated
	 */
	public function isValidMob($mobNo){
		return preg_match('/^[0-9]{10}+$/', $mobNo);
	}
 }
?>