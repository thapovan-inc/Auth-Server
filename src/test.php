<?php
require_once __DIR__.'\Auth.php';
require_once __DIR__.'\Config.php';
require_once __DIR__.'\Repo.php';

 $auth = new Auth();
 //$result = $auth->nativeLogin("anand981794@gmail.com","welcome@123",[]);
 $result = $auth->nativeSignupAction("anand981794@gma.com","9276091992","Welcome@123","Welcome@123","anand981794@gma.com");
 print_r($result);  


?>