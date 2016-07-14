<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="UTF-8">
<title></title>

</head>

<body>

<?php
$TodaysDate = date("Y-m-d");
$username = $_SERVER['REMOTE_USER'];
include('functions.php');

if ($_GET['action'] == "AddPayment") { AddPayment($_POST['UID'], $_POST['payment'], $_POST['amount'], $_POST['comments'], $_POST['ip'], $_POST['receivedby']); }
if ($_GET['action'] == "AddAccess") { AddAccess($_POST['UID'], $_POST['cardnum'],  $_POST['comments'], $_POST['grantedby']); }
if ($_GET['action'] == "AddWebschedule") { AddWebschedule($_POST['UID'], $_POST['comments'], $_POST['grantedby']); }
if ($_GET['action'] == "DepositReturned") { DepositReturned($_POST['UID'], $_POST['amount'], $_POST['ip'], $_POST['refundedby']); }
if ($_GET['action'] == "PendingPayment") { PendingPayment(); }
if ($_GET['action'] == "PendingApproval") { PendingApproval(); }
if ($_GET['action'] == "AddApproval") { AddApproval($_POST['UID'], $_POST['comments'], $_POST['Approved']); }
if ($_GET['action'] == "PendingAccess") { PendingAccess(); }
if ($_GET['action'] == "PendingWebsch") { PendingWebsch(); }
if ($_GET['action'] == "Users") { Users(); }
if ($_GET['action'] == "LapsedSafety") { LapsedSafety(); }
if ($_GET['action'] == "PaymentReport") { PaymentReport(); }
if ($_GET['action'] == "PendingPIList") { PendingPIList(); }
if ($_GET['action'] == "StudentProfile") { StudentProfile($_POST['UID']); } 
if ($_GET['action'] == "EditStudent") { EditStudent($_POST['StudID'], $_POST['lname'], $_POST['fname'], $_POST['email'], $_POST['phone'], $_POST['organization'], $_POST['department'], $_POST['job'], $_POST['pi_name'], $_POST['piemail'], $_POST['poperator'], $_POST['review'], $_POST['safety_date'], $_POST['operator_date'], $_POST['mock_date'], $_POST['comment']); }


echo "User: " .$username;
?> 

</body>

</html>
