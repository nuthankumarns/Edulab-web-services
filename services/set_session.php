<?php

require_once "session.class.php";
$oSession = new Session();
$idletime=86400;//after 60 seconds the user gets logged out
//$oSession->clean($idletime);
//if ((time()-$_SESSION['timestamp']>$idletime) || (!(isset($_SESSION['UID'])))){
if ((time()-$_SESSION['timestamp']>$idletime) || (!(isset($_SESSION['UID'])))){
unset($_SESSION['UID']);
header("location:logout_api.php");
}else{
    $_SESSION['timestamp']=time();
}
/*if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minates ago
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp*/

//on session creation
/*if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
} else if (time() - $_SESSION['CREATED'] > 1800) {
    // session started more than 30 minates ago
    session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
    $_SESSION['CREATED'] = time();  // update creation time
}*/

?>
