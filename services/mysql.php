<?php

/*
/   THIS CLASS IS FREE TO USE WITH NON-COMMERCIAL USE
/   FOR COMMERCIAL USE PLEASE CONSIDER A DONATION OF 20$ PER PROJECT
/   CONTACT ME @ ADMIN@ELECTRONICSPUB.COM
*/

/**************************************************\
|           PHP - MYSQL CONNECTION CLASS           |
|              AUTHOR: Saeid Yazdani               |
|                                                  |
|            Requires PHP 4.3 or higher            |
|                                                  |
|      This class is free to use and disturb       |
|       WITHOUT changing this comment block        |
|                                                  |
|                 SUPPORT US AT:                   |
|           HTTP://WWW.ELECTRONICSPUB.COM/         |
|                                                  |
\*________________________________________________*/


/* YOUR DATABASE ACCESS INFORMATION *///






class CLS_MYSQL {
  var $query;
  var $qNumber;
  var $result;
  var $config;

  var $data;
  var $dbLink;
  var $lastID;
  var $lastRows;

  function CLS_MYSQL($config) {
      $this->config = $config;
      $this->query = "";
      $this->qNumber = 0;
      $this->data = array();
  }

  function Error() {
    if (mysql_errno() != 0) {
        echo "<pre>\n";
      echo("Oops there is an error in site! :(\n");
        echo("Please contact admin!!!\n\n");
        echo("Error number: ") . mysql_errno() . "\n";
        echo("Error       : ") . mysql_error() . "\n";
        echo "</pre>\n";
        die("");
    }
  }

  function Connect() {
      $this->dbLink = @mysql_connect($this->config["server"] . ":" . $this->config["port"] , $this->config["username"], $this->config["password"], false);
      $this->Error();
    @mysql_select_db($this->config["database"], $this->dbLink);
      $this->Error();

      // UTF-8 is the default connection type, don't change unless neccessery.
      //@mysql_query("SET CHARACTER SET utf8");
     @mysql_query("SET SESSION collation_connection = 'utf8_unicode_ci'");
//@mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
@mysql_query("SET character_set_client=utf8");
@mysql_query("SET character_set_connection=utf8");
@mysql_query("SET character_set_results=utf8");
  }

  function DisConnect() {
    if (!(@mysql_close($this->dbLink))) { echo "<div align=\"center\" class=\"EN\">Can not Close DATABASE Connection</div>"; };
    $this->Error();
  }

  function Query($query, $key = "") {
    if (substr($query, 0, 6) == "SELECT") {
      for ($i=0; $i<count($this->data); $i++) {
          if ($this->data[$i]["query"] == $query) { return $this->data[$i]["return"]; }
        }
      }

    $this->result = @mysql_query($query);
      $this->Error();
      $this->query = $query;
      $this->qNumber++;

      if (mysql_num_rows($this->result) == 0) {
        $this->data[]["query"] = $query;
        $this->data[]["return"] = array();
        return array();
      }

    $ret = array();
      while ($row = mysql_fetch_assoc($this->result)) {
        if ($key == "") { $ret[] = $row; } else { $ret[$row[$key]] = $row; }
      }
    mysql_free_result($this->result);

    if (substr($query, 0, 6) == "SELECT") {
        $this->data[]["query"] = $query;
        $this->data[]["return"] = $ret;
    }

      return $ret;
  }

  function Execute($query) {
    $this->result = @mysql_unbuffered_query($query);
      $this->qNumber++;
      $this->Error();
    if (substr($query, 0, 6) == "INSERT") { $this->lastID = @mysql_insert_id($this->dbLink); }
    $this->lastRows = @mysql_affected_rows($this->dbLink);
    return $this->lastRows;
  }

  function GetCount($table, $extra = "", $field = "f_code") {
    $query = "SELECT COUNT(`" . $field . "`) AS `cNum` FROM `" . $table . "` " . $extra;
    $this->result = @mysql_query($query);
      $this->Error();
      $this->qNumber++;
      $num = mysql_result($this->result, 0, "cNum");
    mysql_free_result($this->result);

      return $num;
  }

  function GetValue($query, $field) {
    $this->result = @mysql_query($query);
      $this->Error();
      $this->qNumber++;

    if (mysql_num_rows($this->result) != 0) {
        $value = mysql_result($this->result, 0, $field);

      } else {
        $value = "";
      }
    mysql_free_result($this->result);

      return $value;
  }

  function GetValues($query, $fields) {
    $this->result = @mysql_query($query);
      $this->Error();
      $this->qNumber++;

      for ($i=0; $i<count($fields); $i++) {
        if (mysql_num_rows($this->result) != 0) {
          $ret[$fields[$i]] = mysql_result($this->result, 0, $fields[$i]);
        } else {
          $ret[$fields[$i]] = "";
        }
      }
      mysql_free_result($this->result);

      return $ret;
  }

  function GetResultNumber($data) {
    return count($data);
  }

  function GetResultValue($data, $number, $field) {
    return stripslashes($data[$number][$field]);
  }

  function GetResultArray($data, $field) {
    $ret = array();
    for ($i=0; $i<$this->GetResultNumber($data); $i++) {
      $ret[] = $this->GetResultValue($data, $i, $field);
    }
    return $ret;
  }

  function GetTablesList() {
    $ret = array();
    $this->result = mysql_list_tables($this->config["database"], $this->dbLink);
      $this->Error();

    $i = 0;
      while ($row = mysql_fetch_assoc($this->result)) {
      $ret[count($ret)] = mysql_tablename($this->result, $i);
      $i++;
    }
    mysql_free_result($this->result);

      return $ret;
  }

  function GetTableDefForBackup($table, $crlf, $drop = true)    {
      $schema = "";
        if ($drop) { $schema .= "DROP TABLE IF EXISTS `" . $table . "`;" . $crlf; }
      $schema .= "CREATE TABLE `" . $table . "` (" . $crlf;

        $this->result = mysql_query("SHOW FIELDS FROM `" . $table . "`");
        $this->Error();
        while($row = mysql_fetch_array($this->result)) {
          $schema .= "   `" . $row["Field"] . "` " . $row["Type"];

            if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))    { $schema .= " DEFAULT '" . $row["Default"] . "'"; }
            if($row["Null"] != "YES")    { $schema .= " NOT NULL"; }
            if($row["Extra"] != "")    { $schema .= " " . $row["Extra"]; }
            $schema .= "," . $crlf;
        }
        $schema = ereg_replace("," . $crlf . "$", "", $schema);
        $this->result = mysql_query("SHOW KEYS FROM `" . $table . "`");
        $this->Error();
        while($row = mysql_fetch_array($this->result)) {
            $kname = $row["Key_name"];
            if(($kname != "PRIMARY") && ($row["Non_unique"] == 0)) { $kname="UNIQUE|" . $kname; }
            if(!isset($index[$kname])) { $index[$kname] = array(); }
            $index[$kname][] = $row["Column_name"];
        }

        while(list($x, $columns) = @each($index))    {
            $schema .= "," . $crlf;
            if($x == "PRIMARY") {
              $schema .= "   PRIMARY KEY (`" . implode($columns, ", ") . "`)";
            }    elseif (substr($x,0,6) == "UNIQUE") {
                $schema .= "   UNIQUE `" . substr($x,7) . "` (`" . implode($columns, ", ") . "`)";
            }    else {
                $schema .= "   KEY `" . $x . "` (`" . implode($columns, ", ") . "`)";
            }
        }


        $schema .= $crlf . ")";
        return (stripslashes($schema) . ";" . $crlf);
  }

  function GetTableContentForBackup($table, $crlf)    {
    $ret  = "";
        $this->result = mysql_query("SELECT * FROM `" . $table . "`");
        $i = 0;
        while($row = mysql_fetch_row($this->result)) {
            $table_list = "(";

            for($j=0; $j<mysql_num_fields($this->result); $j++) {
                $table_list .= "`" . mysql_field_name($this->result, $j) . "`, ";
            }
            $table_list = substr($table_list, 0, -2);
            $table_list .= ")";

            $schema_insert = "INSERT INTO `" . $table . "` " . $table_list . " VALUES (";

            for($j=0; $j<mysql_num_fields($this->result); $j++) {
                if(!isset($row[$j])) {
                  $schema_insert .= " NULL,";
                }    elseif($row[$j] != "") {
                  $schema_insert .= " '".addslashes($row[$j])."',";
                }    else {
                    $schema_insert .= " '',";
                }
            }
            $schema_insert = ereg_replace(",$", "", $schema_insert);
            $schema_insert .= ")";
            $ret .= trim($schema_insert) .  ";" . $crlf;
            $i++;
        }
        return $ret;
  }

  function GetDatabaseBackup($headers, $crlf, $tableSchema = true, $dataSchema = true) {
    $ret  = "";
    $ret .= "# ========================================================" . $crlf;
    $ret .= "# " . $crlf;
    for ($i=0; $i<count($headers); $i++) {
      $ret .= "# " . $headers[$i] . $crlf;
    }
    $ret .= "# " . $crlf;
    $ret .= "# ========================================================" . $crlf;
    $ret .= $crlf;
    $tables = ($this->GetTablesList());
    for ($i=0; $i<count($tables); $i++) {
      $table = $tables[$i];

      if ($tableSchema == true) {
        $ret .= $crlf;
        $ret .= "# --------------------------------------------------------" . $crlf;
        $ret .= "# Table structure for table '" . $table . "'" . $crlf;
        $ret .= "# --------------------------------------------------------" . $crlf;
        $ret .= $this->GetTableDefForBackup($table, $crlf, true);
        $ret .= $crlf;
        $ret .= $crlf;
      }

      if ($dataSchema == true) {
        $ret .= $crlf;
        $ret .= "# --------------------------------------------------------" . $crlf;
        $ret .= "# Dumping data for table '" . $table . "'" . $crlf;
        $ret .= "# --------------------------------------------------------" . $crlf;
        $ret .= $this->GetTableContentForBackup($table, "\n");
        $ret .= $crlf;
        $ret .= $crlf;
      }
    }

    return $ret;
  }
}
?>
