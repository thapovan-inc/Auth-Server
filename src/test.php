<?php
require_once __DIR__.'\Auth.php';
require_once __DIR__.'\Config.php';
require_once __DIR__.'\Repo.php';
require_once __DIR__.'\Constants.php';

  $auth = new Auth();
  $result = $auth->nativeLogin("anand981798@gmail.com","Sairam@89");
 // $result = $auth->nativeSignupAction("anand981798@gmail.com","9176092991","Welcome@123","Welcome@123",'[{"ipaddress":"192.1681.10","host":"domain.com"}]');
 // $result = $auth->forgotPassword("anand981798@gmail.com",'[{"ipaddress":"192.1681.10","host":"domain.com"}]');
 // $result = $auth ->resetPassword("59a91ce8cb63c","anand981798@gmail.com","Sairam@89","Sairam@89",'[{"ipaddress":"192.1681.10","host":"domain.com"}]');
  print_r($result);  


?>