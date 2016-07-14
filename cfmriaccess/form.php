<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="UTF-8">
<title>CFMRI Access Form (has to be requested by the PI)
</title>
<link href="form.css" rel="stylesheet" media="screen">
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-18831199-1");
pageTracker._trackPageview();
} catch(err) {}
</script>
<style type="text/css">
.style1 {
	color: #FF0000;
}
.style2 {
	color: #FF0000;
	font-weight: bold;
}
a:link {
	color: blue;
	text-decoration: underline;
	text-underline: single;
}
fieldset {
	width: 367;
	padding: 2px;
}
</style>
</head>

<body>
<div id="content">

<?php

$page="1";
$lname="";
$fname="";
$email="";
$message="";
$safetydate="";

function error_bool($error, $field) {
    if($error[$field]) {
		print(" style='color: red' ");
    }
    else {
		print("");
    }
}
  
#start of page 1  
function show_form() {
global $HTTP_POST_VARS, $error, $print_again, $lname, $fname, $email;
?>
<h1>CFMRI Access Form</h1>
<p>This Form is for requesting access to the CFMRI building after hours, access to the 3T Scanners, the 7T Scanner and the Mock Scanner. This form is also used for adding Certified Operators to the CFMRI Webschedule. Only CFMRI Safety Trained personnel are eligible to request access. The level of access will be granted based on the current training status. </p>

<p>The person requesting access must complete and submit this form. An automatic email will be sent to the PI on record for approval. Upon receiving the PI's approval, CFMRI will email further instructions on how to obtain access.<br />
</p>


<p>The requester must be familiar with the current <a href="http://fmri.ucsd.edu/policies.html"> CFMRI policies regarding magnet room access, operator status, and PI-related activities </a> before submitting this form.</p>

<span class='style1'>Please provide name and email address used for Safety Training registration</span>

<form method="post" action="">
 <?php
$ipi = getenv("REMOTE_ADDR");
$httprefi = getenv ("HTTP_REFERER");
$httpagenti = getenv ("HTTP_USER_AGENT");
?>
 <input type="hidden" name="ip" value="<?php echo $ipi ?>" />
 <input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
 <input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />
 
 <fieldset>
  <legend>Requester Info:</legend>
  <div>
<label>Last Name:</label>
<input type="text" name="lname" size="35" value="<?php echo $lname ?>" /> </div>
<div><label>First Name:</label>
<input type="text" name="fname" size="35" value="<?php echo $fname ?>" /> </div>

<div><label>Email:</label>
<input type="text" name="email" size="35" value="<?php echo $email ?>" />
<strong><span class="style1">*Valid Organizational email address only</span></strong> </div>



  <br />
 </fieldset>
<input type="submit" name="Submit" value="Submit" />
</form>
<p>
<?php
}
#end of page1
	
#start of page 2
function show_form2() {

global $HTTP_POST_VARS, $error, $print_again, $lname, $fname, $email, $safetydate;
$ipi = getenv("REMOTE_ADDR");
$httprefi = getenv ("HTTP_REFERER");
$httpagenti = getenv ("HTTP_USER_AGENT");
$lname=$_POST["lname"];
$email=$_POST["email"];
$fname=$_POST["fname"];
$_POST["safetydate"]=$safetydate=check_safetydate($email,$lname);

$link = ConnectMysql();
$email = strtolower(mysql_real_escape_string($_POST['email']));
$lname = mysql_real_escape_string($_POST['lname']);
$fname = mysql_real_escape_string($_POST['fname']);
$query = "SELECT lname,fname,email,phone,organization,department,job,pi_name,piemail from Registration WHERE email='$email' and lname='$lname'";
$results = QueryMysql($link,$query);
$row = @mysql_fetch_row($results);
CloseMysql($link);

$lname=$row[0];
$fname=$row[1];
$email=$row[2];
$phone=$row[3];
$org=$row[4];
$dep=$row[5];
$job=$row[6];
$piname=$row[7];
$piemail=$row[8];

$opstatus = "Safety $safetydate";
# check if user is an operator
$operator = isoperator($_POST["email"],$_POST["lname"]);
if ($operator ){$opstatus .= ", Operator $operator";}
# check if user is an mock scanner operator
$mockscan = ismockscan($_POST["email"],$_POST["lname"]);
if ($mockscan ){$opstatus .= ", Mock Scanner $mockscan";}

?>
<h1>CFMRI Access Form (Page 2)</h1>
<form method="post" action="">

<input type="hidden" name="ip" value="<?php echo $ipi ?>" />
<input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
<input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />
<input type="hidden" name="page" value="2valid" />
<input type="hidden" name="lname" value="<?php echo $lname; ?>" />
<input type="hidden" name="fname" value="<?php echo $fname; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="piemail" value="<?php echo $piemail; ?>" />
<input type="hidden" name="safetydate" value="<?php echo $safetydate; ?>" />
<input type="hidden" name="opstatus" value="<?php echo $opstatus; ?>" />

<fieldset>
<legend>User Info:</legend>

<div><label>Last Name:</label><output><?php echo $lname; ?></output></div>
<div><label>First Name:</label><output><?php echo $fname; ?></output></div>
<div><label>Email:</label><output><?php echo $email; ?></output></div>
<div><label>Training Status:</label><output><?php echo $opstatus; ?></output></div>
<div><label>Phone:</label><output><?php echo $phone; ?></output></div>
<div><label>Org:</label><output><?php echo $org; ?></output></div>
<div><label>Department:</label><output><?php echo $dep; ?></output></div>
<div><label>Job Title:</label><output><?php echo $job; ?></output></div>
<div><label>PI Name:</label><output><?php echo $piname; ?></output></div>
<div><label>PI Email:</label><output><?php echo $piemail; ?></output></div>
</fieldset>
<p>
The information above is the most current information in CFMRI records.
If any of the information is not correct, please click CANCEL and email cfmri@ucsd.edu with updated information, otherwise click NEXT to proceed.

</p>
<p />
<input type="submit" name="Submit2" value="NEXT" />
</form>
<form method="post">
<input type="button" value="CANCEL"
onclick="window.location.href='http://fmri.ucsd.edu'">
</form>
<p><?php
}
#end of page 2

#start of page 3
function show_form3op() {

global $HTTP_POST_VARS, $error, $print_again, $lname, $fname, $email, $safetydate;
$ipi = getenv("REMOTE_ADDR");
$httprefi = getenv ("HTTP_REFERER");
$httpagenti = getenv ("HTTP_USER_AGENT");
$lname=$_POST["lname"];
$email=$_POST["email"];
$fname=$_POST["fname"];
$_POST["safetydate"]=$safetydate=check_safetydate($email,$lname);

$link = ConnectMysql();
$email = strtolower(mysql_real_escape_string($_POST['email']));
$lname = mysql_real_escape_string($_POST['lname']);
$fname = mysql_real_escape_string($_POST['fname']);
$query = "SELECT lname,fname,email,phone,organization,department,job,pi_name,piemail from Registration WHERE email='$email' and lname='$lname'";
$results = QueryMysql($link,$query);
$row = @mysql_fetch_row($results);
CloseMysql($link);

$lname=$row[0];
$fname=$row[1];
$email=$row[2];
$phone=$row[3];
$org=$row[4];
$dep=$row[5];
$job=$row[6];
$piname=$row[7];
$piemail=$row[8];

$opstatus = "Safety $safetydate";
# check if user is an operator
$operator = isoperator($_POST["email"],$_POST["lname"]);
if ($operator ){$opstatus .= ", Operator $operator";}
# check if user is an mock scanner operator
$mockscan = ismockscan($_POST["email"],$_POST["lname"]);
if ($mockscan ){$opstatus .= ", Mock Scanner $mockscan";}

?>
<h1>CFMRI Access Form (Page 3)</h1>
<form method="post" action="">

<input type="hidden" name="ip" value="<?php echo $ipi ?>" />
<input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
<input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />
<input type="hidden" name="page" value="2valid" />
<input type="hidden" name="lname" value="<?php echo $lname; ?>" />
<input type="hidden" name="fname" value="<?php echo $fname; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="safetydate" value="<?php echo $safetydate; ?>" />
<input type="hidden" name="opstatus" value="<?php echo $opstatus; ?>" />
<input type="hidden" name="reason" value="operator" />

<fieldset>
<legend>User Info:</legend>

<div><label>Last Name:</label><output><?php echo $lname; ?></output></div>
<div><label>First Name:</label><output><?php echo $fname; ?></output></div>
<div><label>Email:</label><output><?php echo $email; ?></output></div>
<div><label>Training Status:</label><output><?php echo $opstatus; ?></output></div>
<div><label>Phone:</label><output><?php echo $phone; ?></output></div>
<div><label>Org:</label><output><?php echo $org; ?></output></div>
<div><label>Department:</label><output><?php echo $dep; ?></output></div>
<div><label>Job Title:</label><output><?php echo $job; ?></output></div>
<div><label>PI Name:</label><output><?php echo $piname; ?></output></div>
<div><label>PI Email:</label><output><?php echo $piemail; ?></output></div>
</fieldset>

<fieldset>
<legend>Request:</legend>

    <div class="radio">
      <fieldset>
        <legend><span <?php error_bool($error, "access"); ?>>Access: </span></legend>
		<div><input type="checkbox" value="After Hour" name="baah" /><label>Building After Hours</label></div>
		<div><input type="checkbox" value="3Ts" name="ba3t" /><label>3Ts (requires 3T operator training)</label></div>
		<div><input type="checkbox" value="7T" name="ba7t" /><label>7T (requires 7T operator training)</label></div>
		<?php
		if($mockscan) echo "<div><input type='checkbox' value='MockScanner' name='bams' /><label>Mock Scanner</label></div>";
		?>
		 </fieldset>
		 </div>
    <div class="radio">
      <fieldset>
        <legend><span <?php error_bool($error, "web"); ?>>Add to webschedule dropdown menu: </span></legend>
		<div><input type="checkbox" value="3tw" name="web3tw" /><label>3TW</label></div>
		<div><input type="checkbox" value="3te" name="web3te" /><label>3TE (requires 3T operator training)</label></div>
		<div><input type="checkbox" value="7t" name="web7t" /><label>7T (requires 7T operator training)</label></div>
		 </fieldset>
		 </div>
</fieldset>
<p />
<input type="submit" name="Submit3" value="Submit" />

</form>
<p><?php
}
#end of page 3


#start of page 3 for non operator
function show_form3nop() {

global $HTTP_POST_VARS, $error, $print_again, $lname, $fname, $email, $safetydate;
$ipi = getenv("REMOTE_ADDR");
$httprefi = getenv ("HTTP_REFERER");
$httpagenti = getenv ("HTTP_USER_AGENT");
$lname=$_POST["lname"];
$email=$_POST["email"];
$fname=$_POST["fname"];
$_POST["safetydate"]=$safetydate=check_safetydate($email,$lname);

$link = ConnectMysql();
$email = strtolower(mysql_real_escape_string($_POST['email']));
$lname = mysql_real_escape_string($_POST['lname']);
$fname = mysql_real_escape_string($_POST['fname']);
$query = "SELECT lname,fname,email,phone,organization,department,job,pi_name,piemail from Registration WHERE email='$email' and lname='$lname'";
$results = QueryMysql($link,$query);
$row = @mysql_fetch_row($results);
CloseMysql($link);

$lname=$row[0];
$fname=$row[1];
$email=$row[2];
$phone=$row[3];
$org=$row[4];
$dep=$row[5];
$job=$row[6];
$piname=$row[7];
$piemail=$row[8];

$opstatus = "Safety $safetydate";
# check if user is an operator
$operator = isoperator($_POST["email"],$_POST["lname"]);
if ($operator ){$opstatus .= ", Operator $operator";}
# check if user is an mock scanner operator
$mockscan = ismockscan($_POST["email"],$_POST["lname"]);
if ($mockscan ){$opstatus .= ", Mock Scanner $mockscan ";}

?>
<h1>CFMRI Access Form (Page 3)</h1>
<form method="post" action="">

<input type="hidden" name="ip" value="<?php echo $ipi ?>" />
<input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
<input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />
<input type="hidden" name="page" value="2valid" />
<input type="hidden" name="lname" value="<?php echo $lname; ?>" />
<input type="hidden" name="fname" value="<?php echo $fname; ?>" />
<input type="hidden" name="email" value="<?php echo $email; ?>" />
<input type="hidden" name="safetydate" value="<?php echo $safetydate; ?>" />
<input type="hidden" name="opstatus" value="<?php echo $opstatus; ?>" />

<fieldset style="width: 367; padding: 2">
<legend>User Info:</legend>

<div><label>Last Name:</label><output><?php echo $lname; ?></output></div>
<div><label>First Name:</label><output><?php echo $fname; ?></output></div>
<div><label>Email:</label><output><?php echo $email; ?></output></div>
<div><label>Training Status:</label><output><?php echo $opstatus; ?></output></div>
<div><label>Phone:</label><output><?php echo $phone; ?></output></div>
<div><label>Org:</label><output><?php echo $org; ?></output></div>
<div><label>Department:</label><output><?php echo $dep; ?></output></div>
<div><label>Job Title:</label><output><?php echo $job; ?></output></div>
<div><label>PI Name:</label><output><?php echo $piname; ?></output></div>
<div><label>PI Email:</label><output><?php echo $piemail; ?></output></div>
</fieldset>

<fieldset style="width: 367; padding: 2">
<legend>Request:</legend>

    <div class="radio">
      <fieldset>
        <legend><span <?php error_bool($error, "access"); ?>>Access: </span></legend>
		<div><input type="checkbox" value="3Ts" name="ba3t" /><label>3Ts (requires 3T operator training)</label></div>
		<div><input type="checkbox" value="7T" name="ba7t" /><label>7T (requires 7T operator training)</label></div>
		<?php
		if($mockscan) echo "<div><input type='checkbox' value='MockScanner' name='bams' /><label>Mock Scanner</label></div>";
		?>
		 </fieldset>
		 </div>
   <p> Our policy only allows Certified Operators to access the MRI rooms. If you are requesting access to the 3T/7T MRI rooms, please provide a justification below including details of your role in the project. Please note that your request must be approved by both your PI and CFMRI before access can be granted. </p>
   <div><label>Justification:</label><textarea name="reason" cols="90" rows="5" /></textarea></div>
</fieldset>
<p />
<input type="submit" name="Submit3" value="Submit" /> 
</form>
<p><?php
}
#end of page 3


if(isset($_POST["Submit"]) && !(isset($_POST["Submit2"])) && !(isset($_POST["Submit3"]))) {
  check_form();
}
elseif(isset($_POST["Submit2"]) && !(isset($_POST["Submit3"]))) {

  check_form2();

} 
elseif(isset($_POST["Submit3"])) {

  check_form3();

} 
 else{
  show_form();
}

function check_safety($email,$lname) { 
	$link = ConnectMysql2();
	$query = "SELECT `email`,`date` FROM `SFresults` WHERE `email`='$email' and `lname` Like '$lname'";
	$results = mysql_query($query, $link) or die('Query failed' . mysql_error() . $errormessage);

	if (mysql_num_rows($results) == 0){ // if doesn't exist
		#print "User not found";
		return false;
	}

	$row = @mysql_fetch_row($results);
	$datestamp = date("Y-m-d");
	$string1 = strtotime($datestamp); // first date
	$string2 = strtotime($row[1]); // second 
	$difference = $string1-$string2;
	$DifferenceInDays = $difference/86400; // one day equals to 86400 seconds
	if ($DifferenceInDays>400) {
		print "ERROR: Operator Safety test is older than 400 days<br>";
		
		return false;
	}

	CloseMysql($link);
	return true;
}

function check_safetydate($email,$lname) {
	$link = ConnectMysql();
	$query = "SELECT `safety_date` FROM `Registration` WHERE `email`='$email' AND `lname` Like '$lname' AND Deleted is NULL";
	$results = QueryMysql($link,$query);
	if (mysql_num_rows($results) == 0){ // if doesn't exist
		print "User not found";
		return false;
	}
	$row = @mysql_fetch_row($results);
	CloseMysql($link);
	 return $row[0];
}

function isoperator($email,$lname) { 
	$link = ConnectMysql2();
	$query = "SELECT OpTrainingSer.datetrained FROM OpTrainingRequest,OpTrainingSer WHERE (OpTrainingRequest.pid = OpTrainingSer.pid) AND Deleted IS NULL AND opemail='$email' AND lname='$lname'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);
	CloseMysql($link);

	if ($row[0]){
		return $row[0];
	} else{
		return false;
	}
}

function ismockscan($email,$lname) { 
	$link = ConnectMysql2();
	$query = "SELECT MockTrainingSer.datetrained FROM MockTrainingRequest,MockTrainingSer WHERE (MockTrainingRequest.pid = MockTrainingSer.pid) AND Deleted IS NULL AND opemail='$email' AND lname='$lname'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);
	CloseMysql($link);

	if ($row[0]){
		return $row[0];
	} else{
		return false;
	}
}

function userexist($email,$lname) { # Gets pid of user
	$link = ConnectMysql();
	$query = "SELECT Registration.user_id FROM Registration,accesslist WHERE Registration.user_id=accesslist.user_id AND email='$email' AND lname Like '$lname'";
	$results = QueryMysql($link,$query);
	if (mysql_num_rows($results) == 0){ // if doesn't exist
		return false;
	}
	$row = @mysql_fetch_row($results);
	CloseMysql($link);
	return $row[0];
}


function check_email_address($email) {
	# First, we check that there's one @ symbol, and that the lengths are right
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
	#Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	 #Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);

	for ($i = 0; $i < sizeof($local_array); $i++) {
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
			return false;
		}
		
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { #Check if domain is IP. If not, it should be valid domain name
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
			return false; # Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) {
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
			return false;
			}
		}
		if (preg_match("/hotmail|yahoo|ymail|rr|gmail|msn|aol|earthlink/i", $domain_array) )
		{
			return false;
		}
	}
	 
	 return true;
}

function check_date($strdate) {
	$date_pattern = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
	if (!preg_match($date_pattern,$strdate)){
		return false;
	}
	return true;
}

function check_form()
{
	global $HTTP_POST_VARS, $error, $print_again, $page, $lname, $fname, $email, $safetydate;
	$datestamp = date("Y-m-d");
	$link = ConnectMysql();
	$lname = mysql_real_escape_string($_POST["lname"]);
	$fname = mysql_real_escape_string($_POST["fname"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$message="";
	if($lname=="") {
		$print_again = true;
		$message.="<span class='style1'>ERROR: The User last name field is empty</span><br>";
	}
	if($fname=="") {
		$print_again = true;
		$message.="<span class='style1'>ERROR: The User first name field is empty</span><br>";
	}
	if(!check_email_address($email)) {
		$error['email'] = true;
		$print_again = true;
		$message.="<span class='style1'>ERROR: User Email Field is either empty, invalid. Personal email addresses are not vaild.</span><br>";
	}
	if(!check_safety($email,$lname)) {
		$print_again = true;
		$message.="<span class='style1'>ERROR: User not found or User has not completed the safety training and online test. Please contact fmri-support@ucsd.edu if you believe this is incorrect.</span>";
	}	
	if($print_again) {
		show_form();
		echo "$message";
	}
	else {
		$page="2";
		if(userexist($_POST["email"],$_POST["lname"])){
			$page="exist";
			echo "<h2>CFMRI records show that you already have submitted an Access Form.</h2> Please contact fmri-support@ucsd.edu if you need to make any changes or if you need assistance.\n"; 
		}else{
			$page="new";
			show_form2();
			#show_form2ra();
		}	  

		echo "$message";
		}	
}

function check_form2(){
	global $HTTP_POST_VARS, $error, $print_again, $page, $lname, $fname, $email, $safetydate;
	$datestamp = date("Y-m-d");
	$link = ConnectMysql();
	$lname = mysql_real_escape_string($_POST["lname"]);
	$fname = mysql_real_escape_string($_POST["fname"]);
	$email = mysql_real_escape_string($_POST["email"]);
	$piemail = mysql_real_escape_string($_POST["piemail"]);
	$message="";

	if($piemail=="") {
		$print_again = true;
		$message.="<span class='style1'>ERROR: The PI Email field is empty, please contact fmri-support@ucsd.edu to update your records before continuing</span><br>";
	}
		  
	if(userexist($_POST["email"],$_POST["lname"])){
		$page="exist";
		$print_again = true;
		echo "<span class='style1'><h2>Error: User already exist.</h2></span>\n"; 
	}
	
	if($print_again) {
		show_form2();
		echo "$message";
	}
	
	else{
		$page="3";
		if(isoperator($_POST["email"],$_POST["lname"])){
			$page="operator";
			show_form3op();
		}else{
			$page="safety";
			show_form3nop();
		}	  
	}	  

	CloseMysql($link);
}

function check_form3()
{
	global $HTTP_POST_VARS, $error, $print_again, $page, $lname, $fname, $email, $safetydate;
	$datestamp = date("Y-m-d");

	if( ($_POST["baah"]=="") && ($_POST["ba3t"]=="") && ($_POST["ba7t"]=="") && ($_POST["bams"]=="")) {
		$print_again = true;
		$message="<span class='style1'>ERROR: Please select an access area</span><br>";
	}
	if(strlen($_POST["reason"])<7) {
		$print_again = true;
		$message.="<span class='style1'>ERROR: Please provide a justification  for your request.</span><br>";
	}
   if($print_again) {
		echo "$message";
		if (isoperator($_POST["email"],$_POST["lname"])){
			$page="operator";
			show_form3op();
		}
		else{
			show_form3nop();
		}
	}
	else {
		submitform();
		echo "Your CFMRI Access Form has been received and is awaiting PI approval.<br>
	Upon receiving PI approval, CFMRI will email you further instructions on obtaining access to CFMRI.";
	}
  

}

function submitform()
{
	global $HTTP_POST_VARS, $error, $print_again;
	// Connecting, selecting database
	$link = ConnectMysql();
	$ip = mysql_real_escape_string($_POST['ip']);
	$httpref = mysql_real_escape_string($_POST['httpref']);
	$httpagent = mysql_real_escape_string($_POST['httpagent']);
	$todayis = date("l, F j, Y, g:i a") ;
	$todayis = date("l, F j, Y, g:i a") ;
	$strTo = $strFrom = $email = strtolower(mysql_real_escape_string($_POST['email']));
	$lname = mysql_real_escape_string($_POST['lname']);
	$fname = mysql_real_escape_string($_POST['fname']);
	$opstatus = mysql_real_escape_string($_POST['opstatus']);
	$reason = mysql_real_escape_string($_POST['reason']);
	$baah=$ba3t=$ba7t=$bams="NULL";
	$web3tw=$web3te=$web7t="NULL";
	if($_POST["baah"]){$baah=1; $accesslevel .= "[Building After Hours] ";}
	if($_POST["ba3t"]){$ba3t=1; $accesslevel .= "[3T] ";}
	if($_POST["ba7t"]){$ba7t=1; $accesslevel .= "[7T] ";}
	if($_POST["bams"]){$bams=1; $accesslevel .= "[MockScanner] ";}
	if($_POST["web3tw"]){$web3tw=1; $webschedule .= "[3TW] ";}
	if($_POST["web3te"]){$web3te=1; $webschedule .= "[3TE] ";}
	if($_POST["web7t"]){$web7t=1; $webschedule .= "[7T] ";}

	$query = "SELECT lname,fname,email,phone,organization,department,job,pi_name,piemail from Registration WHERE email='$email' and lname='$lname'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);
	$lname=$row[0];
	$fname=$row[1];
	$email=$row[2];
	$phone=$row[3];
	$org=$row[4];
	$dep=$row[5];
	$job=$row[6];
	$piname=$row[7];
	$piemail=$row[8];


	$hcode=hashcode($email);

	// Connecting, selecting database
	$link = ConnectMysql();

	// Performing SQL query
	$datestamp = date("Y-m-d");
	$results = "" ;

	$query = "SELECT `user_id` FROM Registration WHERE email like '$email' and lname Like '$lname'";

	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);


	$uid = $row[0];

	$query = "INSERT INTO accesslist (user_id,postdate,reqip,reason,hcode,afterhour,3t,7t,mock) VALUES ($uid,CURRENT_TIMESTAMP,'$ip','$reason','$hcode',$baah,$ba3t,$ba7t,$bams)";
	$results = QueryMysql($link,$query);
	#	$pid=userexist($email,$lname);

	if (preg_match("/ucsd/i", $org)) {
		$query = "INSERT INTO receipts (user_id,paydate,payment,receivedby) VALUES ('$uid',CURRENT_TIMESTAMP,'SOM','SOM')";
		$results = QueryMysql($link,$query);
	} else {
		$query = "INSERT INTO receipts (user_id) VALUES ('$uid')";
		$results = QueryMysql($link,$query);
	}


	$query = "INSERT INTO websch (user_id,piemail,3te, 3tw,7ta) VALUES ('$uid','$piemail',$web3te,$web3tw,$web7t)";
	$results = QueryMysql($link,$query);

	// Closing connection
	CloseMysql($link);




	// Send confirmation email

	$subject = "CFMRI Access Request for $fname $lname";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";


	$to = "$email";
	$message = "Dear CFMRI User,<br>
	Your CFMRI Access Form has been received and is awaiting PI approval.<br>
	Upon receiving PI approval, CFMRI will email you further instructions on obtaining access to CFMRI.
	<br><br>
	Thank you,<br>
	CFMRI<br>
	<br>
	";

	// Additional headers
	$headers .= 'Bcc: CFMRI Webmaster <fmri-support@ucsd.edu>' . "\r\n";
	$headers .= 'From: CFMRI <cfmri@ucsd.edu>' . "\r\n";

	mail($to, $subject, $message, $headers);



	$message="
	<html>
	<head>
	 <title>CFMRI Access Request for $fname $lname </title>
	</head>
	<body>
	Dear PI,<br>
	The following user has requested access to CFMRI. Please carefully review this request and confirm your approval by clicking on the link at the bottom of this email.
	<p>
	Last Name: $lname<br>
	First Name: $fname<br>
	Email: $email<br>
	Training Status:  $opstatus<br>
	Phone: $phone<br>
	Org: $org<br>
	Department: $dep<br>
	Job Title: $job<br>
	PI Name: $piname<br>
	PI Email: $piemail
	</p>
	<p>
	Requested Access Level: $accesslevel <br>
	Requested webschedule: $webschedule <br>
	Reason requesting access: $reason <br>
	</p>
	<p>
	<a href='https://cfmriweb.ucsd.edu/cfmriaccess/piapproval.php?action=approval&code=".$hcode."'>Please click here to Confirm or Deny this request.</a>
	</p>
	$todayis [PST] <br>
	</body>
	</html>
	";

	#if ($email != $piemail) $headers .= "To: $fname $lname <$email>;\n"; #if the operator is not same as the pi send mail to pI
	#$headers .= "From: CFMRI Webmaster <fmri-support@ucsd.edu>;\n";
	#$headers .= "Cc: CFMRI Webmaster <fmri-support@ucsd.edu>;\n";
	#$ccopy = "Cc: Bob <rbussell@ucsd.edu>, Eman Ghobrial <eghobrial@ucsd.edu>, Kun Lu <kulu@ucsd.edu>;\n"; 

	#$headers .= $ccopy;

	if (mail($piemail, $subject, $message, $headers)) {
		echo("<p>Request successfully sent!</p>");
	}
	else {
		echo("<p>Request delivery failed...</p>");
	}

}

function hashcode($email) {
	$salt = "9275a213526a52dc1b52e64ff3df6766";
	$code = md5($salt.$email);
	$link = ConnectMysql();
	while ( !$finished ){
		$query = "SELECT `hcode` from `accesslist` WHERE `hcode`= '$code'";
		$results = QueryMysql($link,$query);
		$row = @mysql_fetch_row($results);
		if ( !$row ){
			$finished = true;
		}
		else{
			$code = md5($salt.$email.rand());
		}
	} 
	CloseMysql($link);

	return $code;

}

function ConnectMysql() {
	//connect to database, credential removed for secuirty
	$link = mysql_connect('cfmriweb.ucsd.edu', 'XXXXXXXXXXXXXXXX', 'XXXXXXXXXXXXXXXX') or die('Could not connect: ' . mysql_error());
	mysql_select_db('cfmriTraining', $link) or die('Could not select database ' . mysql_error());
	return $link;
}

function ConnectMysql2() {
	$link = mysql_connect('cfmriweb.ucsd.edu', 'XXXXXXXXXXX', 'XXXXXXXXXXXXXXX') or die('Could not connect: ' . mysql_error());
	mysql_select_db('TrainingInfo', $link) or die('Could not select database ' . mysql_error());
	return $link;
}

function QueryMysql($link,$query) {
	$result = mysql_query($query) or die("Query failed: " . mysql_error());
	return $result;
}

function CloseMysql($link) {
	
}


?> </p>
</div>
</body>

</html>
