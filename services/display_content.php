<?php
include'set_xml_session.php';
include'setup.php';
//require_once "session.class.php";
//$oSession = new Session();
header("Content-Type: application/xml;charset=UTF-8");
$uid=$_SESSION['UID'];
//echo $uid;
$output="<?xml version='1.0' encoding='UTF-8'?><Result><content>";
//www.tritonetech.com/php_uploads/porsche/webservice/display_content.php?content_id=&task=

$task=$_REQUEST['task'];
$content_id=$_REQUEST['content_id'];
$self_content_id=$_REQUEST['self_content_id'];
/*$query=$_DB->Query("select a.id,a.ability,a.user_id,b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");

$ability=$_DB->GetResultValue($query,0,'ability');*/
$ability=$_SESSION['ability'];
//$task=$_REQUEST['task'];
//$a="select * from jos_porsche_".$ability."_lesson where id='$content_id' and task='$task'";
//echo $a;
$query = $_DB->Query("select * from jos_porsche_".$ability."_lesson where id='$content_id'");
$count=$_DB->GetResultNumber($query);
//echo $count;
	if($count==0)
	{
	$line="No content"."</content></Result>";
	echo $line;
	exit;
	}
	else
	{
$filename=$_DB->GetResultValue($query,0,'filename');
$filename=(($filename)!=(''))?($filename):"no_file";
//$dataDB['Result']['Data'][0]['Status']=($query)?("task assign success"):"task assign failure";
//$dataDB['Result']['Data'][$i]['total_score'] = ($_DB->GetResultValue($query, $i, "total_score"))!=('0'||'')?($_DB->GetResultValue($query,$i,"total_score")):"not
	$line=$_DB->GetResultValue($query,0,"content");
preg_match_all('/./u', $line, $array);
	$array=array_pop($array);
$i=0;
while($i<count($array)){
$b[]=array_slice($array,$i,100);
$i+=100;
}
//$output.="<content>";
for($i=0;$i<count($b);$i++)
{
$con[$i]=implode('',$b[$i]);
//echo $con[$i];
$output.="<para>".$con[$i]."</para>";
}
//$output.="</content>";
	$output.= "</content><content_id>".$_DB->GetResultValue($query,0,'id')."</content_id><filename>".$filename."</filename><benchmark>".$_DB->GetResultValue($query,0,'benchmark')."</benchmark><time>".$_DB->GetResultValue($query,0,'time')."</time>";
$_DB->Execute("update jos_porsche_student_module set cur_con_id='$content_id' where student_id='$student_id'");
}
echo $output."</Result>";
?>
