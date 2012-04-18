<?php
/*
 * @author sudhir vishwakarma <sudhir.vis@gmail.com>
 * @copyright Sudhir Vishwakarma  (www.itwebinfo.com)
 * 
 * @version 0.1 20100602
 * How to use :
 *  Create your database in MySQL, and create a table in which
 *  to store your session information.  The example code below
 *  uses a table called "session".  Here is the SQL command
 *  which created it:
 * 
 *  CREATE TABLE sessions (id varchar(32) NOT NULL,access
 *  int(10) unsigned,data text,PRIMARY KEY (id));
*/
require_once "session.class.php";
$oSession = new Session();
$uid=$_SESSION['uid'] = "63";
//echo Session::read('4ec755f49559e17064802cf0964fb35f');
echo $oSession->read(session_id());
//print_r(Session::users());
print_r($oSession->users());
//$_SESSION['uid'] = "63"; // Comment this Once sessoin is set
//$_SESSION['test'] = "great"; // Comment this Once sessoin is set
?>

