<?php
include'set_xml_session.php';
include'setup.php';
$uid=$_SESSION['UID'];

header("Content-Type: application/xml");
$output="<?xml version='1.0' encoding='utf-8'?><Result>";
$query=$_DB->Query("select a.id,a.user_id,a.ability,b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$ability=$_DB->GetResultValue($query,0,'ability');
/*
switch($ability)
{
case'LA':
$time='Ltime';
break;
case'MA':
$time='Mtime';
break;
case'HA':
$time='Htime';
break;
}*/
//www.tritonetech.com/php_uploads/porsche/webservice/display_group_content.php?module_id=&task=
$task=$_REQUEST['task'];
$module_id=$_REQUEST['module_id'];
$query=$_DB->Query("select a.id,a.ability,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');
$group_id=$_DB->GetResultValue($query,0,'group_id');
//$query=$_DB->Query("select id,content,$ability,$time from jos_porsche_lesson where module_id='$module_id' and group_self='1'");
$qury=$_DB->Query("select * from jos_porsche_".$ability."_lesson where module_id='$module_id' and task='$task'");
$count=$_DB->GetResultNumber($qury);
if($count==0)
{$output.="<Status>"."No content"."</Status></Result>";
	echo $output;
	exit;
}
else
{
	//$qry=$_DB->Query("SELECT * from jos_porsche_group where $student_id IN (student_id) AND group_id='$group_id' and module_id='$module_id' and task='$task'");
	$qry=$_DB->Query("SELECT * from jos_porsche_group where group_id='$group_id' and module_id='$module_id' and task='$task'");
	//SELECT * from jos_porsche_group where 12 IN (student_id) and group_id='1' and module_id='1' and task='group_read'


	$cnt=$_DB->GetResultNumber($qry);
	
	switch($cnt){
	case'0':
	$_DB->Execute("INSERT INTO jos_porsche_group values('','$group_id','$student_id','$module_id','$task')");
	$group_content_id=mysql_insert_id();
	$output.="<group_content_id>".$group_content_id."</group_content_id>";
/*INSERT INTO jos_porsche_group SET id=1, student_id=16
  ON DUPLICATE KEY UPDATE student_id=IF(0=FIND_IN_SET(VALUES(student_id), student_id), CONCAT(student_id, ',', VALUES(student_id)), student_id)*/
	for($i=0;$i<$count;$i++)
	{
	
	$output.="<Data><content_id>".$_DB->GetResultValue($qury,$i,'id')."</content_id>
			<content>".$_DB->GetResultValue($qury,$i,'content')."</content>
			<benchmark>".$_DB->GetResultValue($qury,$i,'benchmark')."</benchmark>
			
			<time>".$_DB->GetResultValue($qury,$i,'time')."</time></Data>";
	//$dataDB['Result']['Data'][$i]["group_content_id"]="$id";
	}
	break;
	case'1':
	//$qry=$_DB->Query("SELECT * from jos_porsche_group where $student_id IN (student_id) AND group_id='$group_id' and module_id='$module_id' and task='$task'");
	$id=$_DB->GetResultValue($qry,0,'id');
	$_DB->Execute("INSERT INTO jos_porsche_group SET id='$id', student_id='$student_id'
  ON DUPLICATE KEY UPDATE student_id=IF(0=FIND_IN_SET(VALUES(student_id), student_id), CONCAT(student_id, ',', VALUES(student_id)), student_id)");
	
	$output.="<group_content_id>".$id."</group_content_id>";
	for($i=0;$i<$count;$i++)
	{
	
	$output.="<Data><content_id>".$_DB->GetResultValue($qury,$i,'id')."</content_id>
			<content>".$_DB->GetResultValue($qury,$i,'content')."</content>
			<benchmark>".$_DB->GetResultValue($qury,$i,'benchmark')."</benchmark>
			<time>".$_DB->GetResultValue($qury,$i,'time')."</time></Data>";
		}
	break;
	}




	
}

echo $output."</Result>";

?>
