<?php
require_once "session.class.php";
$oSession = new Session();
include "setup.php";
//include "config.php";
$option = $_REQUEST['option'];
$user1=$_REQUEST['user1'];
$pass=$_REQUEST['pass'];

//http://www.tritonetech.com/php_uploads/porsche/webservice/login_api.php?user1=admin&pass=qazwsx2010&option=1
//http://www.tritonetech.com/php_uploads/porsche/webservice/login_api.php?user1=local&pass=local123&option=1
//http://www.tritonetech.com/php_uploads/porsche/webservice/login_api.php?user1=dinesh&pass=dinesh123&option=1
		
			$query= $_DB->Query("select id,password,gid from jos_users where CONVERT(username USING latin1) COLLATE latin1_general_cs ='$user1'");
			//$a="select id,password,gid from jos_users where CONVERT(username USING latin1) COLLATE latin1_general_cs ='$user1'";
//echo $a;
			$count = $_DB->GetResultNumber($query);
				if($count==0)
				{
				$dataDB['Result']['Data'][0]=array('Status'=>"Incorrect username/password");
				echo stripslashes(json_encode($dataDB));			
				exit;
				}
			
		         				$ID['id'] = $_DB->GetResultValue($query, 0, "id");
					$dbpassword['password'] = $_DB->GetResultValue($query, 0, "password");
					$gid['gid']=$_DB->GetResultValue($query, 0 ,"gid");
					
       				 
					//echo $dbpassword['password'];
			list($md5pass, $saltpass) = split(":", $dbpassword['password']);			
			
			if (md5($pass.$saltpass)==$md5pass) 
			{			
			$_SESSION["UID"] = $ID['id'];
			$_SESSION['timestamp']=time();
			//$_SESSION["timestamp"]=
			$gid=$gid['gid'];	
			$ID=$ID['id'];
			//$_DB->Execute("update jos_porsche_student SET logged_in='1' WHERE user_id='$ID'");
			//return $ID['id'];
			} 
			else 			
			{
			$dataDB['Result']['Data'][0]=array('Status'=>"Password does not match");			
			echo json_encode($dataDB);			
			exit;			
			}





if($option==1 && $gid=='32')	 /*student authentication*/
{

$query=$_DB->Query("SELECT a.id, a.name, a.usertype, b. * , b.id AS student_id,b.student_id AS student_academic_id, c.class_id, c.module_id, d.module_name, d.id, d.module_order,f.name AS class_name, sum( self_reading + self_writing + group_reading + group_writing ) AS total_score
FROM jos_users AS a
LEFT JOIN jos_porsche_student AS b ON a.id = b.user_id
LEFT JOIN jos_porsche_student_module AS e ON b.id = e.student_id
LEFT JOIN jos_porsche_class_module AS c ON b.class_id = c.class_id
LEFT JOIN jos_porsche_module AS d ON c.module_id = d.id
LEFT JOIN jos_porsche_classes AS f ON b.class_id = f.id
WHERE a.id = '$ID' ");
/*$query=$_DB->Query("SELECT a.id, a.name, a.usertype, b. * , b.id AS student_id,b.student_id AS student_academic_id, c.class_id, c.module_id, c.task, d.module_name, d.id, d.module_order,f.name AS class_name, sum( self_reading + self_writing + group_reading + group_writing ) AS total_score
FROM jos_users AS a
LEFT JOIN jos_porsche_student AS b ON a.id = b.user_id
LEFT JOIN jos_porsche_student_module AS e ON b.id = e.student_id
LEFT JOIN jos_porsche_module AS d ON c.module_id = d.id
LEFT JOIN jos_porsche_classes AS f ON b.class_id = f.id
WHERE a.id = '$ID' ");*/
$count=$_DB->GetResultNumber($query);
	if($count == 0)
	{
	 $dataDB['Result']['Data'][]=array('Status'=>"User does not exist");
	echo json_encode($dataDB);
	exit;
	}
	else
	{
		$dataDB['Result']['Data'][0]['uid'] = ("$ID");
			
	for($i=0; $i < $count; $i++)
       					{
		//$dataDB['Result']['Data'][0]['group_id'] = ($_DB->GetResultValue($query, $i, "group_id"))!=('0'||'')?($_DB->GetResultValue($query, $i, "group_id")):"not assigned";
				$dataDB['Result']['Data'][$i]['user_type'] = $_DB->GetResultValue($query, $i, "usertype");
		$dataDB['Result']['Data'][$i]['student_id'] = ($_DB->GetResultValue($query, $i, "student_id"))!=('0'||'')?($_DB->GetResultValue($query,$i,"student_id")):"not assigned";
						$_SESSION['student_id']=$_DB->GetResultValue($query, $i, "student_id");
						$dataDB['Result']['Data'][$i]['name'] = $_DB->GetResultValue($query, $i, "name");
							$dataDB['Result']['Data'][$i]['age'] = $_DB->GetResultValue($query, $i, "age");
									$dataDB['Result']['Data'][$i]['sex'] = $_DB->GetResultValue($query, $i, "sex");
										$dataDB['Result']['Data'][$i]['race'] = $_DB->GetResultValue($query, $i, "race");
					$dataDB['Result']['Data'][$i]['dob'] = $_DB->GetResultValue($query, $i, "dob");
						$dataDB['Result']['Data'][$i]['student_academic_id'] = $_DB->GetResultValue($query, $i, "student_academic_id");
						$dataDB['Result']['Data'][$i]['class'] = $_DB->GetResultValue($query, $i, "class_name");
							$dataDB['Result']['Data'][$i]['school_id'] = $_DB->GetResultValue($query, $i, "school_id");
		$dataDB['Result']['Data'][$i]['group_name'] = ($_DB->GetResultValue($query, $i, "ability"))!=('0'||'')?($_DB->GetResultValue($query,$i,"ability")):"not assigned";
			$_SESSION['ability']=$_DB->GetResultValue($query,$i,'ability');
									$dataDB['Result']['Data'][$i]['avatar'] = $_DB->GetResultValue($query, $i, "avatar");
										$dataDB['Result']['Data'][$i]['trophies'] = $_DB->GetResultValue($query, $i, "trophies");
		$dataDB['Result']['Data'][$i]['module_id'] = ($_DB->GetResultValue($query, $i, "module_id")!=('0'||''))?($_DB->GetResultValue($query, $i, "module_id")):"not assigned";
		$dataDB['Result']['Data'][$i]['ordering'] = ($_DB->GetResultValue($query, $i, "module_order")!=('0'||''))?($_DB->GetResultValue($query, $i, "module_order")):"not assigned";
		$dataDB['Result']['Data'][$i]['progress'] = ($_DB->GetResultValue($query, $i, "module_name"))!=('0'||'')?($_DB->GetResultValue($query,$i,"module_name")):"not assigned";
		//$dataDB['Result']['Data'][$i]['task'] = ($_DB->GetResultValue($query, $i, "task"))!=('0'||'')?($_DB->GetResultValue($query,$i,"task")):"not assigned";
		$dataDB['Result']['Data'][$i]['total_score'] = ($_DB->GetResultValue($query, $i, "total_score"))!=('0'||'')?($_DB->GetResultValue($query,$i,"total_score")):"not assigned";        				
}
		$dataDB['Result']['Data'][0]['Status']=("Sucessfully Logged in!!!");
	echo stripslashes(json_encode($dataDB));
/*----------------------------------------------user_profile.php--------------------------------------------------------------*/
	}
}
elseif($option==1 && ($gid=='25' || $gid=='31'))
{
$query= $_DB->Query("select name,gid,usertype from jos_users where id='$ID'");
$count = $_DB->GetResultNumber($query);
	if($count == 0 )
    	{
        $dataDB['Result']['Data'][]=array('Status'=>"User does not exist");
	echo json_encode($dataDB);
	exit;
    	}
	else
   	 {
	$dataDB['Result']['Data'][0]['uid'] = ("$ID");
       		 for($i=0; $i < $count; $i++)
       		 {
			$dataDB['Result']['Data'][$i]['group_id'] = $_DB->GetResultValue($query, $i, "gid");
				$dataDB['Result']['Data'][$i]['user_type'] = $_DB->GetResultValue($query, $i, "usertype");
        	}
	$dataDB['Result']['Data'][0]['Status']=("Sucessfully Logged in!!!");
   	 }
	echo json_encode($dataDB);
}
else
{
	$dataDB['Result']['Data'][0]=array('Status'=>"Authetication Failed");
	echo json_encode($dataDB);
}
//print_r($_SESSION);

?>

