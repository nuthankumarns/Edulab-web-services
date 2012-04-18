<?php
//include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
//www.tritonetech.com/php_uploads/porsche/webservice/view_remediation_content.php
$output="<?xml version='1.0' encoding='utf-8'?><Result><content>";
$uid=$_SESSION['UID'];
//$uid='10';
$query=$_DB->Query("SELECT b.content,b.id
FROM jos_porsche_class_module AS a
JOIN jos_porsche_remediation AS b ON a.module_id = b.remediation_module_id
AND a.task = b.task
WHERE b.student_id = '$uid'");
$count=$_DB->GetResultNumber($query);
//echo $count;
	if($count==0)
	{
	$output.="No remediation content"."</content></Result>";
	echo $output;
	exit;
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
$output.="<para>".$con[$i]."</para>";
}
//$output.="</content>";
	$output.= "</content><remediation_content_id>".$_DB->GetResultValue($query,0,'id')."</remediation_content_id>";

}
echo $output."</Result>";
?>
