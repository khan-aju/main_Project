<?php

class Database
{

   public function getConn()
   {
      $db_host = "localhost";
      $db_name = "mainproject";
      $db_user = "cms_www";
      $db_pass = "6p@MJoesi]HLejMh";

      $dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8';

      /* try {
         $db = new PDO($dsn, $db_user, $db_pass);

         $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
         echo $e->getMessage();
         exit;
      }*/
      return new PDO($dsn, $db_user, $db_pass);
   }
}
