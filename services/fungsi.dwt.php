<style type="text/css">
<!--
body {
	background-image: url(image542.gif);
}
.tab1 {
	font-family: Arial, Helvetica, sans-serif;
	color: #FFFFFF;
	font-weight: bold;
}
.tab2 {
font-family: Arial, Helvetica, sans-serif;
font-size:12px;

}
-->
</style>
<?
//@author			: 	Eka Dystiant Aulia 
//@date Modified	:	14/04/2006
//@class item		:
//	
//LIBRARY MODIFIED : 08/04/2006

//constant
define ('HOST','localhost');
define ('USER','root');
define ('PASSWORD','a');
define ('DATABASE','jurnal');
define ('PAGING',10);
//CLASS
class mySqlConn // all connection into mysql extended from this class 
	{
				private $host;
				private $user;
				private $pass;
				private	$conn;
				private $database;
				private $row;
				private	$res;
				function setConn($host_,$user_,$pass_,$database_)
						{
								$this->host 	= $host_;
								$this->user 	= $user_;
								$this->pass 	= $pass_;
								$this->database = $database_;
						}
				function connectMySql()
						{
							$this->conn		=	mysql_connect($this->host,$this->user,$this->pass,"","") 
							or die("<b>Not connected !!! cause :".mysql_error()."</b>");
							mysql_select_db($this->database,$this->conn);
						}
				
				function runQry($sql)
						{
							$this->res	=	mysql_query($sql,$this->conn);	
							return $this->res;
						}	
						
				function closeConn()
						{
							mysql_close($this->conn);		
						}
				
				function fetchRow()
						{
							$this->row 	=  mysql_fetch_row($this->res);
							return $this->row;
						}
						
				function fieldList(&$fieldRet,$sql,&$res)
						{	$i = 0;
							$res=$this->runQry($sql);
							while ($i < mysql_num_fields($this->res)) 
							{
  
  									 $meta = mysql_fetch_field($this->res, $i);
   											if (!$meta) {
       														echo "No Info<br />\n";
   														};
  
										$fieldRet[$i] = $meta->name;
   										$i++;
							}
							return $i;
							//echo mysql_tablename($this->res,0);
							
						}
	};

class dataToXML extends mySqlConn
		{
				private		$tabelName;
				private		$fileName;
				private		$fileHandle;
				private		$fieldName;
				private		$crlf	=	"\r\n";
				private		$numOfFields;
				private		$sql;
				private		$res;
				private		$pages;
				private		$header_;
				private		$columnName;
				private		$allign;
				private		$width;
				private		$totalWidth;
				function __construct($fileName)
					{
						$this->fileName		=	$fileName;
						$this->setConn(HOST,USER,PASSWORD,DATABASE);
						$this->connectMysql();
						$this->fileHandle = fopen($this->fileName,"w");
						$this->pages = 10;
					}
				
				function	setXMLFile($filename)
					{
							$this->fileName	=	$filename;
					}
					
				function	setQueryString($sql)
					{
							$this->sql = $sql;
							$this->numOfFields	=	$this->fieldList($this->fieldName,$this->sql,$this->res);	
							$this->tabelName	=	mysql_field_table($this->res,$this->fieldName[0]);
						
					}
				
				function	createXML()
					{
							fputs($this->fileHandle,"<?xml version='1.0' standalone='yes'?>".$this->crlf);
							fputs($this->fileHandle,'<'.$this->tabelName.'>'.$this->crlf);
							
							$this->runQry($this->sql);
							while ($row=$this->fetchRow())
							{
							fputs($this->fileHandle,'    <RECORD>'.$this->crlf);
							for($itr = 0 ; $itr < $this->numOfFields;$itr++)
								{
										//checking row from illegal XML character
										fputs($this->fileHandle,'     <'.htmlspecialchars($this->fieldName[$itr]).'>'.
										htmlspecialchars($row[$itr]).'</'.htmlspecialchars($this->fieldName[$itr]).'>'.$this->crlf);
								
								};
							fputs($this->fileHandle,'    </RECORD>'.$this->crlf);	
							};
							fputs($this->fileHandle,'</'.$this->tabelName.'>'.$this->crlf);
							
							echo '<XML ID ="dso'.$this->tabelName.'" src ="'.$this->fileName.'"/>'.$this->crlf;
							//	echo '<H2 align="center" class="style1">'.$this->header_.'</H2>'.$this->crlf;
							
							
					}
				
				
			
				function __destruct()
					{
						$this->closeConn();
						fclose($this->fileHandle);
					}
				
				function setProperty($header,$columnName,$allign,$width)
					{
							$this->header_		=	$header;
						
							//if column header not defined then the default is field name attribut
							//
							if(!$columnName)
								{
									$this->fieldList($this->columnName,$this->sql,$this->res);
								}
							else
								{
										$this->columnName	=	$columnName;
								};
							//if align property is not defined then the default is left ajusted
							if(!$columnName)
								{
									for($itr = 0 ; $itr < $this->numOfFields;$itr++)
											{
												$this->allign[$itr]="left";
											};
								}
							else
								{
										$this->allign		=	$allign;
								};
							//if width property isn't defined then the default is 100
							if(!$columnName)
								{
									for($itr = 0 ; $itr < $this->numOfFields;$itr++)
											{
												$this->width[$itr]=100;
											};
								}
							else
								{
										$this->width		=	$width;
								};
								
							$this->totalWidth	=	0;
							
							for($itr = 0 ; $itr < $this->numOfFields;$itr++)
								{
									$this->totalWidth	=	$this->totalWidth + $width[$itr];
								};
								
								
					}
					
				function showList()
					{
    							echo '<table width="'.$this->totalWidth.'" height="32" border="1" align="center" cellpadding="1" cellspacing="0">'.$this->crlf;
  								echo '<tr>'.$this->crlf;
								echo '<td  height="32" align="center" bordercolor="#333333" bgcolor="#99CCFF"><button onclick="'.
								$this->tabelName.'.firstPage()">First Page</button>'.$this->crlf;
    							
								echo '<td align="center" bordercolor="#333333" bgcolor="#99CCFF"><button onclick="'.
								$this->tabelName.'.previousPage()">Previous Page</button>'.$this->crlf;
    							
								echo '<td  align="center" bordercolor="#333333" bgcolor="#99CCFF"><button onclick="'.
								$this->tabelName.'.nextPage()">Next Page</button></td>'.$this->crlf;
    							
								echo '<td  align="center" bordercolor="#333333" bgcolor="#99CCFF"><button onclick="'.
								$this->tabelName.'.lastPage()">Last Page</button></td>'.$this->crlf;
  								
								echo '</tr>'.$this->crlf;
								echo '</table>'.$this->crlf;
								
								
								echo '<TABLE width="'.$this->totalWidth.'" border="8" align="center" cellpadding="0" cellspacing="1"   bordercolordark="#333333" 
								bordercolorlight="#CCCCCC" datapagesize="'.$this->pages.'" ID="'.$this->tabelName.'"  datasrc="#dso'.$this->tabelName.'" class="tab2">';
				
								echo '<thead>';
							
							for($itr = 0 ; $itr < $this->numOfFields;$itr++)
								{
							
								
								
									echo 	'<td height="32" width = "'.$this->width[$itr].'"bordercolor="#99CCFF" bgcolor="#006699">
											<div align="center" class="tab1">
											<span class="style1">'.$this->columnName[$itr].
											'</span></div>
											</td>';
								};
				
								echo '</thead>';
								echo '<tr>';
								
							for($itr = 0 ; $itr < $this->numOfFields;$itr++)
								{
									echo 	'<td align="'.$this->allign[$itr].'" bordercolor="#99CCCC" bgcolor="#FFFFFF"> 
											<span class="tab2" DATAFLD = "'.$this->fieldName[$itr].'"/></td>';
								};
								
								echo '</tr>';
								echo '</TABLE>';
								
							
									
					}
				function showForm($type)
					{
							//type 0 text, 1 textarea 2 list
						//header
									echo 	'<table width="550" border="5" bordercolordark="#333333" bordercolorlight="#CCCCCC" align="center" 
										  	cellpadding="0" cellspacing="0"><tr align="center" valign="middle">
      										<td colspan="2" bordercolor="#333333" bgcolor="#99CCCC" scope="row"><div align="center">
        									<p class="tab2">'.$this->header_.'</p>
      										</div></td>
    										</tr>
											<tr><td colspan="2" bordercolor="#FFFFE6" bgcolor="#FFFFE6" scope="row">&nbsp;</td></tr>';	
						if (!$type)
							{
									
									for($itr = 0 ; $itr < $this->numOfFields;$itr++)
										{//nama postnya Fn
										echo	'<tr>
      											<td bordercolor="#FFFFE6" bgcolor="#FFFFE6" scope="row"><div align="right" class="tab2">'.
												$this->columnName[$itr].' : </div></td>
      											<td align="center" valign="middle" bordercolor="#FFFFE6" bgcolor="#FFFFE6"><div align="left">
        										<input name="F'.$itr.'" DATASRC="#dso'.$this->tabelName.'" 
												DATAFLD="'.$this->fieldName[$itr].'" type="text" id="'.$this->columnName[$itr].'" size="30" maxlength="30" />
      											</div></td>
    											</tr>';
										};
									
							}	
						else
							{
									//for development purpose not defined yet, type of column displayed
									//0 text, 1 text area, 2 etc.......
									for($itr = 0 ; $itr < $this->numOfFields;$itr++)
										{
												switch ($type)
													{
															case 0:
															break;
															case 1:
															break;
															case 2:
															break;
													};
														
										};	
							};
						//footer	
									echo	'<tr><td colspan="2" bordercolor="#FFFFE6" bgcolor="#FFFFE6" scope="row">&nbsp;</td></tr>
											<td colspan="2" bordercolor="#FFFFE6" bgcolor="#FFFFE6" scope="row"><div align="center">
											<p>
          									<input type="submit" name="Edit" value="Submit" />  
          									<input type="reset" name="Reset" value="Reset" />
		  									<INPUT ID=cmdNavFirst TYPE=BUTTON VALUE="Move First" onclick="dso'.$this->tabelName.'.recordset.MoveFirst()">
		  									<INPUT ID=cmdNavFirst TYPE=BUTTON VALUE="Move Prev" onclick="dso'.$this->tabelName.'.recordset.MovePrevious()">
		  									<INPUT ID=cmdNavFirst TYPE=BUTTON VALUE="Move Next" onclick="dso'.$this->tabelName.'.recordset.MoveNext()">
		  									<INPUT ID=cmdNavFirst TYPE=BUTTON VALUE="Move Last" onclick="dso'.$this->tabelName.'.recordset.MoveLast()">
        									</p>
        									</div></td>
    										</tr>
    										<tr>
      										<td colspan="2" bordercolor="#FFFFE6" bgcolor="#FFFFE6" scope="row">&nbsp;</td>
    										</tr>
  											</table>';		
					//end of Function
					}
					
				function setPage($pages)
					{
						$this->pages	=	$pages;
					}
								
		};
		

class optionList extends mySqlConn // class for retrieve and create the combo list item from mysql data
		{		
				private	$ref_val_field;
				private	$ref_list_field;
				private $refid;
				private $tabel_name;
				private $ref_field;
				private $jumlah_item;
				private $selected;
				private $value_;
				private $item_;
				
						function __construct(	$ref_val_field_,	// 	nama field yang akan dijadikan acuan value (select - deprecated
												$ref_list_field_,	//	nama field yang akan dijadikan tampil di list <-- deprecated
												$tabel_name_,		//	nama Tabel  <----from------------	deprecated		
												$ref_field_,		//	nama field referensi data yang diambil (where ref_field like deprecated)
												$refid_,			//	kondisi deprecated
												$selected_)			// 	index list selected default
								{
										$this->ref_val_field 	= 	$ref_val_field_;
										$this->ref_list_field	=	$ref_list_field_;
										$this->refid			=	$refid_;
										$this->tabel_name		=	$tabel_name_;
										$this->ref_field		=	$ref_field_;
										$this->selected			=	$selected_;
										$this->setConn(HOST,USER,PASSWORD,DATABASE);
										$this->connectMysql();
								}
		
		
						function ambilReferensi()
										{
											//ref 			= 	referensi
											//refid			=  	nilai acuan
											//tabel_name	=	nama tabel acuan
											//ref_field		=	nama field acuan
					
											$iterasi 	= 0;
										
											$sql  		=  	'select '.
															$this->ref_val_field.','.
															$this->ref_list_field.' from '.
															$this->tabel_name.' where '.
															$this->ref_field.' like"'.
															$this->refid.'"';
											$this->runQry($sql);
											while($row 	= 	$this->fetchRow())
												{
													$this->value[$iterasi]		=	$row[0];
													$this->item[$iterasi]	 	= 	$row[1]; 
													$iterasi=$iterasi+1;
												};
											$this->jumlah_item = $iterasi;
										}					
								
							function buildList()
										{		
												for ($i = 0 ; $i <$this->jumlah_item; $i++)
													{
														if (!strcmp($i,$this->selected))
																{
																	echo '<option selected="selected" value="'.$this->value[$i].'">'.$this->item[$i].'</option>';
																}
														else
																{
																	echo '<option value="'.$this->value[$i].'">'.$this->item[$i].'</option>';
																};
													};
										}
										
										
							
										
							function __destruct()
							
									{
										$this->closeConn();
									}
		
		
		};

class IlyaTab
		{		
				//	javascript modification
				//	NOT FUNCTIONALL YET !!!,
				// 	Copyright (C) 2005 Ilya S. Lyubinskiy. 
				// 	Technical support: http://www.php-development.ru/
				//
				//	added class automation with PHP5
				//	@author	Eka Dystiant Aulia Nst
				//		IlyaTab	$myTab	=	new IlyaTab(array("tab1","tab2","tab3"),"Hello",500,500,"center");
				//		$myTab->initJSCSS();
				//		$myTab->setTabContent(array("a","b","c"));
				//		$myTab->setTabActive(int);
				//		$myTab->showTab();
				
				private	$tabName;
				private	$tabId;
				private $tabWidth;
				private $tabHeight;
				private $tabCount;
				private $tabContent;
				private $tabActive	=	1;
				private	$tabAlign	=	'center';
				private	$crlf		=	"\r\n";
				function __construct($tabName,$tabId,$tabWidth,$tabHeight,$tabAlign)
						{
							
								$this->tabName		=	$tabName;
								$this->tabId		=	$tabId;
								$this->tabWidth		=	$tabWidth;
								$this->tabHeight	=	$tabHeight;
								$this->tabCount		=	count($tabName);
								$tabAlign==NULL?NULL:$this->tabAlign=$tabAlign;
						}
				function initJSCSS()
						{
							echo 	'<link rel="stylesheet" type="text/css" href="tabview.css" />
									<script type="text/javascript" src="tabview.js">';
						}
						
			
				function setTabActive($no)
						{
								$this->tabActive	=	$no;
						}
				function initTab()
						{
							echo 	'<script type="text/javascript">
									tabview_aux("'.$this->tabId.'",'.$this->tabActive.');
       								</script>';	
						}
				
				function showTab($content)
						{
							//header
							echo	'<table  border="2" align="'.$this->tabAlign.'" cellpadding="0" cellspacing="0">'.$this->crlf.'
							 		<tr>'.$this->crlf.'
 									<td><div align="left">'.$this->crlf.'
 									<div class="TabView" id="'.$this->tabId.'">'.$this->crlf.'
 									<!-- *** Tabs ************************************************************** -->'.$this->crlf.'
         							<div class="Tabs" style="width:'.$this->tabWidth.'px;">'.$this->crlf; 
		 					for($itr=0;$itr<$this->tabCount;$itr++)
									{
										echo '<a>'.$this->tabName[$itr].'</a>'.$this->crlf;
									};
							echo	'</div>'.$this->crlf.'
									<!-- *** Pages ************************************************************* -->'.$this->crlf.'
    								<div class="Pages" style="width: '.$this->tabWidth.'px;height:'.$this->tabHeight.'px">'.$this->crlf;
								
							//tab
							for($itr=0;$itr<$this->tabCount;$itr++)
									{
							
									echo	'<div class="Page">
        									<div class="Pad">
         					 				<table >
           									<tr>
             								<td style="vertical-align: middle;"></td>
             								<td>';
							
									 		$content[$itr];
											
									echo	'</td>'.$this->crlf.'
           									</tr>'.$this->crlf.'
         									</table>'.$this->crlf;
							
									};
							
							//footer
							$this->initTab();
     						
							echo 	'</div></td>'.$this->crlf.'
   									</tr>'.$this->crlf.'
 									</table>'.$this->crlf;
						}
						
		};
		
		
		
class mySqlDataList extends optionList //modified 19-04-2006
			{			private		$nama;
						private		$column_name;
						private		$table_name;
						private		$column_header;
						private		$align;
						private		$line_num;
						private		$action;
						private		$width;
						private		$mode;
						private		$itr; 		
						private		$field;			
						private		$value;		
						private		$set_val; 		
						private		$where_val;		
						private		$column_no;
						private		$conn;
						
						public		$table_width;
						public		$itr_; 		
						public		$start;
						public		$condition;
						
						function __construct(	$name,
												$column_name, //deprecated
												$table_name,  //deprecated 
												$condition,   //deprecated 
												$column_header,
												$align,
												$start,
												$line_num,
												$action,
												$width,
												$mode)
								{
										$this->name				= 	$name;
										$this->column_name		=	$column_name;
										$this->table_name		=	$table_name;
										$this->condition		=	$condition;
										$this->column_header	=	$column_header;
										$this->align			=	$align;
										$this->start			=	$start;
										$this->line_num			=	$line_num;
										$this->action			=	$action;
										$this->width			=	$width;
										$this->mode				=	$mode;
										$this->line_num			=	$line_num;
										$this->setConn(HOST,USER,PASSWORD,DATABASE);
										$this->connectMysql();
										$this->table_width		=	100;
								}
						function __destruct()
								{
									$this->closeConn();
								
								}
								
						function 	init()
								{
										$this->itr 				= 	1;
										$this->field			=	NULL;
										$this->value			=	NULL;
										$this->set_val 			=	NULL;
										$this->where_val		=	NULL;
										$this->column_no 		=	count($this->column_name);
										for ($this->itr=0; $this->itr < $this->column_no;$this->itr++)
											{
												
												$this->itr == $this->column_no-1? 
												$this->field = $this->field.$this->column_name[$this->itr]:
												$this->field = $this->field.$this->column_name[$this->itr].",";
											};
									
										
								}
						
						function	proceed()
								{
									if (isset($_POST['B3']))
											{
												if ($_POST['B3']=='Edit')
													{
														for ($this->itr=0;$this->itr<$this->column_no;$this->itr++)
															{
																$this->begining_value[$this->itr]=$_POST['f'.$this->itr];
									
																$this->updated_value[$this->itr]=$_POST['F'.$this->itr];
									
																$this->itr== $this->column_no-1?
																$this->set_val = $this->set_val.$this->column_name[$this->itr]."='".$this->updated_value[$this->itr]."' where "
																:$this->set_val = $this->set_val.$this->column_name[$this->itr]."='".$this->updated_value[$this->itr]."',";
							
																$this->itr== $this->column_no-1?
																$this->where_val = $this->where_val.$this->column_name[$this->itr]."='".$this->begining_value[$this->itr]."'"
																:$this->where_val = $this->where_val.$this->column_name[$this->itr]."='".$this->begining_value[$this->itr]."' and ";
															};
				
															$update_query	=	'update '.$this->table_name.' set '.$this->set_val.$this->where_val;
															$this->runQry($update_query);
												}
												elseif ($_POST['B3']=='Delete')
													{
														//echo "DATA TELAH DIHAPUS";
														for ($this->itr=0;$this->itr<$this->column_no;$this->itr++)
															{	
																$this->begining_value[$this->itr]=$_POST['f'.$this->itr];
																$this->itr== $this->column_no-1?
																$this->value	= $this->value.$this->column_name[$this->itr]."='".$this->begining_value[$this->itr]."'":
																$this->value	= $this->value.$this->column_name[$this->itr]."='".$this->begining_value[$this->itr]."' and ";
							
															};	
						
															$delete_query = 'delete from '.$this->table_name.' where '.$this->value;
															$this->runQry($delete_query);
													};
											};

								}
						

						function	drawList()
						
							{ 
							
									$sql	=	'select '.$this->field.' from '.$this->table_name." where ".$this->condition." limit ".$this->start.",".$this->line_num;
									//assigning queries
									$this->mode!=0?$span =$this->column_no + 1:$span=$this->column_no;
	
									$res = $this->runQry($sql);
								//ini nanti akan digunakan meretrieve nama tabel
								//	echo mysql_field_table($res,'rek1');
								
									//building tabel header
									echo '<div align="center">';
									echo '<center>';
									echo '<table border="1" cellspacing="0" id="AutoNumber1" cellpadding="0" width="'.$this->table_width.'" height="91">';
									echo '<tr><td bgcolor="#006699" align="center" height="25" colspan="'.$span.'"><font face="Arial">'.$this->name.'</font></td></tr>';
									echo '<tr>';
									for ($this->itr = 0 ; $this->itr< $this->column_no;$this->itr++)
										{
   											echo'<td bordercolor="#CCCCCC" bgcolor="#CCCCCC" align="center" height="26"><p align="center"><font face="Arial">'.$this->column_header[$this->itr].'</font></td>';
										};			
									if ($this->mode != 0)
										{
											echo'<td bordercolor="#CCCCCC" bgcolor="#CCCCCC"  align="center" valign = "middle" height="26"><p align="center"><font face="Arial">'."Action".'</font></td>';
										};
	
									echo'</tr>';
						//			echo '</form>';
	
									//building data
    								$this->itr=1;
	   								while($row 	= 	$this->fetchRow())
										{
											echo '<form id ="'.$this->itr.'" name="'.$this->itr.'" action ="'.$this->action.'"  method="post" target="_self" >'; 
			 								echo '<tr>';
											for ($this->itr_ = 0; $this->itr_<$this->column_no;$this->itr_++)
												{	
	   												switch ($this->mode)
													{
														case 0:
															echo '<td bgcolor="#FFFFFF" align="'.$this->align[$this->itr_].'" height="19"><font face="Arial">'.$row[$this->itr_].'</font></td>';
														break;
														case 1:
														//nanti di buat ada yang bertipe dropdown list (buat entri/edit)
															echo'<td bgcolor="#FFFFFF"><div align="'.$this->align[$this->itr_].'" class="style18">'.
															'<input name="F'.$this->itr_.'" type="text" id="'.$this->itr.'row2'.'" size="'.$this->width[$this->itr_].'" maxlength="'
															.$this->width[$this->itr_].'" value="'.$row[$this->itr_].'">'.
															'</div></td>';
															
															echo '<input name = "f'.$this->itr_.'" type = "hidden" value ="'.$row[$this->itr_].'">';
														break;
														case 2:
														case 3:
							
															echo'<td bgcolor="#FFFFFF"><div align="'.$this->align[$this->itr_].'" class="style18">'.
															'<input name="F'.$this->itr_.'" type="text" id="'.$this->itr.'row2'.'" size="'.$this->width[$this->itr_].'" maxlength="'
															.$this->width[$this->itr_].'" value="'.$row[$this->itr_].'" readonly=" ">'.
															'</div></td>';
															echo '<input name = "f'.$this->itr_.'" type = "hidden" value ="'.$row[$this->itr_].'">';
														break;
														default:
														break;
													};
												};
					
										if ($this->mode != 0)
												{
													switch ($this->mode)
													{
														case 1:
														echo'<td  bgcolor="#FFFFFF" align="left" height="1"><p align="center"><input type="submit" value="Edit" name="B3"></td>';
														break;
														case 2:
													echo'<td  bgcolor="#FFFFFF" align="left" height="1"><p align="center"><input type="submit" value="Delete" name="B3"></td>';
														break;
													};
						
												};
									echo '</tr>';
									echo '</form>';
	  								$this->itr++;
								};

								echo '	<tr>
										<td align="left" height="24" colspan="'.$span.'" bgcolor="#CCCCCC">
										<p align="right"><font face="Arial">
										 Page (-) of (-) pages, generated by GridData (c) 2006 
										</font>
										</td>
										</tr>';
								echo '</table></center></div>';
							//	echo '</form>';

						}

						function	show()
								{
										$this->init();
										$this->proceed();
										$this->drawList();
								}
								
						function	setMode($mode)
								{
										$this->mode = $mode;
								}
						function  	setDataNum($linenum)
								{
									$this->line_num=$linenum;
								}
						function 	setPos($pos)
								{
									$this->start = $pos;
								}

				};

//echo "all class library loaded !!";
?>

