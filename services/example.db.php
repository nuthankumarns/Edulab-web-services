<?php
	  include 'db.php';
	class UserManagement {
		function getuserlist() {
			$ddb = new database();
			$query = "select * from jos_users";
			$result = $ddb->select($query);
			$xml = $ddb->data_to_xml($result);
			mysql_free_result($result);
			return $xml;
		}
$a=new UserManagement();
		
		/*function getuser($uid) {
			$ddb = new database();
			$query = "Select a.user_id, a.person_id, a.user_group_id, a.user_name, a.pass_word, a.loginmessage_id, a.start_date, a.end_date, a.created_on, a.created_by, a.modified_on, a.modified_by,";
	    $query = $query . " b.salutation, b.first_nm, b.last_nm, b.created_on as person_created_on, b.created_by as person_created_by, b.modified_on as person_modified_on, b.modified_by as person_modified_by,";
	    $query = $query . " c.user_group_desc";
	    $query = $query . " from pa_user a";
	    $query = $query . " 	 join (pa_person b, pa_user_group c)";
	    $query = $query . " 		on (a.user_group_id = c.user_group_id";
	    $query = $query . " 		and a.person_id = b.person_id)";
	    $query = $query . " Where a.user_id = " . $uid;		
			$result = $ddb->select($query);
			$xml = $ddb->data_to_xml($result);
			mysql_free_result($result);
			return $xml;
		}*/
}		
?>
