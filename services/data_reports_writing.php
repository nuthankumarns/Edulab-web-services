<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';

$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
$task=$_REQUEST['task'];

/*task:0 user completed asc
task:1 users completed desc
task:2 average of users desc
task:3 average of users asc */
/*shortest time to complete a word*/
//www.tritonetech.com/php_uploads/porsche/webservice/data_reports_writing.php?school_id=&level_id=&class_id=&module_id=&ability=&task=
/*SELECT a.name,c.word, c.self_error
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_self AS b ON a.id = b.student_id
LEFT JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.ability = 'MA'
AND a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.module_id = '$module_id'
AND b.task = 'self_write'
ORDER BY self_error DESC
LIMIT 1*/

/*word with highest no. of errors*/
/*$query=$_DB->Query("SELECT c.word, sum( c.self_error )
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '1'
AND a.level_id = '1'
AND a.class_id = '1'
AND b.module_id = '1'
AND b.ability = 'LA'
AND b.task = 'self_write'
AND c.self_error > '0'
GROUP BY (
c.word
)
ORDER BY sum( c.self_error ) DESC
LIMIT 0 , 10 ");*/
/*users with average*/
/*SELECT a.name, c.self_content_id, avg( c.self_count ) , count( self_count )
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '1'
AND a.level_id = '1'
AND a.class_id = '1'
AND b.module_id = '1'
AND b.ability = 'LA'
AND b.task = 'self_write'
AND c.self_error > '0'
GROUP BY (
c.self_content_id
)
ORDER BY avg( c.self_count ) DESC*/
switch($task){
case'0':
/*order by users completed in shortest time */
$query=$_DB->Query("SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
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
ORDER BY time_taken  ASC");
break;
case'1':
/* order by users complete in longest time */
$query=$_DB->Query("SELECT a.name,c.self_content_id, SEC_TO_TIME( sum( TIME_TO_SEC( c.self_time + c.unaided_time ) ) ) AS time_taken
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
ORDER BY  time_taken  DESC");
break;
case'2':
/*order by users average highest first*/
$query=$_DB->Query("SELECT a.name, c.self_content_id, avg( c.self_count ) AS average , count( self_count ) AS number_of_words
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.module_id = '$module_id'
AND b.ability = '$ability'
AND b.task = 'self_write'
AND c.self_error > '0'
GROUP BY (
c.self_content_id
)
ORDER BY avg( c.self_count ) DESC");
break;
case'3':
/*order by users average lowest first*/
$query=$_DB->Query("SELECT a.name, c.self_content_id, avg( c.self_count ) AS average , count( self_count ) AS number_of_words
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.module_id = '$module_id'
AND b.ability = '$ability'
AND b.task = 'self_write'
AND c.self_error > '0'
GROUP BY (
c.self_content_id
)
ORDER BY avg( c.self_count ) ASC");
break;
default:
$dataDB['Result']['Data'][0]['Status']="parameters missing";
break;
}

//var_dump($query);
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
{
$dataDB['Result']['Data'][0]['Status']="No users exist";
}
else
{
	for($i=0;$i<$count;$i++)
	{
	$dataDB['Result']['Data'][$i]['name']=$_DB->GetResultValue($query,$i,'name');
		switch($task){
		case'0':
		case'1':
		$dataDB['Result']['Data'][$i]['time_taken']=$_DB->GetResultValue($query,$i,'time_taken');
		break;
		case'2':
		case'3':
		$dataDB['Result']['Data'][$i]['average']=$_DB->GetResultValue($query,$i,'average');
		$dataDB['Result']['Data'][$i]['number_of_words']=$_DB->GetResultValue($query,$i,'number_of_words');
		break;
		}

	}

}
echo FastJSON::encode($dataDB);



/*word with most hits*/
/*$query=$_DB->Query("SELECT a.id, b.student_id, c.self_content_id, c.self_count, c.word
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_writing_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '1'
AND a.level_id = '1'
AND a.class_id = '1'
AND b.ability='LA'
AND b.task = 'self_write'
AND b.module_id = '1'
AND c.self_count > '0'
ORDER BY self_count ASC");*/




?>
