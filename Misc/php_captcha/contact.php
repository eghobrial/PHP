<?php
session_start();
$conn = mysql_connect("localhost","root","");
mysql_select_db("phppot_examples",$conn);
if(count($_POST)>0) {
if($_POST["captcha_code"]==$_SESSION["captcha_code"]){
$message = "Your message received successfully";
mysql_query("INSERT INTO tblcontact (user_name, user_email,subject,content) VALUES ('" . $_POST['userName']. "', '" . $_POST['userEmail']. "','" . $_POST['subject']. "','" . $_POST['content']. "')");
}
else{
$message = "Enter Correct Captcha Code";
}
}
?>
<html>
<head>
<title>Contact Us Form</title>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<form name="frmContact" method="post" action="">
<div class="message"><?php if(isset($message)) { echo $message; } ?></div>
<table border="0" cellpadding="10" cellspacing="1" width="500" align="center">
<tr class="tableheader">
<td colspan="2">Enter Contact Information</td>
</tr>
<tr class="tablerow">
<td>Name<br/><input type="text" name="userName"></td>
<td>Email<br/><input type="text" name="userEmail"></td>
</tr>
<tr class="tablerow">
<td colspan="2">Subject<br/><input type="text" name="subject" size="73"></td>
</tr>
<tr class="tablerow">
<td colspan="2">Content<br/><textarea name="content" cols="60" rows="6"></textarea></td>
</tr>
<tr class="tablerow">
<td colspan="2">Captcha Code<br/><input name="captcha_code" type="text"><br>
<img src="captcha_code.php" /></td>
</tr>
<tr class="tableheader">
<td align="center" colspan="2"><input type="submit" name="submit" value="Submit"></td>
</tr>
</table>
</form>
</body></html>