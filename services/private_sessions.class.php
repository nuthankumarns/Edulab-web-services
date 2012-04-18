<?php

/*
  User-level session storage class for PHP 4, 5
  Written by Vagharshak Tozalakyan <vagh@armdex.com>
  Released under GNU Public License
*/

class private_sessions
{

  var $save_to_db = false;

  var $save_path = '';

  var $db_host = '184.168.226.74';
  var $db_uname = 'sopdb40';
  var $db_passwd = 'Tritone123';
  var $db_name = 'sopdb40';
  var $save_table = 'sessions';

  function private_sessions()
  {
    $this->save_path = session_save_path();
  }

  function set_handler()
  {
    if ($this->save_to_db)
    {
      global $__g_db_host, $__g_db_uname, $__g_db_passwd, $__g_db_name, $__g_save_table;
      $__g_db_host = $this->db_host;
      $__g_db_uname = $this->db_uname;
      $__g_db_passwd = $this->db_passwd;
      $__g_db_name = $this->db_name;
      $__g_save_table = $this->save_table;
      $postfix = '_db';
    }
    else
    {
      global $__g_save_path;
      $__g_save_path = $this->save_path;
      $postfix = '_fl';
    }
    session_set_save_handler(
      array('private_sessions', '_open' . $postfix),
      array('private_sessions', '_close' . $postfix),
      array('private_sessions', '_read' . $postfix),
      array('private_sessions', '_write' . $postfix),
      array('private_sessions', '_destroy' . $postfix),
      array('private_sessions', '_clean' . $postfix)
    );
  }

  function install_table($drop_existed = false)
  {
    if (!$this->save_to_db)
    {
      return false;
    }
    global $__g_link;
    $__g_link = mysql_connect($this->db_host, $this->db_uname, $this->db_passwd)
      or die(mysql_error());
    mysql_select_db($this->db_name, $__g_link) or die(mysql_error());
    if ($drop_existed)
    {
      mysql_query("DROP TABLE IF EXISTS $this->save_table") or die(mysql_error());
    }
    $result = mysql_query("SHOW TABLES", $__g_link) or die(mysql_error());
    $found = false;
    while ($row = mysql_fetch_array($result))
    {
      if (strtoupper($this->save_table) == strtoupper($row[0]))
      {
        $found = true;
      }
    }
    if ($found)
    {
      return false;
    }
    mysql_query("CREATE TABLE $this->save_table (
      id varchar(32) NOT NULL,
      access int(10) unsigned,
      data text,
      PRIMARY KEY (id)
    )") or die(mysql_error());
    return true;
  }

  function _open_fl($save_path, $session_name)
  {
    global $__g_save_path;
    if (!is_dir($__g_save_path))
    {
      mkdir($__g_save_path);
    }
    return true;
  }

  function _open_db($save_path, $session_name)
  {
    global $__g_db_host, $__g_db_uname, $__g_db_passwd, $__g_db_name;
    global $__g_save_table, $__g_link;
    if ($__g_link = mysql_connect($__g_db_host, $__g_db_uname, $__g_db_passwd))
    {
      return mysql_select_db($__g_db_name, $__g_link);
    }
    return false;
  }

  function _close_fl()
  {
    return true;
  }

  function _close_db()
  {
    global $__g_link;
    return mysql_close($__g_link);
  }

  function _read_fl($id)
  {
    global $__g_save_path;
    $sess_file = $__g_save_path . '/sess_' . $id;
    if ($fp = @fopen($sess_file, 'r'))
    {
      $sess_data = fread($fp, filesize($sess_file) + 1000);
      fclose($fp);
      return $sess_data;
    }
    return '';
  }

  function _read_db($id)
  {
    global $__g_save_table, $__g_link;
    $id = mysql_real_escape_string($id);
    $sql = "SELECT data FROM $__g_save_table WHERE id='$id'";
    if ($result = mysql_query($sql, $__g_link))
    {
      if (mysql_num_rows($result))
      {
        return mysql_result($result, 0, 0);
      }
    }
    return '';
  }

  function _write_fl($id, $sess_data)
  {
    global $__g_save_path;
    $sess_file = $__g_save_path . '/sess_' . $id;
    if ($fp = @fopen($sess_file, 'w'))
    {
      $writed = fwrite($fp, $sess_data);
      fclose($fp);
      return $writed;
    }
    return false;
  }

  function _write_db($id, $sess_data)
  {
    global $__g_save_table, $__g_link;
    $id = mysql_real_escape_string($id);
    $access = mysql_real_escape_string(time());
    $sess_data = mysql_real_escape_string($sess_data);
    $sql = "REPLACE INTO $__g_save_table VALUES ('$id', '$access', '$sess_data')";
    return mysql_query($sql, $__g_link);
  }

  function _destroy_fl($id)
  {
    global $__g_save_path;
    $sess_file = $__g_save_path . '/sess_' . $id;
    return @unlink($sess_file);
  }

  function _destroy_db($id)
  {
    global $__g_save_table, $__g_link;
    $id = mysql_real_escape_string($id);
    $sql = "DELETE FROM $__g_save_table WHERE id='$id'";
    return mysql_query($sql,  $__g_link);
  }

  function _clean_fl($lifetime)
  {
    global $__g_save_path;
    if ($dir = opendir($__g_save_path))
    {
      while ($fname = readdir($dir))
      {
        if (substr($fname, 0, 1) == '.')
        {
          continue;
        }
        clearstatcache();
        $modified = filemtime($__g_save_path . '/' . $fname);
        if ((time() - $modified) > $lifetime)
        {
          @unlink($__g_save_path . $fname);
        }

      }
      closedir($dir);
    }
    return true;
  }

  function _clean_db($lifetime)
  {
    global $__g_save_table, $__g_link;
    $old = time() - $lifetime;
    $old = mysql_real_escape_string($old);
    $sql = "DELETE FROM $__g_save_table WHERE access<'$old'";
    return mysql_query($sql, $__g_link);
  }

}

// IMPORTANT! Please no spaces after closing tag.
?>