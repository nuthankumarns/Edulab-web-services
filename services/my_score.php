<?php
include 'set_session.php';
include 'setup.php';
$uid=$_SESSION['UID'];
//$uid='459';
$query=$_DB->Query("SELECT sum( self_reading + self_writing + group_reading + group_writing ) AS total_score
FROM jos_porsche_student AS a
JOIN jos_porsche_student_module AS b ON a.id = b.student_id
WHERE a.user_id = '$uid'");
/*$query=$_DB->Query("SELECT sum( group_reading + group_writing ) AS total_score
FROM jos_porsche_student AS a
JOIN jos_porsche_student_module AS b ON a.id = b.student_id
WHERE a.user_id = '$uid'");*/
//var_dump($query);
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="admin has not triggered the module";
}
else
{
$dataDB['Result']['Data'][0]['total_score']=$_DB->GetResultValue($query,0,'total_score')!=''?$_DB->GetResultValue($query,0,'total_score'):"No score";
}

echo json_encode($dataDB);
?>
