<?php
//require "/var/www/conf/db.inc";
include "/var/www/conf/db.inc";

 
// Test for user input
if (!empty($_POST["authorfn"]) &&
    !empty($_POST["authorln"]) &&
    !empty($_POST["esubject"]) &&
    !empty($_POST["emessage"]) )
   // !empty($_POST["post"]) &&
  //  !empty($_POST["whichlist"]))
{
  if (!empty($_POST['post']))
	$post=1;
else 
	$post=0;


  

if ((!empty($_POST['send3T'])) && (!empty($_POST['send7T'])))
  $whichlist =  'b';
elseif (!empty($_POST['send3T']))
   $whichlist =  '3T';
elseif (!empty($_POST['send7T']))
   $whichlist =  '7T';  
else 
 $whichlist='na';
  


 // Connect to the MySQL server
  $connection = mysql_connect($hostName, $username, $password) or
     die("Cannot connect");

  //$connection = mysql_connect('cfmriweb.ucsd.edu', 'emang', '9tucURnqGAMbfNDd') or
   //  die("Cannot connect");

  $authorfn = mysqlclean($_POST, "authorfn", 50, $connection);
  $authorln = mysqlclean($_POST, "authorln", 50, $connection);
 // $post = mysqlclean($_POST, $post, 1, $connection);
 // $whichlist = mysqlclean($_POST, $whichlist, 3, $connection);
  $esubject = mysqlclean($_POST, "esubject", 50, $connection);
  $emessage = mysqlclean($_POST, "emessage", 2500, $connection);
  
 



  if (!(mysql_select_db($databaseName, $connection)))
//	die("Cannot DB Select");	
    myshowerror( );

  // Insert the new announcement entry
  
  $query = "INSERT INTO emessage VALUES
            (NULL, now(), '{$authorfn}', '{$authorln}', '{$esubject}','{$emessage}','{$post}','{$whichlist}')";

  if (@mysql_query ($query, $connection))
  {
    header("Location: testdbconf.php?status=T&" .
           "mesg_id=". mysql_insert_id($connection));
    exit;
  }
} // if empty()

header("Location: testdbconf.php?status=F");
?>
