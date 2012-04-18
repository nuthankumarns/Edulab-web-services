<?php
include'set_session.php';
include'setup.php';
//var_dump($_SESSION['UID']);

//$idletime=60;//after 60 seconds the user gets logged out
//$oSession->clean($idletime);
/*if (time()-$_SESSION['timestamp']>$idletime){
header("location:logout_api.php");
}else{
    $_SESSION['timestamp']=time();
}*/
/*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minates ago
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp*/

//on session creation
/*if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minates ago
    session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}*/


//if(isset($_SESSION['UID']))
//{
//www.tritonetech.com/php_uploads/porsche/webservice/user_profile.php
//$user_id='65';
//$user_id=$_REQUEST['user_id'];
$uid=$_SESSION['UID'];
//var_dump($uid);
$query= $_DB->Query("SELECT a . * , b.student_id, b.task, b.module_id, c.id, c.module_name
FROM jos_porsche_student AS a
LEFT JOIN jos_porsche_student_module AS b ON a.id = b.student_id
LEFT JOIN jos_porsche_module AS c ON b.module_id = c.id
WHERE a.user_id = '$uid'");
			$count = $_DB->GetResultNumber($query);
				if($count==0)
				{
				$dataDB['Result']['Data'][0]=array('Status'=>"User does not exist");			
				echo json_encode($dataDB);			
				exit;
				}
				else
				{ //	level_id 	class_id 	 	 	 	
					for($i=0; $i < $count; $i++)
       					{
           				 $dataDB['Result']['Data'][$i]['student_id'] = $_DB->GetResultValue($query, $i, "id");
						$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i, "name");
								$dataDB['Result']['Data'][$i]['age'] = $_DB->GetResultValue($query, $i, "age");
									$dataDB['Result']['Data'][$i]['sex'] = $_DB->GetResultValue($query, $i, "sex");
										$dataDB['Result']['Data'][$i]['race'] = $_DB->GetResultValue($query, $i, "race");
					$dataDB['Result']['Data'][$i]['dob'] = $_DB->GetResultValue($query, $i, "dob");
						$dataDB['Result']['Data'][$i]['student_academic_id'] = $_DB->GetResultValue($query, $i, "student_id");
							$dataDB['Result']['Data'][$i]['school_id'] = $_DB->GetResultValue($query, $i, "school_id");
								$dataDB['Result']['Data'][$i]['group_name'] = $_DB->GetResultValue($query, $i, "ability");
									$dataDB['Result']['Data'][$i]['avatar'] = $_DB->GetResultValue($query, $i, "avatar");
										$dataDB['Result']['Data'][$i]['trophies'] = $_DB->GetResultValue($query, $i, "trophies");

	$dataDB['Result']['Data'][$i]['module_id'] = ($_DB->GetResultValue($query, $i, "module_id")!=('0'||''))?($_DB->GetResultValue($query, $i, "module_id")):"not assigned";
		$dataDB['Result']['Data'][$i]['progress'] = ($_DB->GetResultValue($query, $i, "module_name"))!=('0'||'')?($_DB->GetResultValue($query,$i,"module_name")):"not assigned";
			$dataDB['Result']['Data'][$i]['task'] = ($_DB->GetResultValue($query, $i, "task"))!=('0'||'')?($_DB->GetResultValue($query,$i,"task")):"not assigned";
        				}
echo stripslashes(json_encode($dataDB));
/*-------------------------------------------------------------------------------------view_modules.php----------------------------------------------------------*/
				}
/*}
else
{
header("location:logout_api.php");
/*$dataDB['Result']['Data'][0]=array('Status'=>"Session Expired-Please login again");			
				echo json_encode($dataDB);			
				exit;*/
//}

//unset($_SESSION['UID']);
//session_destroy();
?>	
