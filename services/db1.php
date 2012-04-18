<?php
$user="sopdb40";
$password="Tritone123";
$database="sopdb40";
$con=mysql_connect("sopdb40.db.5236568.hostedresource.com","$user","$password") or die(mysql_error());
$link=mysql_select_db($database) or die( "Unable to select database");
?>
