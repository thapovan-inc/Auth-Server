<?php
class ApplicationConstants{
  //Config Variables
  public static $DBConfigRoot = "dblocals";
  public static $Host = "host";
  public static $Port = "port";
  public static $UserName = "username";
  public static $Password = "password";
  public static $DB = "db";
  public static $charset = "charset";
  public static $ApplicationConfigRoot = "application";
  public static $Salt = "salt";
  public static $RegExpConfigRoot ="regexp";
  public static $PasswordCharect = "pwd";

  // SP Names

  public static $GetUserByEmail = "CALL auth.sp_get_user_by_email(?)";
  public static $GetUserByMob = "CALL auth.sp_get_user_by_mob(?)";
  public static $CreateUSer ="CALL sp_create_user(?,?,?,?)";
  public static $ManageForgotPassword  ="CALL sp_manage_forgot_password(?,?,?,?,?)";
  public static $VerifyToken = "CALL sp_verify_token(?,?)";
  public static $ResetPassword = "CALL sp_reset_password(?,?,?)";

  //Messages/Info 
   
  public static $RequiredParam = "required parameter missing";
  public static $InvalidMob = "Invalid mobile no";
  public static $InvalidEmail = "Invalid emailId";
  public static $Invalid = "invalid";
  public static $PasswordConstraints = "Password length should be greater than 6 and it should contain at least 1 number,1 caps & 1 symbol";
  public static $ComparePassword = "Confirm password and password doesnt match";
  public static $ValidUserId = "Please provide valid user id";
  public static $InvalidMobOrUserNotReg = "incorrect mobile no/user not resgistered";
  public static $InvalidEmailOrUserNotReg="incorrect email/user not resgistered";
  public static $EmailAlreadyReg = "Email already registered";
  public static $MobAlreadyReg = "Mobile number already registered";
  public static $PasswordReset = "password reset succesfully";
  public static $IncorrectPwd = "incorrect password";

  //Result Array Index
  public static $ReqStat ="reqstat";
  public static $Message = "message";
  public static $Parameters = "parameters";


}
?>