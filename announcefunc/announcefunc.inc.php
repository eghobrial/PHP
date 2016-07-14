<?php
//include "../php.conf/announcefunc.db.inc.php";
// This file includes mysqlclean() and shellclean() to secure database values

   
  
   function mailtopic($whichlist,$numoftopics,$esubject,$topicst,$topicbody)
   {


    $headers = "From: " . "cfmri@ucsd.edu" . " \r\n";
	$headers .= "MIME-Version: 1.0 \r\n";
	$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
	$headers .= "Content-Transfer-Encoding: base64 \r\n\r\n";

	//build the message
	$message = '<html><body>';
	if ($numoftopics>1)
	{
		//put a topic list header 
$message .= '<p>________________________________________________________________</p>';
		for ($num=0;$num<$numoftopics;$num++)
		{
			
			$newnum = $num+1;
			$message .= $newnum. '. '.$topicst[$num] ;
			$message .= '</br>';
			
			}
$message .= '<p>________________________________________________________________</p>';
 		for ($num=0;$num<$numoftopics;$num++)
			{
		$message .= '<strong>'. $topicst[$num] .'</strong>';
		$message .= '<p>'. $topicbody[$num] .'</p>';
		}
	}
	else { 
	$num=0;
           $message .= 'Dear CFMRI Users,'; 
	   $message .= '</br>';
	   $message .= '<p>________________________________________________________________</p>';
		$message .= '<strong>'. $topicst[$num] .'</strong>';	
					$message .= '<p>________________________________________________________________</p>';
$message .= '<p>'. $topicbody[$num] .'</p>';
	}
	$message .= '</br>';
       
	//$message .= 'Please feel free to contact us at cfmri@ucsd.edu <mailto:cfmri@ucsd.edu> if you have any questions.';
	//$message .= '<br>';
	$message .= '<br>';
	$message .= 'CFMRI';
	$message .= '</body></html>';
	$message = stripslashes($message);
	$message = chunk_split(base64_encode($message));
	
	if ($whichlist=='3T')
		mail ("fmriusers-l@ucsd.edu", $esubject, $message, $headers);
	elseif ($whichlist=='7T')
		mail ("7tusers-l@ucsd.edu", $esubject, $message, $headers);
	elseif ($whichlist=='G')
	  {
		mail ("fmriusers-l@ucsd.edu", $esubject, $message, $headers);
		mail ("7tusers-l@ucsd.edu", $esubject, $message, $headers);
	}
     
    
   }

function savetips($authname,$whichlist,$numoftipstopics,$esubject,$topicst,$topicsbody)
{
$hostName = 'cfmriweb.ucsd.edu';
 
   //database login information goes here
  //$username = 'XXXXXXXXXXXXXXX';
 //$password = 'XXXXXXXXXXXXXXXXXX';
 $databaseName2 = 'tipsandnews';
// Connect to the MySQL server
  $connection2 = mysql_connect($hostName, $username, $password) or
     die("Cannot connect");

if (!(mysql_select_db($databaseName2, $connection2)))
	die("Cannot DB Select");	
   

for ($num=0;$num<$numoftipstopics;$num++)
			{
  // Insert the new announcement entry

$query = "INSERT INTO Tips VALUES
            (NULL, now(), NULL, '{$topicst[$num]}','{$topicsbody[$num]}','{$authname}', NULL)";
  
  
  if (!(@mysql_query ($query, $connection2)))
  {
   die("Cannot DB Query");
}
}

}

function saveposts($authname,$whichlist,$numofposttopics,$esubject,$topicst,$topicsbody)
{
	$hostName = 'cfmriweb.ucsd.edu';
   	 //database login information goes here
  //$username = 'XXXXXXXXXXXXXXX';
 //$password = 'XXXXXXXXXXXXXXXXXX';
	$databaseName = 'announcedb';

 // Connect to the MySQL server
  $connection3 = mysql_connect($hostName, $username, $password) or
     die("Cannot connect");

if (!(mysql_select_db($databaseName, $connection3)))
	die("Cannot DB Select");	
   

for ($num=0;$num<$numofposttopics;$num++)
			{
  // Insert the new announcement entry

$query = "INSERT INTO posttable VALUES
            (NULL, now(),  '{$topicst[$num]}','{$topicsbody[$num]}','{$authname}', '{$whichlist}',NULL)";
  
  
  if (!(@mysql_query ($query, $connection3)))
  {
   die("Cannot DB Query");
}
}

}

  
?>

