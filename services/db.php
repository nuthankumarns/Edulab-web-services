<?php

class database {


	private $g_dbname = 'sopdb40';
	private $g_host = 'sopdb40.db.5236568.hostedresource.com';
	private $g_uname = 'sopdb40';
	private $g_pwd = 'root';		
	private $conn;
		
		//Here you dont even need to call select_db() separately, you can directly
		//play with DB after executing this constructor.
		function __construct() {
		$this->connect();
			$this->select_db($this->g_dbname);
		}
		
		function connect() {
			$this->conn = mysql_connect($this->g_host, $this->g_uname, $this->g_pwd);

		}
		
		
		//Step 3: Select one Database after connection
		
		function select_db($dbname) {
		  try {
			mysql_select_db($dbname, $this->conn);
			}catch(Exception $e) {
					$this->show_error("Error Connecting to DB " . $e->getMessage());
			}
		}
		
		//Rest of Steps: You can play with the DB Connections now.
		function execute($strquery) {
		 $ret = false;
		  try {	
				$ret = mysql_query($strquery);
			}catch (Exception $e) {
				$this->show_error("Error executing query  " . $e->getMessage());
				return $ret;
			}
			return $ret;
		}
		
		function select($strquery) {
			$result=null;
		  try {	
			 $result = mysql_query($strquery);
			}catch (Exception $e) {
				$this->show_error("Error Selecting from DB " . $e->getMessage());
				return $result;
			}
			
			return $result;
		}
		
		function show_error($errstr) {
			session_start();
			$_SESSION['message'] = $errstr;
			header("Location: error.php");
		}
		
		function close_connection() {
		  try {
		      if (!$this->conn) {
				    if(mysql_close($this->conn)) 
				    { 
				      $this->conn = null; 
				    }
				}
			}catch(Exception $e) {
				$this->show_error("Error Closing DB Connection " . $e->getMessage());
			}
			
		
		}
		
		function data_to_xml($result) {
		$i=0;
		$xml="<data>";
		$xml=$xml."<totalrecords>".mysql_num_rows($result)."</totalrecords>";
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$xml=$xml."<row>";
			$i=0;
			while($i < mysql_num_fields($result)) {
				$meta = mysql_fetch_field($result, $i);
				//echo $meta->name.'<br>';
				$xml=$xml."<".$meta->name.">";
				//echo $row[$meta->name];
				$xml=$xml.$row[$meta->name];
				$xml=$xml."</".$meta->name.">";
				$i++;
			}
			$xml=$xml."</row>";
		}
		$xml=$xml."</data>";
		return $xml;
		}

}
?>
