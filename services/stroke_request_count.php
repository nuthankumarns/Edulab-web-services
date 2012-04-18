<?php
session_start();
/*$user="sopdb40";
$password="Tritone123";
$database="sopdb40";
$con=mysql_connect("sopdb40.db.5236568.hostedresource.com","$user","$password") or die(mysql_error());
global $con;
$link=mysql_select_db($database) or die( "Unable to select database");*/
//www.tritonetech.com/php_uploads/porsche/webservice/stroke_request_count.php

global $link;
include 'setup.php';
require 'FastJSON.class.php';
//include 'mysql.php';
$uid=$_REQUEST['UID'];
$uid='107';
//global $uid;
echo $uid;

//global $id;

//global $_DB;
//www.tritonetech.com/php_uploads/porsche/webservice/stroke_request_count.php
//CLS_MYSQL
class stroke 
{
var $b;
	public function Get_result() 
	{
$uid='107';
echo $uid;
//$this->_DB->Connect();
//$this->_DB->Execute("update jos_porsche_student_module SET stroke_req=stroke_req+1 where student_id='$id'");
$this->query=$_DB->Query("select a.user_id,a.id,b.student_id from jos_porsche_student as a left join jos_porsche_student_module as b on a.id=b.student_id where user_id='$uid'");
var_dump($this->query);
	$id=$this->_DB->GetResultValue($query,0,'student_id');
//echo $id;
	$this->_DB->Query("select * from jos_porsche_student");
//var_dump($b);
//print_r($a);
	//var_dump($this->query);
//$this->GetResultValue("
		//if($query)
		//{
		$dataDB['Result']['Data'][]=array('number_of_requests'=>"10");
		//}
		//else
		//{
		//$dataDB['Result']['Data'][]=array('Status'=>"cannot update");
		//}
	return $dataDB;
	}
	public function Json_result($dataDB1)
	{
	echo FastJSON::encode($dataDB1);
	//echo json_encode($dataDB1);
	}
}
$a=new stroke($uid);
var_dump($a);
//var_dump($a);
$dataDB1=$a->Get_result();
//print_r($dataDB1);
$a->Json_result($dataDB1);


?>