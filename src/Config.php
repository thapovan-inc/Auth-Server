<?php
  class Config{
      public static $config ;
      public static function getConfig(){
        try{
        if(Config::$config == null){
          Config::$config  = parse_ini_file(__DIR__."/config.ini", true);
        }
      }
      catch(Exception $ex){

      }
  }
  }
?>