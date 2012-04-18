<?
class createXml
{
    var $doc;
    var $root;
    var $node;
    var $subnode;
    var $newsubnode;
    var $textnode;
    
	function createXML()
	{
		$this->doc = domxml_new_doc("1.0");
	}
	function createRootElement($string)
	{
		$this->root = $this->doc->create_element($string);
		$this->root = $this->doc->append_child($this->root);
	}
	function addNode($string)
	{
		$this->node = $this->doc->create_element($string);
		$this->node = $this->root->append_child($this->node);
	}
	function addsubNode($string)
	{
		$this->subnode = $this->doc->create_element($string);
		$this->subnode = $this->node->append_child($this->subnode);
	}
	function addnewsubNode($string)
	{
		$this->newsubnode = $this->doc->create_element($string);
		$this->newsubnode = $this->subnode->append_child($this->newsubnode);
	}
	function addtextNode($text)
	{
		$this->textnode = $this->doc->create_text_node($text);
		$this->textnode = $this->newsubnode->append_child($this->textnode);
	}
	function writetofile($dbname,$filename)
	{
	   if(!file_exists($dbname))
	   {
	   	$val=mkdir($dbname,0755);
	   }
	   $this->doc->dump_file("./$dbname/$filename.xml", false, true);
	}
	function dbconnet($host,$username,$pwd,$dbname)
	{
		$link = mysql_connect($host,$username,$pwd) or die("Could not connect");
		mysql_select_db($dbname) or die("Could not select database");
		return($link);
	}
	
	function mysqltoxml($host,$username,$pwd,$dbname,$sql) // create file using given sql;
	{
		$link=$this->dbconnet($host,$username,$pwd,$dbname);
		$this->createXml();
		$this->createRootElement("TABLE-RECORDS");
		$this->addNode("EXPORT-RECRODS");

		$res = mysql_query($sql) or die(mysql_error());
		if($res)
		{
			while($row = mysql_fetch_array($res))
			{
				$this->read_datas($res,$row);
			}
		}
		$this->writetofile($dbname,"result");
	}
	function read_datas($res,$row)
	{
		$columns = mysql_num_fields($res);
		$this->addsubNode("ROW");
		for($i=0;$i<$columns;$i++)
		{
			$fldname = mysql_field_name($res, $i);
			$this->addnewsubNode(strtoupper($fldname));

			$fldvalue = $row[$fldname];
			$this->addtextNode($fldvalue);
		}
	}
	
	function mysql_dbasetoxml($host,$username,$pwd,$dbname)
	{
		$link=$this->dbconnet($host,$username,$pwd,$dbname);
		$tables = mysql_list_tables($dbname);
		if($tables)
		{
			while($tname = mysql_fetch_row($tables))
			{
				$this->createXml();
				$this->createRootElement("TABLES");
				$this->addNode($tname[0]);

				$sql = "select * from ".$tname[0];
				$res = mysql_query($sql) or die(mysql_error());
				if($res)
				{
					while($row = mysql_fetch_array($res))
					{
						$this->read_all_datas($link,$dbname,$tname[0],$row);
					}
				}
				$this->writetofile($dbname,$tname[0]);
			}
		}
	}
	function read_all_datas($link,$dbname,$tname,$row)
	{
		$fields = mysql_list_fields($dbname, $tname, $link);
		$columns = mysql_num_fields($fields);
		$this->addsubNode("ROW");
		for($i=0;$i<$columns;$i++)
		{
			$fldname = mysql_field_name($fields, $i);
			$this->addnewsubNode($fldname);

			$fldvalue = $row[$fldname];
			$this->addtextNode($fldvalue);

		}
	}
	
}
?>