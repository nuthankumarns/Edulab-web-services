<?php
include'set_session.php';
include'setup.php';
//www.edulabcloud.com/webservices/module_check.php?class_id=&subject_id=
$class_id=$_REQUEST['class_id'];
//$module_id=$_REQUEST['module_id'];
$subject_id=$_REQUEST['subject_id'];
//$a="SELECT b.module_name FROM jos_porsche_class_module AS a LEFT JOIN jos_porsche_module AS b ON a.module_id=b.module_id WHERE a.class_id='$class_id' AND a.subject_id='$subject_id'";
//echo $a;
$query=$_DB->Query("SELECT b.module_name FROM jos_porsche_class_module AS a LEFT JOIN jos_porsche_module AS b ON a.module_id=b.id WHERE a.class_id='$class_id' AND a.subject_id='$subject_id'");

$count=$_DB->GetResultNumber($query);
if($count==0)
{
	$dataDB['Result']['Data'][0]['Status']="module not started yet";
}
else
{
	$dataDB['Result']['Data'][0]['Status']=$_DB->GetResultValue($query,0,'module_name');
}
echo json_encode($dataDB);
?>
