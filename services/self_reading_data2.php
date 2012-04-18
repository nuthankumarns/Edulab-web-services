<?php
include'set_session.php';
include'setup.php';
include'FastJSON.class.php';


//echo "<pre>";
//www.tritonetech.com/php_uploads/porsche/webservice/self_reading_data.php?school_id=&level_id&class_id&ability=&module_id=
$school_id=$_REQUEST['school_id'];
$level_id=$_REQUEST['level_id'];
$class_id=$_REQUEST['class_id'];
$module_id=$_REQUEST['module_id'];
$ability=$_REQUEST['ability'];
$query=$_DB->Query("SELECT a.id, b.student_id, c.id, c.time_taken
FROM jos_porsche_student AS a
JOIN jos_porsche_self AS b ON a.id = b.student_id
JOIN jos_porsche_self_reading_task AS c ON b.id = c.self_content_id
WHERE a.school_id = '$school_id'
AND a.level_id = '$level_id'
AND a.class_id = '$class_id'
AND b.ability = '$ability'
AND b.task = 'self_read'
AND b.module_id = '$module_id'");
//echo "<pre>";
$count=$_DB->GetResultNumber($query);
//echo $count;
if($count==0)
	{
	$dataDB['Result']['Data'][]['Status']="No data";
	//$dataDB['Result']['Data'][]['student_id']";
	echo FastJSON::encode($dataDB);
	exit;
	}
	else
	{
	for($i=0;$i<$count;$i++)
	{
	$id[$i]=$_DB->GetResultValue($query,$i,'id');
	}
	print_r($id);
		for($j=0;$j<(count($id));$j++)
		{
		$query=$_DB->Query("SELECT time_taken FROM jos_porsche_self_reading_task where self_content_id='$self_content_id[$j]'");
		$count=$_DB->GetResultNumber($query);
echo "count:".$count."<br/>";
			for($k=0;$k<$count;$k++)
			{
			$time[$j]=$_DB->GetResultValue($query,$k,'time_taken');
			//print_r($time);
			$tim=explode(' ',$time[$k]);
			//print_r($tim);
			unset($tim[0]);
			//print_r($tim);
			$time_seconds=0;
				foreach($tim as $key=>$str)
				{
				
				$str_time = $str;
				sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
				$time_seconds += isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
				}
			echo $time_seconds;
			$a[$j]=Sec2Time($time_seconds);
			//$time_seconds=0;
			
			
			}

		}
	}/*end of program*/
//print_r($time);
//print_r($tim);

print_r($a);
function Sec2Time($time){
  if(is_numeric($time)){
    $value = array(
      /*"years" => 0,*/ "days" => 0, "hours" => 0,
      "minutes" => 0, "seconds" => 0,
    );
    /*if($time >= 31556926){
      //$value["years"] = floor($time/31556926);
      $time = ($time%31556926);
    }*/
    if($time >= 86400){
      $value["days"] = floor($time/86400);
      $time = ($time%86400);
    }
    if($time >= 3600){
      $value["hours"] = floor($time/3600);
      $time = ($time%3600);
    }
    if($time >= 60){
      $value["minutes"] = floor($time/60);
      $time = ($time%60);
    }
    $value["seconds"] = floor($time);
    return (array) $value;
  }else{
    return (bool) FALSE;
  }
}/*end of Sec2Time()*/

/*function hms2sec ($hms) {
		list($h, $m, $s) = explode (":", $hms);
		$seconds=0;
		$seconds += (intval($h) * 3600);
		$seconds += (intval($m) * 60);
		$seconds += (intval($s));
		return $seconds;
		}*/
//echo hms2sec($tim[1]);

//echo $time_seconds;
//$time='60';
//print_r(Sec2Time($time_seconds));
//echo json_encode(Sec2Time($time_seconds));

//echo FastJSON::encode($dataDB);
?>
