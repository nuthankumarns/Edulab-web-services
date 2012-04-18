<?php
include'set_session.php';
include'setup.php';

//www.tritonetech.com/php_uploads/porsche/webservice/create_group.php?ids=
$array=stripslashes($_REQUEST['ids']);
if($array=='')
{
$dataDB['Result']['Data'][0]=array('Status'=>"missing parameters");
	echo json_encode($dataDB);
	exit;
}
$a=json_decode($array,true);

$ids=implode(',',$a);

for($j=0;$j<count($a);$j++)
{
$id=$a[$j];
$query=$_DB->Query("SELECT * FROM jos_porsche_group_members
WHERE student_id = '$id'");
$count=$_DB->GetResultNumber($query);

//var_dump($query);
	if($count==1)
	{
	$group_id=$_DB->GetResultValue($query,0,'group_id');
	$_DB->Execute("DELETE FROM jos_porsche_group_members where group_id='$group_id'");
	$_DB->Execute("delete from jos_porsche_receiver where id='$group_id'");
	$_DB->Execute("DELETE FROM jos_porsche_group WHERE group_id='$group_id'");
	}
	
	
	/*$_DB->Execute("INSERT INTO jos_porsche_group SET id='$id', student_id='$student_id'
  ON DUPLICATE KEY UPDATE student_id=IF(0=FIND_IN_SET(VALUES(student_id), student_id), CONCAT(student_id, ',', VALUES(student_id)), student_id)");*/
	//SELECT * from jos_porsche_group where 12 IN (student_id) and group_id='1' and module_id='1' and task='group_read'

}
//var_dump($group_id);

		
//echo $ids;
$query=$_DB->Execute("insert into jos_porsche_receiver values('')");
$ins_id=mysql_insert_id();


if($query)
{
	for($i=0;$i<count($a);$i++)
	{
	$query=$_DB->Execute("insert into jos_porsche_group_members values('','$a[$i]','$ins_id')");
	//$query=$_DB->Execute("update jos_porsche_student_module set group_id='$ins_id' where student_id='$a[$i]'");
	//$_DB->Execute("INSERT INTO jos_porsche_student_module(student_id,group_id)values('$a[$i]','$ins_id') ON DUPLICATE KEY UPDATE group_id='$ins_id'");
 		/*if(!$query)
		{
		$dataDB['Result']['Data'][]=array('Status'=>"please assign module first");
		echo json_encode($dataDB);
		exit;

		}*/
	}

$dataDB['Result']['Data'][0]=array('Status'=>"group created",'Group_id'=>$ins_id);
	echo json_encode($dataDB);
	exit;
}
else
{
$dataDB['Result']['Data'][0]=array('Status'=>"cannot execute");
	echo json_encode($dataDB);
	exit;

}


//echo $ins_id;
//echo count($array);
//print_r($array);




?>
