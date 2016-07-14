<?php
function PendingPIList() {
	$TDate = date("l dS \of F Y");
	$link = ConnectMysql();
	echo "
	<center><font size=4>
	<b>Pending PI Approval List<br>
	</font>";
		$query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,pi_name,Registration.piemail from Registration,accesslist WHERE Registration.user_id=accesslist.user_id AND Deleted IS NULL AND adate is NULL ORDER by lname asc";
			$results = QueryMysql($link,$query);

		echo "<table border=1 cellspacing=0 width=1200px>
		  <thead>
	<tr BGCOLOR=#659EC7><th height=40px>ID</th><th>Last Name</th><th>First Name</th><th>eMail</th>
	<th>Phone</th><th>Organization</th><th>Department </th><th>PI Name</th><th>PI Email</th>
	</tr>
	  </thead>
	  <tbody>
	";

	

    while ($row = @mysql_fetch_row($results)){
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td height=40px width=40px>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td>
		<td>$row[8]
		</td></tr>
		");
    }

		
	echo ("</tbody></table> <center>");
	
	CloseMysql($link);
}

function PendingApproval() {
    global $TodaysDate;
	$link = ConnectMysql();
	$username = $_SERVER['REMOTE_USER'];
    $query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,safety_date,SafetyTestDate,operator_date,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,reason from Registration,accesslist,receipts WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=receipts.user_id AND Deleted IS NULL AND adate is not NULL AND cfmriaprroval is NULL ORDER by lname asc";
		$results = QueryMysql($link,$query);
	echo "
	<center><font size=4>
	<strong>Pending CFMRI Approval List</strong><br>
	</font>
	This will send an email with instructions to the user and HS Security Team.
	";
	echo "<table border=1 cellspacing=0>
<tr BGCOLOR=#659EC7><td>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
<td>Phone</td><td>Organization</td><td>Department</td><td>Safety</td><td>Test</td><td>Operator</td><td>AfterHour</td><td>3T</td><td>7T</td><td>Mock</td><td>Reason</td>
<td>CFMRI Approval</td></tr>
";

    while ($row = @mysql_fetch_row($results))
        {
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td><td>$row[14]</td>
		<td>
		<form name=save$i action=display.php?action=AddApproval method=POST>
		<input type=hidden name=UID value=$row[0]>
		Comments:<br>
		<textarea name=comments cols=30 rows=4>$username</textarea>
		<br>
		<input type=submit value=Approved name=Approved >
		<input type=submit value=Delete name=Delete >
		</form></td>
		
		</tr>");
        }
				
	echo ("</table>");
	echo ("Users left: $i <br>");
	CloseMysql($link);
}

function PendingPayment(){
    global $TodaysDate;
	$link = ConnectMysql();
	$username = $_SERVER['REMOTE_USER'];
    $query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department from Registration,accesslist,receipts WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=receipts.user_id AND Deleted IS NULL AND adate is not NULL AND cfmriaprroval is not NULL and paydate is NULL ORDER by lname asc";
		$results = QueryMysql($link,$query);
	echo "
	<center><font size=4>
	<b>Pending Payment List<br>
	</font>";
	echo "<table border=1 cellspacing=0>
<tr BGCOLOR=#659EC7><td>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
<td>Phone</td><td>Organization</td><td>Department</td>
<td>Payment</td></tr>
";
$ipi = getenv("REMOTE_ADDR");

    while ($row = @mysql_fetch_row($results))
        {
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td>
		<td>
		<form name=save$i action=display.php?action=AddPayment method=POST>
		<input type=hidden name=UID value=$row[0]>
		<input type=hidden name=ip value='$ipi' />
		Check Number: <input type=text name=payment value=''><br>
		Amount $: <input type=text name=amount value='35'><br>
		Comments: <input type=text name=comments value=''><br>
		Received By: <input type=text name=receivedby value='".$username."'><br>
		<input type=submit value=Paid>
		</form></td></tr>");
        }
				
	echo ("</table>");
	echo ("Users left: $i <br>");
	CloseMysql($link);
}

function PendingAccess() {
    global $TodaysDate;
	$link = ConnectMysql();
	$username = $_SERVER['REMOTE_USER'];
    $query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,safety_date,SafetyTestDate,operator_date,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,picomments,receipts.comments,cfmricomments from Registration,accesslist,receipts WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=receipts.user_id AND Deleted IS NULL AND adate is not NULL and paydate is not NULL AND cfmriaprroval is not NULL and granted is NULL ORDER by lname asc";
		$results = QueryMysql($link,$query);
	echo "
	<center><font size=4>
	<b>Pending Access List<br>
	</font>";
	echo "<table border=1 cellspacing=0>
<tr BGCOLOR=#659EC7><td>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
<td>Phone</td><td>Organization</td><td>Department</td><td>Safety</td><td>Test</td><td>Operator</td><td>AfterHour</td><td>3T</td><td>7T</td><td>Mock</td><td>Comments</td>
<td>Access Granted</td></tr>
";

    while ($row = @mysql_fetch_row($results))
        {
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		
		if ($row[10]){$afterhour="AfterHour";}else{$afterhour="";}
		if ($row[11]){$threet="3T";}else{$threet="";}
		if ($row[12]){$sevent="7T";}else{$sevent="";}
		if ($row[13]){$mock="Mock";}else{$mock="";}
		
		
		
		echo ("
		<tr BGCOLOR=$trcolor><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$afterhour</td><td>$threet</td><td>$sevent</td><td>$mock</td>
		<td><p>PI: $row[14]</p><p>Recipts: $row[15]</p><p>CFMRI: $row[16]</p></td>
		<td>
		<form name=save$i action=display.php?action=AddAccess method=POST>
		<input type=hidden name=UID value=$row[0]>
		Card Number: <input type=text name=cardnum value=''><br>
		Comments: <input type=text name=comments value=''><br>
		Granted By: <input type=text name=grantedby value='".$username."'><br>
		<input type=submit value=Granted>
		</form></td></tr>");
        }
				
	echo ("</table>");
	echo ("Users left: $i <br>");
	CloseMysql($link);
}

function PendingWebsch() {
    global $TodaysDate;
	$link = ConnectMysql();
	$username = $_SERVER['REMOTE_USER'];
    $query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,pi_name,safety_date,SafetyTestDate,operator_date,websch.3tw,websch.3te,websch.7ta from Registration,accesslist,websch WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=websch.user_id AND Deleted IS NULL AND adate is not NULL and websch.granted is NULL AND (websch.3tw is not null or websch.3te is not null or websch.7ta is not null) AND cfmriaprroval is not NULL ORDER by lname asc";
		$results = QueryMysql($link,$query);
	echo "
	<center><font size=4>
	<b>Pending Webschdule Access List<br>
	</font>";
	echo "<table border=1 cellspacing=0>
<tr BGCOLOR=#659EC7><td>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
<td>Phone</td><td>Organization</td><td>Department</td><td>PI</td><td>Test</td><td>Operator</td><td>3TW</td><td>3TE</td><td>7TA</td>
<td>Access Granted</td></tr>
";

    while ($row = @mysql_fetch_row($results))
        {
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td>
		<td>
		<form name=save$i action=display.php?action=AddWebschedule method=POST>
		<input type=hidden name=UID value=$row[0]>
		Comments: <input type=text name=comments value=''><br>
		Granted By: <input type=text name=grantedby value='".$username."'><br>
		<input type=submit value=Granted>
		</form></td></tr>");
        }
				
	echo ("</table>");
	echo ("Users left: $i <br>");
	CloseMysql($link);
}

function Users() {
    global $TodaysDate;
	$link = ConnectMysql();
    $query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,pi_name,Registration.piemail,safety_date,SafetyTestDate,operator_date,receipts.paydate,receipts.payment,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,accesslist.granted,websch.3tw,websch.3te,websch.7ta,websch.granted,accesslist.returned from Registration,accesslist,receipts,websch WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=receipts.user_id AND Registration.user_id=websch.user_id AND Deleted IS NULL ORDER by lname asc";
		$results = QueryMysql($link,$query);

	echo "<table border=1 cellspacing=0>
<tr BGCOLOR=#659EC7><th>ID</th><th>Last Name</th><th>First Name</th><th>eMail</th>
<th>Phone</th><th>Organization</th><th>Department </th><th>PI Name</th><th>PI Email</th><th>Safety Date</th><th>Test</th><th>Operator Date</th><th>Paid</th><th>Payment</th><th>AfterHour</th><th>3T</th><th>7T</th><th>Mock</th><th>Access Granted</th><th>3tw</th><th>3te</th><th>7ta</th><th>Webschedule Granted</th><th>Edit Profile</th><th>Deposit Returned</th></tr>
";

$ipi = getenv("REMOTE_ADDR");
    while ($row = @mysql_fetch_row($results))
        {
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		if (($i % 15 == 0) ) {
	echo "<tr BGCOLOR=#659EC7><td>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
<td>Phone</td><td>Organization</td><td>Department </td><td>PI Name</td><td>PI Email</td><td>Safety Date</td><td>Test</td><td>Operator Date</td><td>Paid</td><td>Payment</td><td>AfterHour</td><td>3T</td><td>7T</td><td>Mock</td><td>Access Granted</td><td>3tw</td><td>3te</td><td>7ta</td><td>Webschedule Granted</td><td>Edit Profile</td><td>Deposit Returned</td></tr>	";	
		}
		echo ("
		<tr BGCOLOR=$trcolor><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td><td>$row[14]</td><td>$row[15]</td><td>$row[16]</td><td>$row[17]</td><td>$row[18]</td><td>$row[19]</td><td>$row[20]</td><td>$row[21]</td><td>$row[22]</td>
		<td>
		<form name=save$i action=display.php?action=StudentProfile method=POST>
		<input type=hidden name=UID value=$row[0]>
		<input type=submit value='Edit'>
		</form>
		</td>
		<td>");
		if ($row[23]){
		echo ("$row[23]");
		}else{
		if ($row[13] != "SOM"){
		echo ("
		<form name=save$i action=display.php?action=DepositReturned method=POST>
		<input type=hidden name=UID value=$row[0]>
		<input type=hidden name=ip value='$ipi' />
		Refund Amount $: <input type=text name=amount value='35'><br>
		Refunded By: <input type=text name=refundedby value='Mary'><br>
		<input type=submit value='Deposit Returned'>
		</form>");}
		
		}
		
		echo ("
		</td>
		</tr>
		
		");
        }
				
	echo ("</table>");
	CloseMysql($link);
}

function AddApproval($UID, $comments, $approval) {
	$link = ConnectMysql();
	$UID = mysql_real_escape_string($UID);
	$comments = mysql_real_escape_string($comments);
	$approval = mysql_real_escape_string($approval);

	$query = "SELECT lname,fname,email,phone,organization,department,job,reason,pi_name,Registration.piemail,safety_date,operator_date,mockscan,accesslist.afterhour,accesslist.3t,accesslist.7t,accesslist.mock,websch.3te,websch.3tw,websch.7ta from Registration,accesslist,websch WHERE Registration.user_id=accesslist.user_id and Registration.user_id=websch.user_id and Registration.user_id='$UID'";
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


	if($approval){
		$query = "UPDATE accesslist SET cfmriaprroval=CURRENT_TIMESTAMP, cfmricomments='$comments' WHERE user_id='$UID';";
		$results = QueryMysql($link,$query);

		#sent email to user with instructions
		$subject="CFMRI Access Granted for $fname $lname";
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";
		$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
		$to = $email;
		$headers .= "Bcc: FmriSupport <fmri-support@ucsd.edu>, Kun Lu <kunlu@ucsd.edu>;\n";
		if (preg_match("/ucsd/i", $org)) {
			$headers .= "Cc: HS Security <hssecurity@ucsd.edu>;\n";
			$message="
			<html>
			<body>
			<h1>CFMRI Access Granted</h1>
			<p>
			Last Name: $lname<br>
			First Name: $fname<br>
			Email: $email<br>
			</p>
			<p>
			Requested Access Level: $accesslevel<br>
			Requested webschedule: $webschedule
			</p>
			<p>
			CFMRI Comments: $comments
			</p>
			Instructions for getting access:
			<ol>
			<li>Take your <strong>current UCSD ID card and a copy of this email</strong> and go to the SOM Security Office (Room B315 in the Basement of the Biomedical Science Building) on UCSD campus, Open Monday and Friday from 8am-12pm except holidays. Call the SOM Security office (858) 822-1703 for directions if needed.</li>
			<li>Indicate that you were sent by the Keck Building (Center for Functional MRI) to apply for a proximity card.</li>
			<li>Once your UCSD employment information is verified, your picture will be taken and the new card will typically be ready in a few minutes.</li>
			<li>Remove the metal clip. CFMRI will provide you with an MRI safe lanyard for use with the card when it is activated.</li>
			<li>Schedule an appointment with Mary O'Malley (momalley@ucsd.edu) to have your card activated.</li>
			</ol>
			<p>
			If you need to be added to another PI's projects on the webschedule, please email eghobrial@ucsd.edu.
			</p>
				
			CFMRI<br>
			cfmri@ucsd.edu<br>
			Mary O'Malley: 858-822-0513
			</body>
			</html>";
		} else {
			$message="
			<html>
			<body>
			<h1>CFMRI Access Instructions</h1>
			<p>
			Last Name: $lname<br>
			First Name: $fname<br>
			Email: $email<br>
			</p>
			<p>
			Requested Access Level: $accesslevel<br>
			Requested webschedule: $webschedule
			</p>
			<p>
			CFMRI Comments: $comments
			</p>
			<ol>			
			<li>Schedule an appointment with Mary O'Malley momalley@ucsd.edu to pay the $35 deposit. CFMRI can only accept checks (payable to UC Regents). Please bring your institutional identification card and $35 deposit receipt to the appointment.  It is suggested that you schedule both appointments at the same time, to avoid having to make two visits.</li>
			<li>Upon verification of your information, Mary will issue a Guest card to you and also give you an MRI-safe lanyard for use at CFMRI.</li>
			</ol>			
			<p>
			If you need to be added to other PIs projects under the webschedule, please email eghobrial@ucsd.edu.
			</p>
			CFMRI
			</body>
			</html>";
		}	
		mail($to,$subject,$message,$headers);
		
		if($webschedule){
			$subject="Webschedule Access for $fname $lname";
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";
			$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
			$to = "eghobrial@ucsd.edu";
			$headers .= "Bcc: FmriSupport <fmri-support@ucsd.edu>;\n";
			$message="
			<html>
			<body>
			<h2>Please add Webschedule access for:</h2>
			<p>
			Last Name: $lname<br>
			First Name: $fname<br>
			Email: $email<br>
			</p>
			<p>
			PI: $piname<br>
			Requested webschedule: $webschedule
			</p>
			<p>
			CFMRI Comments: $comments
			</p>			
			CFMRI
			</body>
			</html>";
			mail($to,$subject,$message,$headers);
		}
		
		
		
		echo "Added Approval for $UID<br>";		
		
		
	}
	else{
		$query = "DELETE FROM accesslist WHERE user_id='$UID'";
		$results = QueryMysql($link,$query);
		$query = "DELETE FROM receipts WHERE user_id='$UID'";
		$results = QueryMysql($link,$query);
		$query = "DELETE FROM websch WHERE user_id='$UID';";
		$results = QueryMysql($link,$query);	
		
		$subject="CFMRI Access Denied for $fname $lname";
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";
		$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
		$to = $email;
		$headers .= "Bcc: FmriSupport <fmri-support@ucsd.edu>;\n";
		$message="
		<html>
		<body>
		<h1>CFMRI Access Denied by CFMRI</h1>
		<p>
		Last Name: $lname<br>
		First Name: $fname<br>
		Email: $email<br>
		</p>
		<p>
		CFMRI Comments: $comments
		</p>
		<p>
		If you believe this is an error please contact cfmri@ucsd.edu.
		</p>
		CFMRI
		</body>
		</html>";
	
		mail($to,$subject,$message,$headers);		
		
		echo "Deleted ID $UID<br>";	
	}
	
	
	

	CloseMysql($link);
	PendingApproval();
}

function AddPayment($UID, $payment, $amount, $comments, $ip, $receivedby) {
	$link = ConnectMysql();
	$UID = mysql_real_escape_string($UID);
	$payment = mysql_real_escape_string($payment);
	$amount = mysql_real_escape_string($amount);
	$comments = mysql_real_escape_string($comments);
	$ip = mysql_real_escape_string($ip);
	$receivedby = mysql_real_escape_string($receivedby);
	$query = "UPDATE receipts SET paydate=CURRENT_TIMESTAMP, payment='$payment', amount='$amount', comments='$comments', ip='$ip', receivedby='$receivedby' WHERE user_id='$UID';";
	$results = QueryMysql($link,$query);


	$query = "SELECT Registration.user_id,lname,fname,email,organization,receipts.paydate,receipts.payment,receipts.amount,receipts.receivedby,receipts.comments,Registration.pi_name,Registration.piemail from Registration,accesslist,receipts WHERE Registration.user_id=accesslist.user_id AND Registration.user_id=receipts.user_id AND Registration.user_id='$UID';";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);		
	echo "<h2>CFMRI Guest Card Deposit Receipt</h2>";	
	echo "Date: $row[5]<br>";
	echo "Guest Card Name: $row[2] $row[1]<br>";
	echo "Email: $row[3]<br>";
	echo "Org: $row[4]<br>";
	echo "PI Name: $row[10]<br>";
	echo "PI Email: $row[11]<br><br>";
	echo "User ID: $row[0]<br>";
	echo "Deposit Amount: \$ $row[7]<br>";
	echo "Check Number: $row[6]<br>";
	echo "Received By: $row[8]<br>";
	echo "Comments: $row[9]<br>";
	echo "<p>This deposit will be refunded upon return of the undamaged card.</p>";

	$subject="CFMRI Guest Card Deposit Receipt for $row[2] $row[1]";
	$headers = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8 ' . "\r\n";
	$headers .= "From: CFMRI <cfmri@ucsd.edu>;\n";
	$to = $email;
	$headers .= "Bcc: FmriSupport <fmri-support@ucsd.edu>, Mary <momalley@ucsd.edu>;\n";
	$message="
	<html>
	<body>
	<h2>CFMRI Guest Card Deposit Receipt</h2>
	<p>
	Date: $row[5]<br>
	Guest Card Name: $row[2] $row[1]<br>
	Email: $row[3]<br>
	Org: $row[4]<br>
	PI Name: $row[10]<br>
	PI Email: $row[11]</p>
	<p>
	User ID: $row[0]<br>
	Deposit Amount: \$ $row[7]<br>
	Check Number: $row[6]<br>
	Received By: $row[8]<br>
	Comments: $row[9]
	</p>
	<p>This deposit will be refunded upon return of the undamaged card.</p>
	CFMRI
	</body>
	</html>";
	
	if (mail($to,$subject,$message,$headers)) {
		echo("<p>Receipt successfully sent!</p>");
	}
	else {
		echo("<p>Receipt delivery failed...</p>");
	}
	CloseMysql($link);
}

function DepositReturned($UID, $amount, $ip, $refundedby) {
	$link = ConnectMysql();
	$UID = mysql_real_escape_string($UID);
	$query = "UPDATE receipts SET returned=CURRENT_TIMESTAMP, returnedip='$ip', returnedamount='$amount', refundedby='$refundedby' WHERE user_id='$UID';";
	$results = QueryMysql($link,$query);
	echo "Deposit Returned for $UID -<br>";
	CloseMysql($link);
	PendingAccess();
}
function AddAccess($UID, $cardnum, $comments, $grantedby) {
	$link = ConnectMysql();
	$UID = mysql_real_escape_string($UID);
	$cardnum = mysql_real_escape_string($cardnum);
	$comments = mysql_real_escape_string($comments);
	$grantedby = mysql_real_escape_string($grantedby);
	$query = "UPDATE accesslist SET granted=CURRENT_TIMESTAMP, cardnum='$cardnum', comments='$comments', grantedby='$grantedby' WHERE user_id='$UID';";
	$results = QueryMysql($link,$query);
	echo "Added - $UID -<br>";
	CloseMysql($link);
	PendingAccess();
}

function AddWebschedule($UID, $comments, $grantedby) {
	$link = ConnectMysql();
	$UID = mysql_real_escape_string($UID);
	$comments = mysql_real_escape_string($comments);
	$grantedby = mysql_real_escape_string($grantedby);
	$query = "UPDATE websch SET granted=CURRENT_TIMESTAMP, comments='$comments', grantedby='$grantedby' WHERE user_id='$UID';";
	$results = QueryMysql($link,$query);
	echo "Added - $UID -<br>";
	CloseMysql($link);
	PendingWebsch();
}


function LapsedSafety() {
	$TDate = date("l dS \of F Y");
	$olddate= date('Y-m-d', strtotime('-365 days'));
	$link = ConnectMysql();
	echo "
	<center><font size=4>
	<b>Users that have Lapsed Safety Test<br>
	</font>";
		$query = "SELECT Registration.user_id,lname,fname,email,phone,organization,department,pi_name,Registration.piemail,SafetyTestDate,granted from Registration,accesslist WHERE Registration.user_id=accesslist.user_id AND Deleted IS NULL AND SafetyTestDate < '$olddate' ORDER by lname asc";
			$results = QueryMysql($link,$query);

		echo "<table border=1 cellspacing=0 width=1200px>
	<tr><td height=40px>ID</td><td>Last Name</td><td>First Name</td><td>eMail</td>
	<td>Phone</td><td>Organization</td><td>Department </td><td>PI Name</td><td>PI Email</td><td>Safety test date</td><td>Access granted date</td>
	</tr>
	";

	

    while ($row = @mysql_fetch_row($results)){
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td height=40px width=40px>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td>
		</tr>
		");
    }

		
	echo ("</table> <center>");
	
	CloseMysql($link);
}


function PaymentReport() {

	$link = ConnectMysql();
	echo "
	<center><font size=4>
	<b>Payment Report<br>
	</font>";
		$query = "SELECT Registration.user_id,lname,fname,email,organization,paydate,payment,amount,comments,receivedby,receipts.returned,receipts.returnedamount,receipts.refundedby from Registration,receipts WHERE Registration.user_id=receipts.user_id AND `payment` NOT LIKE 'SOM' ORDER by lname asc";
			$results = QueryMysql($link,$query);

		echo '<table border=1 cellspacing=0 width=1200px>
	<tr><td height=40px>ID</td><td>Last Name</td><td>First Name</td><td>Email</td>
	<td>Organization</td><td>Payment Date</td><td>Check Number</td><td>Amount $</td><td>Comments</td><td>Received By</td><td>Refund Date</td><td>Refund $</td><td>Refunded By</td>
	</tr>
	';

	

    while ($row = @mysql_fetch_row($results)){
		$i++;
		if ($i % 2 == 0) {$trcolor="#FFFFFF";} else {$trcolor="#C0C0C0";}
		echo ("
		<tr BGCOLOR=$trcolor><td height=40px width=40px>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td>
		<td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td>
		</tr>
		");
    }

		
	echo ("</table> <center>");
	
	CloseMysql($link);
}

function StudentProfile($StudID) {
	$link = ConnectMysql();
	$StudID = mysql_real_escape_string($StudID);
	$query = "SELECT `user_id`,`lname`,`fname`,`email`,`phone`,`organization`,`department`,`pi_name`,`poperator`,`review`,`safety_date`,`operator_date`,`mockscan`,`comment`,`job`,`piemail` from `Registration` WHERE `user_id`= '$StudID'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);
	?>
	Edit Profile for <?php echo $row[3];?><br>
	<form name=Profile action=display.php?action=EditStudent method=POST>
	<input type=hidden name=StudID value="<?php echo $row[0];?>">
	Last Name: <input size=35 type=text name=lname value="<?php echo $row[1];?>"><br>
	First Name: <input size=35 type=text name=fname value="<?php echo $row[2];?>"><br>
	Email: <input size=35 type=text name=email value="<?php echo $row[3];?>"><br>
	Phone: <input size=35 type=text name=phone value="<?php echo $row[4];?>"><br>
	Organization: <input size=35 type=text name=organization value="<?php echo $row[5];?>"><br>
	Department: <input size=35 type=text name=department value="<?php echo $row[6];?>"><br>
	Job Title: <input size=35 type=text name=job value="<?php echo $row[14];?>"><br>
	PI Name: <input size=35 type=text name=pi_name value="<?php echo $row[7];?>"><br>
	PI Email: <input size=35 type=text name=piemail value="<?php echo $row[15];?>"><br>
	Pending operator training: <input size=35 type=text name=poperator value="<?php echo $row[8];?>"><br>
	Safety Review: <input size=35 type=text name=review value="<?php echo $row[9];?>"><br>
	Safety Date: <input size=35 type=text name=safety_date value="<?php echo $row[10];?>"><br>
	Operator Date: <input size=35 type=text name=operator_date value="<?php echo $row[11];?>"><br>
	Mock Scanner Date: <input size=35 type=text name=mock_date value="<?php echo $row[12];?>"><br>
	Comment: <input size=35 type=text name=comment value="<?php echo $row[13];?>"><br>
	
	<input type=submit value=Update>
	</form>
	<?php
	CloseMysql($link);
}
function EditStudent($StudID, $lname, $fname, $email, $phone, $organization, $department, $job, $pi_name, $piemail, $poperator, $review, $safety_date, $operator_date, $mock_date, $comment) {

$link = ConnectMysql();	
$StudID = mysql_real_escape_string($StudID);
$lname = mysql_real_escape_string($lname);
$fname = mysql_real_escape_string($fname);
$email = mysql_real_escape_string($email);
$phone = mysql_real_escape_string($phone);
$organization = mysql_real_escape_string($organization);
$department = mysql_real_escape_string($department);
$job = mysql_real_escape_string($job);
$pi_name = mysql_real_escape_string($pi_name);
$piemail = mysql_real_escape_string($piemail);
$poperator = mysql_real_escape_string($poperator);
$review = mysql_real_escape_string($review);
$safety_date = mysql_real_escape_string($safety_date);
$operator_date = mysql_real_escape_string($operator_date);
$mock_date = mysql_real_escape_string($mock_date);
$comment = mysql_real_escape_string($comment);

if ($operator_date == "") $operator_date="NULL"; else $operator_date="'$operator_date'";
if ($safety_date == "") $safety_date="NULL"; else $safety_date="'$safety_date'";
if ($mock_date == "") $mock_date="NULL"; else $mock_date="'$mock_date'";
if ($comment == "") $comment="NULL"; else $comment="'$comment'";

	$query = "SELECT `operator_date` from `Registration` WHERE `user_id`= '$StudID'";
	$results = QueryMysql($link,$query);
	$row = @mysql_fetch_row($results);

	$query = "UPDATE `Registration` SET
`lname`= '$lname',
`fname`= '$fname',
`email`= '$email',
`department`='$department',
`job`='$job',
`phone`='$phone',
`pi_name`='$pi_name',
`piemail`='$piemail',
`poperator`='$poperator',
`organization`='$organization',
`review`= '$review',
`safety_date`=$safety_date,
`operator_date`=$operator_date,
`mockscan`=$mock_date,
`comment`=$comment
WHERE `user_id`='$StudID'";	
$results = QueryMysql($link,$query);
CloseMysql($link);
echo "- Updated ID $StudID -<br>";
Users();
	
}

function ConnectMysql() {
	//connect to database, credential removed for secuirty
	$link = mysql_connect('cfmriweb.ucsd.edu', 'XXXXXXX', 'XXXXXXXXX') or die('Could not connect: ' . mysql_error());
	mysql_select_db('cfmriTraining', $link) or die('Could not select database ' . mysql_error());
	return $link;
}

function QueryMysql($link,$query) {
	$result = mysql_query($query) or die("Query failed: $query <br>" . mysql_error());
	return $result;
}

function CloseMysql($link) {
	mysql_close($link);
}

?>
