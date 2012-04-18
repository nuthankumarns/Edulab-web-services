<?php
require_once "session.class.php";
$oSession = new Session();
header("content-type:application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
include 'setup.php';

//print_r($_SESSION);
//www.tritonetech.com/php_uploads/porsche/webservice/logout_api.php
if(isset($_SESSION['UID']))
{
$id=$_SESSION['UID'];
//var_dump($id);
//$_DB->Execute("UPDATE jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id SET time_taken='0' where a.user_id='$id'");
$output.="<Status>"."Successfully logged out"."</Status>";
session_unset();
session_destroy();
}
else
{
$output.="<Status>"."Session Expired"."</Status>";
session_destroy();
}
echo $output."</Result>";

?>
