<?PHP
    //Copyright محمد مصطفي شهركي @ http://www.ncis.ir
    ini_set('session.save_handler','user');

    class MySessionHandler
    {
        private $time_out;          //Time out for session
        private $salt;              //Salt, an uniq string
        private $browser_hash;      //Browser hash
        private $server;            //Server name
        private $user;              //User name
        private $pass;              //Pass word
        private $db;                //Database name

        public function Open($save_path, $session_name)
        {
            //Initialize your need here.
            //In my case, I need nothing.
            return true;
        }

        public function Close()
        {
            //Just deinitialize your resources
            return true;
        }

        public function Write($id,$data)
        {
            $safe_id=mysql_real_escape_string($id);
            $safe_data=mysql_real_escape_string($data);
            $hash=mysql_real_escape_string($this->browser_hash);
            $now=date("Y-m-d H:i:s");
            $query="INSERT INTO `sessions` (`id`,`data`,`modified`,`hash`) VALUES ('$safe_id','$safe_data','$now','$hash') ON DUPLICATE KEY UPDATE `data`='$safe_data', `modified`='$now',`hash`='$hash'";
            try
            {
                mysql_query($query);
            }
            catch(Exception $e)
            {
                return false;
            }
            return true;
        }

        public function Read($id)
        {
            $query='SELECT * FROM `sessions` WHERE (`id`=\''.mysql_real_escape_string($id).'\')';
            try
            {
                $result=mysql_query($query);
            }
            catch(Exception $e)
            {
                return '';
            }
            //Is there any???
            if (mysql_num_rows($result)!=1)
            {
                return '';
            }
            $data=mysql_fetch_assoc($result);
            //Now its time to validate...
            $time=strtotime($data['modified']);
            $hash=$data['hash'];
            //Check for time out and browser data
            if(time()-$time > $this->time_out || strcasecmp($this->browser_hash,$hash)!=0)
            {
                return '';
            }
            //Anything is ok, return data
            return $data['data'];
        }

        public function Destroy($id)
        {
            $safe_id=mysql_real_escape_string($id);
            $query="DELETE FROM `sessions` WHERE (`id`='$safe_id')";
            try
            {
                mysql_query($query);
            }
            catch(Exception $e)
            {
                return false;
            }
            $this->GC($this->time_out);
            return true;
        }

        public function GC($maxlifetime)
        {
            //You can use your timeout instead of this.
            $date=time()-$maxlifetime;
            $date_str=date("Y-m-d H:i:s",$date);
            $query="DELETE FROM `sessions` WHERE (`modified`<'$date')";
            try
            {
                mysql_query($query);
            }
            catch(Exception $e)
            {
                return false;
            }
            return true;
        }

        private function connect()
        {
            mysql_connect($this->server,$this->user,$this->pass) or die('Connection Error');
            mysql_select_db($this->db) or die('Database does not exit.');
            mysql_query('SET NAMES \'utf8\'');
        }

        public function __construct($server,$user,$pass,$db,$time_out=600,$salt='')
        {
            date_default_timezone_set('Asia/Tehran');
            if($salt=='')
            {
                $salt=md5('http://www.ncis.ir');
            }
            $this->server=$server;
            $this->user=$user;
            $this->pass=$pass;
            $this->db=$db;
            $this->time_out=$time_out;
            $this->salt=$salt;
            $this->calcHash();
            $this->connect();
            session_set_save_handler(array(&$this,'Open'),array(&$this,'Close'),array(&$this,'Read'),array(&$this,'Write'),array(&$this,'Destroy'),array(&$this,'GC'));
        }

        private function calcHash()
        {
            $ip=isset($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:"Unknown";
            $ip.=isset($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:"Unknown";
            $ip.=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"Unknown";
            $agent=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'NoUserAgent';
            $browser_data=$this->salt.$_ip.$_agent;
            $this->browser_hash=md5($browser_data);
        }
    }
?>