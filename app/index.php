<?php

/////////////////////
// Simple Mailform //
/////////////////////


// Configuration //


// REQUIRED
// The is the mail address that mail is sent to.
//
$settings["email"] = "info@example.com";


// REQUIRED
// This is the mail subject.
// 
$settings["subject"] = "Simple Mailform";


// OPTIONAL
// This is the 'From' mail address.
// If 'from' is not set, 'email' setting is set as 'From' address. 
// 
//$settings["from"] = "info@example.com";


// OPTIONAL
// This is a configuration of each form parameters.
//
//$settings["parameters"] = Array(
//	"email" => Array("required" => true, "formal_name" => "Mail Address")
//);


//REQUIRED
// Absolute URL to complete.html
// If you rename or move complete.html file, please rewrite completePageUrl setting
//
$settings["completePageUrl"] = "http://HOSTNAME/PATH_TO_SIMPLE_MAILFORM/complete.html";



// Mailform Script //


$errors = Array();

if ($_POST){

	// set default settings
	if (!isset($settings["from"])) {
		$settings["from"] = $settings["email"];
	}
	if (!isset($settings["parameters"])) {
		$settings["parameters"] = Array();
	}
	
	// mail variables
	$from = $settings["from"];
	$to = $settings["email"];
	$headers = "";
	$body = "";

	// construct mail headers
	$headers .= "From: <{$from}>";

	// construct mail body
	foreach($_POST as $name => $param){
		$body .= $name . ":\n";
		$body .= $param . "\n\n";
	}

	if (!$errors){
		$result = mb_send_mail($to, $subject, $body, $headers);
		if ($result){
			header("Location: ".$settings["completePageUrl"]);
			exit;
		} else {
			$error[] = "The mail was not sent.";
		}
	}
}



// Mailform HTML //


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Simple Mailform</title>
	</head>
	<body>
		<?php if($errors){ ?>
			<p>Error:</p>
			<?php foreach($errors as $e){ ?>
				<p><?php echo $e; ?></p>
			<?php } ?>
			<p></p>
		<?php } ?>

		<form action="./index.php" method="post">
			<p>email*: <input name="email" /></p>
			<p>content: <br /><textarea name="content"></textarea></p>
			<p><input type="submit" value="send" /></p>
		</form>
	</body>
</html>
