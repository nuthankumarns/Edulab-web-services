<?php
    //SETUP.PHP

    //YOUR MySQL CONNECTION DETAILS
$DATABASE["server"] = "localhost";
$DATABASE["port"] = "3306";
$DATABASE["username"] = "edulabcl_buddy";
$DATABASE["password"] = "Tritone123#";
$DATABASE["database"] = "edulabcl_porsche";
   
  
    //Including mysql.php , Remember to give the right path to this file
    include "mysql.php";

    // Initialaizing connection to database using CLS_MYSQL =20
    $_DB = new CLS_MYSQL($DATABASE);
   
    //Connecting to database
    $_DB->Connect();



?>
