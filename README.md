# Auth-Server
 This source will give you genric method for nativelogin,regsitration ,forgot password and social login .Please refer wiki for more details.

**Minimum Requirement**:

PHP > 5.6
MYSQL >= 5.7 
 
# Native login

Method will take email id or mob ,password and done by as input.done by will be a json stating the ip address,domain info e.t.c

#
```php
<?php
  require_once __DIR__.'\Auth.php';
  $auth = new Auth();
  $result = $auth->nativeLogin("anand981794@gmail.com","welcome@123");
  //write your own business logic with the result set
  print_r($result);  
  ?>
  ```
##
# Registration

This is a basic registration with email id ,mobile number and password as parameter along with the $doneBy param.

#
```php
<?php
  require_once __DIR__.'\Auth.php';
  $auth = new Auth();
  $result = $auth->nativeSignupAction("anand981794@gmal.com","9276091992","Welcome@123","Welcome@123",[{"ip-address":"192.168.1.90","domain":"host.com"}]);
 // write your own business logic with the result set
  print_r($result);  
  ?>
  ```
##

# Forgot Password

This method will take the email id as input and generate a token for password reset 

#
```php
<?php
  require_once __DIR__.'\Auth.php';
  $auth = new Auth();
  $result = $auth->forgotPassword("anand981794@gmal.com",[{"ip-address":"192.168.1.90","domain":"host.com"}]);
  //write your own business logic with the result set
  print_r($result);  
  ?>
```
##

# Reset Password

This method will reset the password based on the token generated on forgot password

#
```php
<?php
  require_once __DIR__.'\Auth.php';
  $auth = new Auth();
  $result = $auth->resetPassword("599d830ece128","anand981794@gma.com","Abcdef@89","Abcdef@89",[{"ip-address":"192.168.1.90","domain":"host.com"}]);
  //write your own business logic with the result set
  print_r($result);  
  ?>
```
##

