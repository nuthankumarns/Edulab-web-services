<?php
//include'set_xml_session.php';
include'setup.php';
header("content-type:application/xml");
$output.="<?xml version='1.0' encoding='utf-8'?><Result>";
$content=$_REQUEST['content'];
$student_id=$_REQUEST['student_id'];
$remediation_module_id=$_REQUEST['remediation_module_id'];
$task=$_REQUEST['task'];
if($content=='' || $student_id=='' || $task=='' || $remediation_module_id=='')
{
$output.="<Status>"."missing parameters"."</Status>";
echo $output;
exit;

}
//www.tritonetech.com/php_uploads/porsche/webservice/student_remediation.php?student_id=&remediation_module_id=&content=&task=
/*AND $module_id
IN (
module_id
)*/
$qry=$_DB->Query("SELECT id
FROM jos_porsche_remediation
WHERE student_id = '$student_id'
AND remediation_module_id='$remediation_module_id'
AND task = '$task'");
$count=$_DB->GetResultNumber($qry);
//echo $count;
switch($count){
case'0':

$query=$_DB->Execute("INSERT INTO jos_porsche_remediation VALUES('','$student_id','$remediation_module_id','$content','$task')");
break;
case'1':
$id=$_DB->GetResultValue($qry,0,'id');
$query=$_DB->Execute("INSERT INTO jos_porsche_remediation
SET id = '$id',
content = '$content' ON DUPLICATE KEY UPDATE  content = CONCAT( content, '\n',
VALUES (
content
) ) ");
/*$query=$_DB->Execute("INSERT INTO jos_porsche_remediation
SET id = '$id',
module_id = '$module_id',
content = '$content' ON DUPLICATE KEY UPDATE module_id = IF( 0 = FIND_IN_SET( VALUES (
module_id
), module_id ) , CONCAT( module_id, ',',
VALUES (
module_id
) ) , module_id ) , content = CONCAT( content, '\n',
VALUES (
content
) ) ");*/
break;
}
if($query)
{
$output.="<Status>"."student remediation content upload successful"."</Status>";
}
else
{
$output.="<Status>"."student remediation content upload failure"."</Status>";
}
echo $output."</Result>";

?>
