<?php
include 'setup.php';
//function Get_student_id($uid)
//{
$query=$_DB->Query("select a.id,a.user_id,b.student_id,b.group_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
$id=$_DB->GetResultValue($query,0,'student_id');
//return $student_id;
//}
?>