<?php

/* Database Connection */
include'setup.php';
//$link = mysql_connect('localhost', 'issassin_killer', 'Tritone123');
/*if (!$link) {
    die('Could not connect: ' . mysql_error());
}
//echo 'Connected successfully';
mysql_select_db('issassin_issass');

/* Database connection ends */

//www.tritonetech.com/php_uploads/porsche_webservice/account.php?task=&username=&password=
$task = $_REQUEST['task'];
echo $task;
//echo $task."kjhk";

switch($task){
	case 'register_save':
		register_save();
		break;

	case 'login':
		login();
		break;
}

function register_save(){

		$password = $_REQUEST['password'];
		$name = $_REQUEST['name'];
		$username = $_REQUEST['username'];
		$email = $_REQUEST['email'];
		$gender = $_REQUEST['gender'];
		$age = $_REQUEST['age'];

		 $query = $_DB->Query("select * from jos_users where username='$username'");//and from_mobile_id='$from_mobile_id'");
                	$count=$_DB->GetResultNumber($query);

                  //  $res = mysql_query($query);

                    if($count > 0)
                    {


$dataDB['Result']['Data'][0]=array('Status'=>"Username Already Exist");

echo json_encode($dataDB);

                    }else

		{



		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);

		$makepass = '';

		$length = 32;

		$stat = @stat(__FILE__);
		if(empty($stat) || !is_array($stat)) $stat = array(php_uname());

		mt_srand(crc32(microtime() . implode('|', $stat)));

		for ($i = 0; $i < $length; $i ++) {
			$makepass .= $salt[mt_rand(0, $len -1)];
		}
		$salt = $makepass;

		$crypt = getCryptedPassword($password, $salt);

		$array['password'] = $crypt.':'.$salt;

		$password = $array['password'];

		$act = gethash(genAct());

		$reg_date=date('Y-m-d H:i:s');

		$sql    = $_DB->Execute("INSERT INTO jos_users(name,username,email,password,usertype,block,sendEmail,gid,registerDate,activation) VALUES ('$name','$username','$email','$password','Registered','0','0','18','$reg_date','$act')");
		//mysql_query($sql);

		//echo $sql."jhkjhjk";

		$sql = $_DB->Query("SELECT * FROM jos_users WHERE username = '$username'");
		//$uid = mysql_query($sql);
		$user_id=$_DB->GetResultValue($sql,0,'id');
		$gid=$_DB->GetResultValue($sql,0,'gid');
		/*while ($row = mysql_fetch_assoc($uid)) {
		    $user_id = $row['id'];
		    $gid = $row['gid'];
		}*/

		$query    = $_DB->Query("INSERT INTO jos_core_acl_aro(section_value,value,order_value,name,hidden) VALUES ('users','$user_id','0','$name','0')");
		//mysql_query($query);

		$sql = $_DB->Query("SELECT * FROM jos_core_acl_aro WHERE value = $user_id");
		//$aro = mysql_query($sql);
		$aid=$_DB->GetResultValue($sql,0,'id');
		/*while ($row = mysql_fetch_assoc($aro)) {
		    $aid = $row['id'];
		}*/

		$query    = $_DB->Execute("INSERT INTO jos_core_acl_groups_aro_map(group_id,section_value,aro_id) VALUES ('$gid','','$aid')");
		//mysql_query($query);

		$query    = $_DB->Execute("INSERT INTO user_profile(gender,age,user_id) VALUES ('$gender','$age','$user_id')");
		//mysql_query($query);
		$count=$_DB->GetResultNumber($query);
		//if(mysql_affected_rows()>0)
		if($count>0)
		{


$dataDB['Result']['Data'][0]=array('Status'=>"Registered Successfully");

echo json_encode($dataDB);
		}else
		{


$dataDB['Result']['Data'][0]=array('Status'=>"Registration Failed");

echo json_encode($dataDB);
		}

}
}//closes the else user exist

function getCryptedPassword($plaintext, $salt = '', $encryption = 'md5-hex', $show_encrypt = false)
{
	// Get the salt to use.
	$salt = getSalt($encryption, $salt, $plaintext);

	// Encrypt the password.
	switch ($encryption)
	{
		case 'plain' :
			return $plaintext;

		case 'sha' :
			$encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext));
			return ($show_encrypt) ? '{SHA}'.$encrypted : $encrypted;

		case 'crypt' :
		case 'crypt-des' :
		case 'crypt-md5' :
		case 'crypt-blowfish' :
			return ($show_encrypt ? '{crypt}' : '').crypt($plaintext, $salt);

		case 'md5-base64' :
			$encrypted = base64_encode(mhash(MHASH_MD5, $plaintext));
			return ($show_encrypt) ? '{MD5}'.$encrypted : $encrypted;

		case 'ssha' :
			$encrypted = base64_encode(mhash(MHASH_SHA1, $plaintext.$salt).$salt);
			return ($show_encrypt) ? '{SSHA}'.$encrypted : $encrypted;

		case 'smd5' :
			$encrypted = base64_encode(mhash(MHASH_MD5, $plaintext.$salt).$salt);
			return ($show_encrypt) ? '{SMD5}'.$encrypted : $encrypted;

		case 'aprmd5' :
			$length = strlen($plaintext);
			$context = $plaintext.'$apr1$'.$salt;
			$binary = JUserHelper::_bin(md5($plaintext.$salt.$plaintext));

			for ($i = $length; $i > 0; $i -= 16) {
				$context .= substr($binary, 0, ($i > 16 ? 16 : $i));
			}
			for ($i = $length; $i > 0; $i >>= 1) {
				$context .= ($i & 1) ? chr(0) : $plaintext[0];
			}

			$binary = JUserHelper::_bin(md5($context));

			for ($i = 0; $i < 1000; $i ++) {
				$new = ($i & 1) ? $plaintext : substr($binary, 0, 16);
				if ($i % 3) {
					$new .= $salt;
				}
				if ($i % 7) {
					$new .= $plaintext;
				}
				$new .= ($i & 1) ? substr($binary, 0, 16) : $plaintext;
				$binary = JUserHelper::_bin(md5($new));
			}

			$p = array ();
			for ($i = 0; $i < 5; $i ++) {
				$k = $i +6;
				$j = $i +12;
				if ($j == 16) {
					$j = 5;
				}
				$p[] = JUserHelper::_toAPRMD5((ord($binary[$i]) << 16) | (ord($binary[$k]) << 8) | (ord($binary[$j])), 5);
			}

			return '$apr1$'.$salt.'$'.implode('', $p).JUserHelper::_toAPRMD5(ord($binary[11]), 3);

		case 'md5-hex' :
		default :
			$encrypted = ($salt) ? md5($plaintext.$salt) : md5($plaintext);
			return ($show_encrypt) ? '{MD5}'.$encrypted : $encrypted;
	}
}

function getSalt($encryption = 'md5-hex', $seed = '', $plaintext = '')
{
	// Encrypt the password.
	switch ($encryption)
	{
		case 'crypt' :
		case 'crypt-des' :
			if ($seed) {
				return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 2);
			} else {
				return substr(md5(mt_rand()), 0, 2);
			}
			break;

		case 'crypt-md5' :
			if ($seed) {
				return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 12);
			} else {
				return '$1$'.substr(md5(mt_rand()), 0, 8).'$';
			}
			break;

		case 'crypt-blowfish' :
			if ($seed) {
				return substr(preg_replace('|^{crypt}|i', '', $seed), 0, 16);
			} else {
				return '$2$'.substr(md5(mt_rand()), 0, 12).'$';
			}
			break;

		case 'ssha' :
			if ($seed) {
				return substr(preg_replace('|^{SSHA}|', '', $seed), -20);
			} else {
				return mhash_keygen_s2k(MHASH_SHA1, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
			}
			break;

		case 'smd5' :
			if ($seed) {
				return substr(preg_replace('|^{SMD5}|', '', $seed), -16);
			} else {
				return mhash_keygen_s2k(MHASH_MD5, $plaintext, substr(pack('h*', md5(mt_rand())), 0, 8), 4);
			}
			break;

		case 'aprmd5' :
			/* 64 characters that are valid for APRMD5 passwords. */
			$APRMD5 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

			if ($seed) {
				return substr(preg_replace('/^\$apr1\$(.{8}).*/', '\\1', $seed), 0, 8);
			} else {
				$salt = '';
				for ($i = 0; $i < 8; $i ++) {
					$salt .= $APRMD5 {
						rand(0, 63)
						};
				}
				return $salt;
			}
			break;

		default :
			$salt = '';
			if ($seed) {
				$salt = $seed;
			}
			return $salt;
			break;
	}
}

function login(){

	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
//echo $username;
//echo $password;
//$a="SELECT id, gid, block, password, usertype FROM jos_users where username='$username'";
//echo $a;
	//$query = $this->_DB->Query("SELECT id, gid, block, password, usertype FROM jos_users where username='$username'");
//$query=mysql_query"SELECT id, gid, block, password, usertype FROM jos_users where username='$username'"
var_dump($query);
  	//$result = mysql_query($query) or die("Query failed" . mysql_error() );

   	//$line = mysql_fetch_array($result, MYSQL_ASSOC);
   	//mysql_free_result($result);
	$line['password']=$_DB->GetResultValue($query,0,'password');
   	$arraypass=explode(":", $line['password']);
   	$key=$arraypass[1];

   	echo 'Key = '.$key.'<br/>';

   	$ret = md5(trim($password).$key).":".$key;

   	echo $ret;

}

function genAct($length = 8)
	{
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);
		$makepass = '';

		$stat = @stat(__FILE__);
		if(empty($stat) || !is_array($stat)) $stat = array(php_uname());

		mt_srand(crc32(microtime() . implode('|', $stat)));

		for ($i = 0; $i < $length; $i ++) {
			$makepass .= $salt[mt_rand(0, $len -1)];
		}

		return $makepass;
}

function getHash( $seed )
{
	return md5( null .  $seed  );
}
?>
