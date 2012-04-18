<?php
require_once "session.class.php";
$oSession = new Session();

include 'setup.php';

//print_r($_SESSION);
//www.tritonetech.com/php_uploads/porsche/webservice/logout_api.php
if(isset($_SESSION['UID']))
{
$id=$_SESSION['UID'];
//var_dump($id);
//$_DB->Execute("UPDATE jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id SET time_taken='0' where a.user_id='$id'");
$dataDB['Result']['Data'][]=array('Status'=>"Successfully logged out",'ID'=>"$id");

echo json_encode($dataDB);
session_unset();
session_destroy();
}
else
{
$dataDB['Result']['Data'][0]=array('Status'=>"Session Expired");
echo json_encode($dataDB);
session_unset();
session_destroy();
}


?>
