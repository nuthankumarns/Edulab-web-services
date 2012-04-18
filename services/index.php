<!doctype html public "-//w3c//dtd xhtml 1.0 strict//en"
    "http://www.w3.org/tr/xhtml1/dtd/xhtml1-strict.dtd">
<!-- Copyright محمد مصطفي شهركي @ http://www.ncis.ir -->
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>Session Set Save Handler DEMO</TITLE>
<META http-equiv="content-type" content="text/html; charset=utf-8"/>
<?PHP
    require_once('sssh.class.php');
?>
</HEAD>
<BODY>
<?PHP
    $session=new MySessionHandler('184.168.226.74','sopdb40','Tritone123','sopdb40');
    session_start();
    echo isset($_SESSION['test'])?$_SESSION['test']:'Test session is not defined';
    $_SESSION['test']='http://www.ncis.ir';
    echo '<BR/>';
    echo isset($_SESSION['value'])?$_SESSION['value']:'value session is not defined';
    $_SESSION['value']='http://www.NCIS.ir';
?>
</BODY>
</HTML>