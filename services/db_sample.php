<?php

  // Include the class source.
  require_once 'private_sessions.class.php';

  // Create an object.
  $ps = new private_sessions();

  // Store session data in MySQL database.
  $ps->save_to_db = true;

  // MySQL access parameters.
/*  $ps->db_host = '184.168.226.74';
  $ps->db_uname = 'sopdb40';
  $ps->db_passwd = 'Tritone123';
  $ps->db_name = 'sopdb40';*/

  // The name of the table used to save session data.
  $ps->save_table = 'mysessions';

  // Optionally create the table if not created.
  //$ps->install_table();

  // Set up session handlers.
  $ps->set_handler();

  // That's all! Proceed to use sessions normally.
  session_start();
  echo $_SESSION['foo'] = 'Hi there!';

?>