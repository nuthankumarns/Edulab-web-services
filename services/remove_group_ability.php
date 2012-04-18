<?php
include'set_session.php';
include "setup.php";
/*session_start();

//http://www.tritonetech.com/php_uploads/porsche/webservice/remove_group_ability.php?id=

if(isset($_SESSION['UID']))
{
$uid=$_SESSION['UID'];*/

$id=$_REQUEST['id'];
//echo $id;


if($id=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"parameters missing");
echo json_encode($dataDB);
exit;
}
//for($i=0;$i<count($id);$i++)
//{
$query=$_DB->Execute("update jos_porsche_student set ability='' where id='$id'");
//$_DB->Execute("update jos_porsche_group_members set group_id='' where student_id='$id'");

	if($query==true)
	{
	$_DB->Execute("delete from jos_porsche_group_members where student_id='$id'");
	$_DB->Execute("update jos_porsche_student_module set group_id='0' where student_id='$id'");
	$dataDB['Result']['Data'][]=array('Status'=>"Success in removing student",'Student_id'=>"$id");
	}
	else
	{
	$dataDB['Result']['Data'][]=array('Status'=>"couldn't complete the task");
	}
//}
echo json_encode($dataDB);
/*}
else
{
$dataDB['Result']['Data'][]=array('Status'=>"Session Expired-Please login again");
echo json_encode($dataDB);
}*/
?>
