<?php
/**
 * Session Class for storing session data in mysql
 * 
 * Features :
 * This class help you to Store Data on mysql server.
 * 
 * How to use :
 *  Create your database in MySQL, and create a table in which
 *  to store your session information.  The example code below
 *  uses a table called "session".  Here is the SQL command
 *  which created it:
 * 
 *  CREATE TABLE sessions (id varchar(32) NOT NULL,access
 *  int(10) unsigned,data text,PRIMARY KEY (id));
 * 
 * 
 * @author sudhir vishwakarma <sudhir.vis@gmail.com>
 * @copyright Sudhir Vishwakarma (www.itwebinfo.com)
 * 
 * @version 0.1 20100602
 * 
 * * License 
 * GNU General Public License version 3 (GPLv3) 
 *  
    File Browser (C) 2009  sudhir vishwakarma
    
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    Also add information on how to contact you by electronic and paper mail.

    If the program does terminal interaction, make it output a short notice like this when it starts in an interactive mode:

    File Browser (C) 2009  sudhir vishwakarma

    This program comes with ABSOLUTELY NO WARRANTY; for details type `show w'.
    This is free software, and you are welcome to redistribute it
    under certain conditions; type `show c' for details.
    The hypothetical commands `show w' and `show c' should show the appropriate parts of the General Public License. Of course, your program's commands might be different; for a GUI interface, you would use an �about box�.

    You should also get your employer (if you work as a programmer) or school, if any, to sign a �copyright disclaimer� for the program, if necessary. For more information on this, and how to apply and follow the GNU GPL, see <http://www.gnu.org/licenses/>.

    The GNU General Public License does not permit incorporating your program into proprietary programs. If your program is a subroutine library, you may consider it more useful to permit linking proprietary applications with the library. If this is what you want to do, use the GNU Lesser General Public License instead of this License. But first, please read <http://www.gnu.org/philosophy/why-not-lgpl.html>.
 */ 
class Session {
    public function __construct(){
        session_set_save_handler(array(&$this, 'open'),array(&$this, 'close'),array(&$this, 'read'),array(&$this, 'write'),array(&$this, 'destroy'),array(&$this, 'clean'));
        session_start();
    }
    public function open() {
 $this->mysql = mysql_connect('localhost', 'edulabcl_buddy', 'Tritone123#');
        $bSeldb = mysql_select_db('edulabcl_porsche', $this->mysql);
	  @mysql_query("SET SESSION collation_connection = 'utf8_unicode_ci'");
@mysql_query("SET character_set_client=utf8");
@mysql_query("SET character_set_connection=utf8");
@mysql_query("SET character_set_results=utf8");
       
        if (!$bSeldb) {
            die ('Can\'t use Database : ' . mysql_error());
        }

    }
    public function write($id, $data) {
	$uid=$_SESSION['UID'];
        $access = time();
        $id = mysql_real_escape_string($id);
        $access = mysql_real_escape_string($access);
        $data = mysql_real_escape_string($data);
        $sql = "REPLACE INTO jos_porsche_sessions VALUES  ('$id', '$access', '$data','$uid')";
        return mysql_query($sql, $this->mysql) or die(mysql_error());
    }

    public function read($id) {
        $id = mysql_real_escape_string($id);
        $sql = "SELECT data FROM   jos_porsche_sessions WHERE  id = '$id'";
        if ($result = mysql_query($sql, $this->mysql)) {
            if (mysql_num_rows($result)) {
                $record = mysql_fetch_assoc($result);
                return $record['data'];
            }
        }
        return '';
    }
    public function destroy($id) {
        $id = mysql_real_escape_string($id);
        $sql = "DELETE FROM   jos_porsche_sessions WHERE  id = '$id'";
        return mysql_query($sql, $this->mysql);
    }
    public function clean($max) {
        $old = time() - $max;
        $old = mysql_real_escape_string($old);
        $sql = "DELETE FROM   jos_porsche_sessions WHERE  access < '$old'";
        return mysql_query($sql, $this->mysql);
    }
	public function users() { 
        $sql = "SELECT COUNT(id) FROM jos_porsche_sessions"; 
        $users_res = mysql_query($sql,$this->mysql); 

        if (!$users_res) { 
            return NULL; 
        }         else { 
            return mysql_fetch_assoc($users_res); 
        } 
}
	/*public function online() { 
        $sql = "SELECT access FROM jos_porsche_sessions where "; 
        $users_res = mysql_query($sql,$this->mysql); 

        if (!$users_res) { 
            return NULL; 
        }         else { 
            return mysql_fetch_assoc($users_res); 
        } 
}*/
    public function close() {
        mysql_close($this->mysql);
    }
}
?>
