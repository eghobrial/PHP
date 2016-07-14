<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="UTF-8">
<title>CFMRI Access PI Approval Form</title>
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
<!--
.style1 {color: #FF0000}
div#content {
	float: left;
	text-align: left;
	width: 800px;
	background: transparent;

	margin: 0 auto auto 30px;
}
BODY {
	font-family: "Trebuchet MS", Verdana, Arial;
	background-color: white;
	COLOR: #000000;
}

P {
	MARGIN: 8px 0px 8px 0px;
	font-family: "Trebuchet MS", Verdana, Arial;
	font-size: 12pt;
}
	
H1 {
	PADDING-TOP: 20px;
	MARGIN: 0px 0px 0px 0px;
	FONT: bold normal large "Century Gothic", Trebuchet, Verdana, Geneva, Helvetica, Arial, sans-serif;

}

H2 {
	PADDING-TOP: 5px;
	MARGIN: 0px 0px 0px 0px;
	FONT: bold medium "Century Gothic", Trebuchet, Verdana, Geneva, Helvetica, Arial, sans-serif;

	text-transform: none;
}
H3 {
	PADDING-TOP: 10px;
	MARGIN: 10px 0px 0px 0px;
	FONT: bold small "Trebuchet MS", Verdana, Geneva, Helvetica, Arial, sans-serif;
	COLOR: #000000;
	PADDING-TOP: 0px;
}

blockquote {
	background: #d0dcdf;
	border: solid 1px #aaa;
	color: inherit;
	margin: 5px 0;
	padding: 20px;
}

-->
</style>
</head>

<body>

<?php

function error_bool($error, $field){
	if($error[$field]){
		print("<font style=color:red>");
	}
    else {
		print("");
    }
  }

function show_form(){
	global $HTTP_POST_VARS, $print_again, $error, $a, $uid;
	$a = $_GET['code'];
	$link = ConnectMysql();
	$query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,job,pi_name,Registration.piemail,safety_date,operator_date,mockscan,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,websch.3te,websch.3tw,websch.7ta from Registration,accesslist,websch WHERE Registration.user_id=accesslist.user_id and Registration.user_id=websch.user_id and hcode='$a'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);

	$uid=$row[0];
	$lname=$row[1];
	$fname=$row[2];
	$email=$row[3];
	$phone=$row[4];
	$org=$row[5];
	$dep=$row[6];
	$job=$row[7];
	$piname=$row[8];
	$piemail=$row[9];
	$safetydate=$row[10];

$operator=$row[11];
$mockscan=$row[12];
$opstatus = "Safety $safetydate";

if ($operator ){$opstatus .= ", Operator $operator";}

if ($mockscan ){$opstatus .= ", Mock Scanner $mockscan ";}

	
	if($row[13]){$accesslevel .= "[Building After Hours] ";}
	if($row[14]){$accesslevel .= "[3T] ";}
	if($row[15]){$accesslevel .= "[7T] ";}
	if($row[16]){$accesslevel .= "[Mock Scanner] ";}
	if($row[17]){$webschedule .= "[3TW] ";}
	if($row[18]){$webschedule .= "[3TE] ";}
	if($row[19]){$webschedule .= "[7T] ";}

	CloseMysql($link);
?>
<div id="content">
<IMG height='2' src='foot.jpg' width='800'>
<img src="head1.jpg">
<IMG height='2' src='foot.jpg' width='800'>
<h1>CFMRI Access PI Approval Form</h1>

<form method="post" action="">
	<?php
$ipi = getenv("REMOTE_ADDR");
$httprefi = getenv ("HTTP_REFERER");
$httpagenti = getenv ("HTTP_USER_AGENT");
?>
<input type="hidden" name="ip" value="<?php echo $ipi ?>" />
<input type="hidden" name="httpref" value="<?php echo $httprefi ?>" />
<input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />
<input type="hidden" name="code" value="<?php echo $a ?>" />
<input type="hidden" name="uid" value="<?php echo $uid ?>" />

The following user has requested access to CFMRI. Please carefully review this request and confirm your approval below.
<fieldset style="width: 367; padding: 2">
<legend>User Info:</legend>
Last Name: <?php echo $lname; ?><br>
First Name: <?php echo $fname; ?><br>
Email: <?php echo $email; ?><br>
Training Status:  <?php echo $opstatus; ?><br>
Phone: <?php echo $phone; ?><br>
Org: <?php echo $org; ?><br>
Department: <?php echo $dep; ?><br>
Job Title: <?php echo $job; ?><br>
PI Name: <?php echo $piname; ?><br>
PI Email: <?php echo $piemail; ?><br>
</p>
<p>
Requested Access Level: <?php echo $accesslevel; ?><br>
Requested webschedule: <?php echo $webschedule; ?>
</p>
</fieldset>
<blockquote>		
    <h2>Do you approve this request?</h2>
    <table id="Responses" width="301" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td width="20"><input name="status" type="radio" value="Approved"></td>
      <td width="400"><span class="style2">Approve Request</span></td>
     </tr>
     <tr>
      <td width="20"><input name="status" type="radio" value="Denied"></td>
      <td width="400"><span class="style2">Deny Request</span></td>
     </tr>
    </table>

	<h3>Comments: (optional)<br>
    <textarea name="CommentsText" 
				 cols="90" rows="4" 
				 onFocus="this.value=''"
				 wrap="VIRTUAL" id="CommentsText"></textarea>
		</h3>
	
</blockquote>		
		
		
	<br />
	<input type="submit" name="Submit" value="Submit" />
	<input type="reset" value="Reset" />
</form>
<br>
<br>
</div>


<?php
} #End of form

if(isset($_POST["Submit"])) {
	check_form();
	}
	else {
		if ($_GET['action'] == "approval") {
			$link=ConnectMysql();
			$a = $_GET['code'];
			$query = "SELECT hcode,adate from accesslist WHERE hcode = '$a'";
			$results = QueryMysql($link,$query);	
			$row = @mysql_fetch_row($results);
			$code = $a;
			$coded = $row[0];
			$adate = $row[1];
			CloseMysql($link);

			if (($a == $coded) && ($adate == "")){
				show_form();
			}
			else{
				print "Error: You need a valid code to complete this response or this code has been used already.<br>
				If you believe this is an error, please contact fmri-support@ucsd.edu.
				";
			}
		}
		
		else{
			print "Error: You need a valid code to complete this response.<br>
			If you believe this is an error, please contact fmri-support@ucsd.edu.";
		}
}


function check_form(){
	global $HTTP_POST_VARS, $error, $print_again;
	$datestamp = date("Y-m-d");
	if($_POST["status"]==""){
		$error['status'] = true;
		$print_again = true;
		$message.="ERROR: The response field is empty<br>";
	}
	if($print_again){
		echo "<h2>Response was NOT submitted</h2>\n";
		echo "<h2>Please fill in all fields correctly</h2>\n"; 
		echo "$message";
		show_form();
	} else {
		echo "<h2></h2>\n";
		submitform();
		echo "$message";
	}
  
}

function submitform()
{
	global $HTTP_POST_VARS, $print_again, $error, $a, $uid;
	$link=ConnectMysql();
	$ip = mysql_real_escape_string($_POST['ip']);
	$httpref = mysql_real_escape_string($_POST['httpref']);
	$httpagent = mysql_real_escape_string($_POST['httpagent']);
	$today = date("Y-m-d"); 
	$a = mysql_real_escape_string($_POST["code"]);
	$uid = mysql_real_escape_string($_POST["uid"]);
	$status = mysql_real_escape_string($_POST["status"]);
	$CommentsText = mysql_real_escape_string($_POST["CommentsText"]);

	$query = "SELECT lname,fname,email,phone,organization,department,job,reason,pi_name,Registration.piemail,safety_date,operator_date,mockscan,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,websch.3te,websch.3tw,websch.7ta from Registration,accesslist,websch WHERE Registration.user_id=accesslist.user_id and Registration.user_id=websch.user_id and hcode='$a'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);

	$lname=$row[0];
	$fname=$row[1];
	$email=$row[2];
	$phone=$row[3];
	$org=$row[4];
	$dep=$row[5];
	$job=$row[6];
	$reason=$row[7];
	$piname=$row[8];
	$piemail=$row[9];
	$safetydate=$row[10];
	$operator=$row[11];
	$mockscan=$row[12];
	if($row[13]){$accesslevel .= "[Building After Hours] ";}
	if($row[14]){$accesslevel .= "[3T] ";}
	if($row[15]){$accesslevel .= "[7T] ";}
	if($row[16]){$accesslevel .= "[MockScanner] ";}
	if($row[17]){$webschedule .= "[3TW] ";}
	if($row[18]){$webschedule .= "[3TE] ";}
	if($row[19]){$webschedule .= "[7T] ";}

$opstatus = "Safety $safetydate";
# check if user is an operator
if ($operator ){$opstatus .= ", Operator $operator";}
# check if user is an mock scanner operator
if ($mockscan ){$opstatus .= ", Mock Scanner $mockscan ";}	
	
	if (preg_match("/Denied/i", $status)) {
		$subject="CFMRI Access Denied for $fname $lname";
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";
		$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
		$to = $email;
		$headers .= "Bcc: CFMRI Webmaster <fmri-support@ucsd.edu>;\n";
		$message="
		<html>
		<body>
		<h1>CFMRI Access Denied by Your PI</h1>
		<p>
		Last Name: $lname<br>
		First Name: $fname<br>
		Email: $email<br>
		</p>
		<p>
		If you believe this is an error please contact your PI and fmri-support@ucsd.edu.
		</p>
		CFMRI
		</body>
		</html>";

		mail($to,$subject,$message,$headers);
		// Performing SQL query
		$query = "DELETE FROM accesslist WHERE user_id='$uid'";
		$results = QueryMysql($link,$query);
		$query = "DELETE FROM receipts WHERE user_id='$uid'";
		$results = QueryMysql($link,$query);
		$query = "DELETE FROM websch WHERE user_id='$uid';";
		$results = QueryMysql($link,$query);
		}
	else {
		// Performing SQL query
		$query = "Update accesslist SET adate=CURRENT_TIMESTAMP, aip='$ip', picomments='$CommentsText', status='$status' where hcode='$a'";
		$results = QueryMysql($link,$query);
		
	
	}
	mysql_close($link);
	

	$subject="CFMRI Access Approval Pending for $fname $lname";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
	
	if (preg_match("/7T/i", $accesslevel)){
		$to = "rbussell@ucsd.edu";
	}else {
		$to = "kunlu@ucsd.edu";
	}
	$headers .= "Cc: Kun Lu <kunlu@ucsd.edu>, Mary <momalley@ucsd.edu>, Eman <eghobrial@ucsd.edu>, CFMRI Webmaster <fmri-support@ucsd.edu>;\n"; 
	$message="
	<html>
	<body>
	<h1>CFMRI Access Approval Pending</h1>
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
	Requested Access Level: $accesslevel<br>
	Requested webschedule: $webschedule<br>
	Reason requesting access: $reason <br>
	</p>
	<p>
	Approval Status: $status<br>
	Additional Comments:<br>
	$CommentsText
	</p>
	<p>Please go to the <a href='https://cfmriweb.ucsd.edu/cfmriaccess/admin/main.html'>CFMRI Access Portal</a></p>
	$todayis [PST] <br>
	Requesting IP = $ip <br>
	Browser Info: $httpagent <br>
	Referral : $httpref <br>
	</body>
	</html>
	";

	mail($to,$subject,$message,$headers);

	echo "<h2>Your submission was successful</h2>";


}

function ConnectMysql() {
	//connect to database, credential removed for secuirty
	$link = mysql_connect('cfmriweb.ucsd.edu', 'XXXXXXXX', 'XXXXXXXXXXXXX') or die('Could not connect: ' . mysql_error());
	mysql_select_db('cfmriTraining', $link) or die('Could not select database ' . mysql_error());
	return $link;
}

function QueryMysql($link,$query) {
	$result = mysql_query($query) or die("Query failed: " . mysql_error());
	return $result;
}

function CloseMysql($link) {
	mysql_close($link);
}

?>
</p>
</div>
</body>
</html>
