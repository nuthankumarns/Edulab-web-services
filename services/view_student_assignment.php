<?php
include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output.="<?xml version='1.0' encoding='utf-8'?><Result>";
$student_id=$_REQUEST['student_id'];
$task=$_REQUEST['task'];
$module_id=$_REQUEST['module_id'];
//$ability=$_REQUEST['ability'];
//www.tritonetech.com/php_uploads/porsche/webservice/view_student_assignment.php?student_id=&module_id=&task=&ability=
$query=$_DB->Query("SELECT ability FROM jos_porsche_student where id='$student_id'");
$ability=$_DB->GetResultValue($query,0,'ability');
$query=$_DB->Query("SELECT a.id AS qid, b.content
FROM jos_porsche_self AS a
JOIN jos_porsche_".$ability."_lesson AS b ON ( a.module_id = b.module_id
AND a.task = b.task )
WHERE a.student_id = '$student_id'
AND a.module_id = '$module_id'
AND a.task = '$task'");

//echo $query."hi";

$count=$_DB->GetResultNumber($query);
if($count==0)
{
$output.="<Status>"."user has not started the module"."</Status>";
}
else
{
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
$content="<para>".$con[$i]."</para>";
}
	for($i=0;$i<$count;$i++)
	{
	$output.="<Data><content>".$content."</content><qid>".$_DB->GetResultValue($query,$i,'qid')."</qid></Data>";
	} 


}

echo $output."</Result>"


?>
