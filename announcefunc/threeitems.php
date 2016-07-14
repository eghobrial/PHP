<?php
//require "/var/www/conf/db.inc";
include "../php.conf/announcefunc.db.inc.php";
include "announcefunc.inc.php";

// Test for user input
if (!empty($_POST["authorfn"]) &&
    !empty($_POST["authorln"]) &&
    !empty($_POST["esubject"]) &&
    !empty($_POST["topic1"]) &&
    !empty($_POST["emessage1"]) &&
    !empty($_POST["topic2"]) &&
    !empty($_POST["emessage2"]) &&
!empty($_POST["topic3"]) &&
    !empty($_POST["emessage3"]) )
{
  if (!empty($_POST['post1']))
	$post1=1;
else 
	$post1=0;
if (!empty($_POST['post2']))
	$post2=1;
else 
	$post2=0;
if (!empty($_POST['post3']))
	$post3=1;
else 
	$post3=0;
  if (!empty($_POST['tips1']))
	$tips1=1;
else 
	$tips1=0;
if (!empty($_POST['tips2']))
	$tips2=1;
else 
	$tips2=0;
if (!empty($_POST['tips3']))
	$tips3=1;
else 
	$tips3=0;
if (!empty($_POST['sendboth'])) 
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
  $topic1 = mysqlclean($_POST, "topic1", 50, $connection);
  $emessage1 = mysqlclean($_POST, "emessage1", 2500, $connection);
 $topic2 = mysqlclean($_POST, "topic2", 50, $connection);
  $emessage2 = mysqlclean($_POST, "emessage2", 2500, $connection);
   $topic3 = mysqlclean($_POST, "topic3", 50, $connection);
  $emessage3 = mysqlclean($_POST, "emessage3", 2500, $connection);
  
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
	$numoftopics=3;
	$topicst = array ($topic1,$topic2,$topic3);
	$topicsbody = array ($emessage1,$emessage2,$emessage3);
	mailtopic($whichlist,$numoftopics,$esubject,$topicst,$topicsbody);
//save to tips
if (($tips1)&&($tips2)&&($tips3))
 {
	$numoftipstopics=3;
	$topicst = array ($topic1,$topic2,$topic3);
	$topicsbody = array ($emessage1,$emessage2,$emessage3);
  } elseif (($tips1)&& (!($tips2)) && (!($tips3))) {
	$numoftipstopics=1;
	$topicst = array ($topic1);
	$topicsbody = array ($emessage1);
} elseif (($tips2)&& (!($tips1)) && (!($tips3))) {
	$numoftipstopics=1;
	$topicst = array ($topic2);
	$topicsbody = array ($emessage2);
}elseif (($tips3)&& (!($tips1)) && (!($tips2))) {
	$numoftipstopics=1;
	$topicst = array ($topic3);
	$topicsbody = array ($emessage3);
}elseif (($tips1)&& ($tips2) && (!($tips3))) {
	$numoftipstopics=2;
	$topicst = array ($topic1,$topic2);
	$topicsbody = array ($emessage1,$emessage2);
}elseif (($tips1)&& ($tips3) && (!($tips2))) {
	$numoftipstopics=2;
	$topicst = array ($topic1,$topic3);
	$topicsbody = array ($emessage1,$emessage3);
}elseif (($tips3)&& ($tips2) && (!($tips1))) {
	$numoftipstopics=2;
	$topicst = array ($topic3,$topic2);
	$topicsbody = array ($emessage3,$emessage2);
}
	
if ($numoftipstopics > 0)
{
$authname=$authorfn." ".$authorln;
   savetips($authname,$whichlist,$numoftipstopics,$esubject,$topicst,$topicsbody);
}
   



//populate the post table
if (($post1)&&($post2)&&($post3))
 {
	$numofposttopics=3;
	$topicst = array ($topic1,$topic2,$topic3);
	$topicsbody = array ($emessage1,$emessage2,$emessage3);
  } elseif (($post1)&& (!($post2)) && (!($post3))) {
	$numofposttopics=1;
	$topicst = array ($topic1);
	$topicsbody = array ($emessage1);
} elseif (($post2)&& (!($post1)) && (!($post3))) {
	$numofposttopics=1;
	$topicst = array ($topic2);
	$topicsbody = array ($emessage2);
}elseif (($post3)&& (!($post1)) && (!($post2))) {
	$numofposttopics=1;
	$topicst = array ($topic3);
	$topicsbody = array ($emessage3);
}elseif (($post1)&& ($post2) && (!($post3))) {
	$numofposttopics=2;
	$topicst = array ($topic1,$topic2);
	$topicsbody = array ($emessage1,$emessage2);
}elseif (($post1)&& ($post3) && (!($post2))) {
	$numofposttopics=2;
	$topicst = array ($topic1,$topic3);
	$topicsbody = array ($emessage1,$emessage3);
}elseif (($post3)&& ($post2) && (!($post1))) {
	$numofposttopics=2;
	$topicst = array ($topic3,$topic2);
	$topicsbody = array ($emessage3,$emessage2);

}
if ($numofposttopics > 0)
{
$authname=$authorfn." ".$authorln;
   saveposts($authname,$whichlist,$numofposttopics,$esubject,$topicst,$topicsbody);
}
    header("Location: testdbconf.php?status=T&" .
           "mesg_id=". mysql_insert_id($connection));
    exit;
  }
} // if empty()

header("Location: testdbconf.php?status=F");

?>
