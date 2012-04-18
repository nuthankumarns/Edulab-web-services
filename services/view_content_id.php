<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';
$uid=$_SESSION['UID'];

$query=$_DB->Query("select a.id,a.user_id,b.group_id,a.ability,b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$student_id=$_DB->GetResultValue($query,0,'student_id');
$ability=$_DB->GetResultValue($query,0,'ability');
$group_id=$_DB->GetResultValue($query,0,'group_id');

//http://www.tritonetech.com/php_uploads/porsche/webservice/view_content_id.php?module_id=&task=
$module_id=$_REQUEST['module_id'];
$task=$_REQUEST['task'];
if($module_id=='' || $task=='')
{
$dataDB['Result']['Data'][]=array('Status'=>"missing paramaters");
	echo json_encode($dataDB);
	exit;}
//$a="select id,module_id,$ability,group_self,$time from jos_porsche_lesson where module_id='$module_id' and group_self='0'";
//echo $a;
$query=$_DB->Query("select * from jos_porsche_".$ability."_lesson where module_id='$module_id' and task='$task'");

switch($task){
case'self_read':
case'self_write':
/*--------------------------------------------------------------------display_content.php?--------------------------------------------------------------*/
//var_dump($query);
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No content");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
	//for($i=0;$i<$count;$i++)
		//{
/*start tracking content id for writing tasks*/
$content_id=$_DB->GetResultValue($query,0,'id');

$qry=$_DB->Query("SELECT id from jos_porsche_self where student_id='$student_id' and module_id='$module_id' and $content_id IN (content_id) and ability='$ability' and task='$task'");
//var_dump($qry);
$count=$_DB->GetResultNumber($qry);
//echo $count;
	switch($count)
	{
	case'0':

	$_DB->Execute("insert into jos_porsche_self values('','$student_id','$module_id','$content_id','$ability','$task',NOW(),'')");
	$self_content_id=mysql_insert_id();
		//$_DB->Execute("insert into jos_porsche_self_reading_task('id','self_content_id') values('','$self_content_id')");
	$self_content_id=(string)$self_content_id;
		$dataDB['Result']['Data'][0]['self_content_id']=$self_content_id;
			$dataDB['Result']['Data'][0]['content_id']=$content_id;
		break;
	case'1':
$id=$_DB->GetResultValue($qry,0,'id');
$_DB->Execute("INSERT INTO jos_porsche_self SET id='$id', content_id='$content_id'
  ON DUPLICATE KEY UPDATE content_id=IF(0=FIND_IN_SET(VALUES(content_id), content_id), CONCAT(content_id, ',', VALUES(content_id)), content_id)");
	$self_content_id=$_DB->GetResultValue($qry,0,'id');
		$dataDB['Result']['Data'][0]['self_content_id']=$id;
			$dataDB['Result']['Data'][0]['content_id']=$content_id;
				break;
	//default:
	//$dataDB['Result']['Data'][0]['Status']=array("Student does not exist");
	}


//var_dump($self_content_id);
/*start tracking content id for writing tasks*/


			//$dataDB['Result']['Data'][$i]['group_self']=$_DB->GetResultValue($query,$i,'group_self');
	//$cont=explode(',',$con);
	//$dataDB['Result']['Data'][]['content_id']=$cont[0];
		//}
	}
break;
case'group_read':
case'group_write':
$count=$_DB->GetResultNumber($query);
	if($count==0)
	{
	$dataDB['Result']['Data'][]=array('Status'=>"No content");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
	$qry=$_DB->Query("SELECT * from jos_porsche_group where group_id='$group_id' and module_id='$module_id' and task='$task'");
//$a="SELECT * from jos_porsche_group where group_id='$group_id' and module_id='$module_id' and task='$task'";
//echo $a;
	//SELECT * from jos_porsche_group where 12 IN (student_id) and group_id='1' and module_id='1' and task='group_read'


	$cnt=$_DB->GetResultNumber($qry);
	//var_dump($nt);
	switch($cnt){
	case'0':
	$_DB->Execute("INSERT INTO jos_porsche_group values('','$group_id','$student_id','$module_id','$task')");
	$group_content_id=mysql_insert_id();
	$_SESSION['group_content_id']=$group_content_id;

	$dataDB['Result']['Data'][]['group_content_id']="$group_content_id";
/*INSERT INTO jos_porsche_group SET id=1, student_id=16
  ON DUPLICATE KEY UPDATE student_id=IF(0=FIND_IN_SET(VALUES(student_id), student_id), CONCAT(student_id, ',', VALUES(student_id)), student_id)*/

	break;
	case'1':
	//$qry=$_DB->Query("SELECT * from jos_porsche_group where $student_id IN (student_id) AND group_id='$group_id' and module_id='$module_id' and task='$task'");
	$id=$_DB->GetResultValue($qry,0,'id');
	$_SESSION['group_content_id']=$id;
	$_DB->Execute("INSERT INTO jos_porsche_group SET id='$id', student_id='$student_id'
  ON DUPLICATE KEY UPDATE student_id=IF(0=FIND_IN_SET(VALUES(student_id), student_id), CONCAT(student_id, ',', VALUES(student_id)), student_id)");
	
	$dataDB['Result']['Data'][]['group_content_id']="$id";
	
	break;
	}


}
}
echo FastJson::encode($dataDB);
	//echo $con;
	//print_r($cont);
	/*	for($i=0;$i<count($cont);$i++)
		{
		$content[$i]=$cont[$i];
		}
	//print_r($content);
	}

/*
// $query=$_DB->Query("select a.id,a.user_id,b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
// $student_id=$_DB->GetResultValue($query,0,'student_id');
$query=$_DB->Query("select cur_con_id from jos_porsche_student_module where student_id='$student_id'");
$cur_con_id=$_DB->GetResultValue($query,0,'cur_con_id');
//echo $cur_con_id;
//$key=array_search('$cur_con_id',$content);
if($cur_con_id==0)
{
$_DB->Execute("update jos_porsche_student_module set cur_con_id='$content[0]' where student_id='$student_id'");
$dataDB['Result']['Data'][]=array('current_content_id'=>"$content[0]");
	echo json_encode($dataDB);
	exit;
}
else
{	
$key = array_search($cur_con_id, $content);
//echo $key;
$key=$key+1;

//var_dump($key);
//echo (count($content)+1);
if($content[$key]=='')
	{
	$dataDB['Result']['Data'][]=array('Status'=>"Content complete");
	echo json_encode($dataDB);
	exit;
	}else
	{
	$_DB->Execute("update jos_porsche_student_module set cur_con_id='$content[$key]' where student_id='$student_id'");
	$dataDB['Result']['Data'][]=array('current_content_id'=>"$content[$key]");
	echo json_encode($dataDB);
	exit;
	}
	
}

/*if($cur_con_id==0)
{
$_DB->Execute("update jos_porsche_student_module set cur_con_id='$content[0]' where student_id='$uid'");
}
else
{
$query=$_DB->Query("select cur_con_id from jos_porsche_student_module where student_id='$uid'");
$cur_con_id=$_DB->GetResultValue($query,0,'cur_con_id');
echo $cur_con_id;
for($i=0;$i<count($content);$i++)
{
if ($cur_con_id == $content[$i]) 
		{
      		continue;
   		 }
   	  $_DB->Execute("update jos_porsche_student_module set cur_con_id='$content[$i]' where student_id='$uid'");
	
	
}
}*/

?>
