<?php
//require "/var/www/conf/db.inc";
include "../php.conf/announcefunc.db.inc.php";
include "announcefunc.inc.php";

// Test for user input
if (!empty($_POST["authorfn"]) &&
    !empty($_POST["authorln"]) &&
    !empty($_POST["esubject"]) &&
	!empty($_POST["topic1"]) &&
    !empty($_POST["emessage1"]) )
{
  if (!empty($_POST['post1']))
	$post1=1;
else 
	$post1=0;
 if (!empty($_POST['tips1']))
	$tips1=1;
else 
	$tips1=0;

if (!empty($_POST['sendboth']))
  $whichlist =  'G';
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
  $esubject = mysqlclean($_POST, "esubject", 150, $connection);
  $topic1 = mysqlclean($_POST, "topic1", 150, $connection);
  $emessage1 = mysqlclean($_POST, "emessage1", 5000, $connection);
 

 
  if (!(mysql_select_db($databaseName, $connection) or die('Could not select database ' . mysql_error())))
//	die("Cannot DB Select");	
    myshowerror( );

  // Insert the new announcement entry


$query = "INSERT INTO emessage VALUES
            (NULL, now(), '{$authorfn}', '{$authorln}', '{$esubject}','{$emessage1}','{$post1}','{$whichlist}','{$tips1}','{$topic1}',
'{$post2}','{$tips2}','{$topic2}','{$emessage2}',
'{$post3}','{$tips3}','{$topic3}','{$emessage3}')";
  
 
 
 
  if (@mysql_query ($query, $connection))
  {
    
//send email
$numoftopics=1;
	$topicst = array ($topic1);
	$topicsbody = array ($emessage1);
	mailtopic($whichlist,$numoftopics,$esubject,$topicst,$topicsbody);
//save to tips
if ($tips1)
 {
	$numoftipstopics = 1;
	{
		$authname=$authorfn." ".$authorln;
		savetips($authname,$whichlist,$numoftipstopics,$esubject,$topicst,$topicsbody);
	}	
  }

//populate post table
if ($post1)
{
	$numofposttopics =1;
	{
		$authname=$authorfn." ".$authorln;
   		saveposts($authname,$whichlist,$numofposttopics,$esubject,$topicst,$topicsbody);
	}
}
    
    header("Location: testdbconf.php?status=T&" .
           "mesg_id=". mysql_insert_id($connection));
    exit;
  }
} // if empty()

header("Location: testdbconf.php?status=F");

?>
