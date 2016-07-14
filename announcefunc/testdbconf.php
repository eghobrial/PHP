<?php
include "../php.conf/announcefunc.db.inc.php";

// Connect to the MySQL server
 $connection =  mysql_connect($hostName, $username, $password) or
     die("Cannot connect");


$status = mysqlclean($_GET, "status", 1, $connection);

switch ($status)
{
  case "T":
    print "Form submitted" ;
    break;
  case "F":
    print "Error";
    break;
  default:
    print "Error";
    break;
}

?>
