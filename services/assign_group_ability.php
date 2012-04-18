<?php
/*API to assign group ability LA/HA/MA*/
include 'set_session.php';
include "setup.php";

//$id[]=$_REQUEST['id'];
//var_dump($id);
$array=stripslashes($_REQUEST['id']);
//print_r($array);
$ability=$_REQUEST['ability'];
//print_r($id);
//http://www.tritonetech.com/php_uploads/porsche/webservice/assign_group_ability.php?id=&ability=
//$id='$_SESSION['UID'];
//if(isset($_SESSION['UID']))
//{
//var_dump($ability);
$a=json_decode($array,true);
//print_r($a);
//$ids=implode(',',$a);
//print_r($ids);
if($array=='' || $ability=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"parameters missing");
echo json_encode($dataDB);
exit;
}

for($j=0;$j<count($a);$j++)
{
$_DB->Execute("update jos_porsche_student set ability='$ability' where id='$a[$j]'");
}
$last_insert_id=mysql_insert_id();
if($last_insert_id>=0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"Success in adding student");
	echo json_encode($dataDB);
	}
	else
	{
	$dataDB['Result']['Data'][]=array('Status'=>"couldn't add to the group");
	echo json_encode($dataDB);
	}
//echo "nuthan";
//$query=$_DB->Query("select id from jos_porsche_receiver where
//$query=$_DB->Execute("insert into jos_porsche_receiver values('',
//echo json_encode($dataDB);

//}
//else
//{
//header("location:logout_api.php")
//}
//session_destroy();
?>

