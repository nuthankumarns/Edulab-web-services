<?php
session_start();
//www.tritonetech.com/php_uploads/porsche/webservice/time.php
if(isset($_SESSION['UID']))
{
unset($_SESSION['UID']);
var_dump($_SESSION['UID']);
}
else
{
echo "sessions";
}


?>