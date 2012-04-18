<?php

error_reporting(E_ALL);
ini_set('display_errors','On');
//include'../setup.php';


$user="sopdb40";
$password="Tritone123";
$database="sopdb40";
mysql_connect("sopdb40.db.5236568.hostedresource.com","$user","$password") or die(mysql_error());
$link=mysql_select_db($database) or die( "Unable to select database");



$school_id=$_REQUEST['school_id'];
		 $level_id=$_REQUEST['level_id'];
		$class_id=$_REQUEST['class_id'];
		$module_id=$_REQUEST['module_id'];
		$ability=$_REQUEST['ability'];
		$task=$_REQUEST['task'];
$query="SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
		FROM jos_porsche_student AS a
		JOIN jos_porsche_self AS b ON a.id = b.student_id
		JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
		WHERE a.school_id = '$school_id'
		AND a.level_id = '$level_id'
		AND a.class_id = '$class_id'
		AND b.module_id = '$module_id'
		AND b.ability = '$ability'
		AND b.task = 'self_write'
		GROUP BY self_content_id
		ORDER BY time_taken  ASC";

//echo $query."jjjj";

 $res=mysql_query($query);

  $count = mysql_num_rows($res);


while($row1=mysql_fetch_array($res))
{

foreach ($row1 as $field=>$value)
echo $value."jjjj";
unset($field);

}

?>
