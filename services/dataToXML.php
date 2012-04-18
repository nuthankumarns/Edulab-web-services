<?
require_once('fungsi.dwt.php')

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Test XML</title>

<style type="text/css">
<!--
.style1 {font-family: Arial, Helvetica, sans-serif}
body {
	background-image: url(image542.gif);
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<body>
<p>
  <?	
  		//this sample will demo the use of dataToXML class
		//	
  		//XML CLASS 
		$myData = new dataToXML("myXML.xml"); // define your xml file
		$myData->setQueryString("select bln,thn,nopek,nama,thp_tetap,uang_cuti
								 from gaji_2002 where bln = '5' and thn ='2002' order by nopek asc");
		$myData->createXML(); // generate XML from Mysql Data Records
		$myData->setProperty("Payroll List",
							array("Month","Year","Worker ID","Name","Sallary","Bonus"),
							array("right","right","right","left","right","right"),
							array(50,50,60,200,150,150)
							); //setting property
							
		echo '<form id="form1" name="form1" method="post" action="">';
 		//the form type field column named Fn...where n is the column idx respect of field displayed
		//F1,F2,F2....and so on
		//(Posted as F1..F2...)
 					echo "THIS IS FORM MODE ";  				
 							$myData->showForm(NULL); // display as form
 					echo "THIS IS LIST MODE";
 							$myData->showList(); // display as list
				
		echo '</form>';
?>
</p>
<p>&nbsp;</p>

<p>&nbsp;</p>
<div align="center">

						<p class="style1">(c)Yakumba Training and Sofware Developer</p>
						<p class="style1">MySQL XML Generator  </p>
</div>
</body>

</html>
