<?php
//require "/var/www/conf/db.inc";
include "../php.conf/announcefunc.db.inc.php";

 
// Test for user input
if (!empty($_POST["numoftopics"]))   
{
  $not = $_POST["numoftopics"];
  if ($not == "1")
       {
print "num of topics =1";
header("Location: oneitem.html?numoftopics=1&" );
    exit;
	
}   
elseif ($not == "2")
{
	header("Location: twoitems.html?numoftopics=2&");
      exit;
}
elseif ($not == "3")
{	
header("Location: threeitems.html?numoftopics=3&");
exit;
}
else
      print "Not Valid";
}

//header("Location: testdbconf.php?status=F");
?>
