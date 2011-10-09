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
$settings["completeHtmlName"] = "complete.html";


// OPTIONAL
// Error message when required message does not contain texts;
//
$settings["error_not_exist_required_parameter"] = "%s is required.";


// Mailform Script //


$errors = Array();

if ($_POST){

	// check settings and set default settings
	if (!isset($settings["email"])) {
		$errors[] = "[Settings] 'email' setting is REQUIRED";
	}
	if (!isset($settings["subject"])) {
		$errors[] = "[Settings] 'subject' setting is REQUIRED";
	}
	if (!isset($settings["from"])) {
		$settings["from"] = $settings["email"];
	}
	if (!isset($settings["parameters"])) {
		$settings["parameters"] = Array();
	}
	if (!isset($settings["error_not_exist_required_parameter"])) {
		$settings["error_not_exist_required_parameter"] = "%s is required.";
	}
	if (!isset($settings["completeHtmlName"])) {
		$settings["completeHtmlName"] = 'complete.html';
	}
	
	// mail variables
	$from = $settings["from"];
	$to = $settings["email"];
	$subject = $settings["subject"];
	$headers = "";
	$body = "";

	// construct mail headers
	$headers .= "From: <{$from}>";

	// construct mail body
	foreach($_POST as $name => $param){
		$body .= $name . ":\n";
		$body .= $param . "\n\n";
	}

	// send mail if not exist errors
	if (!$errors){

		//send mail
		$result = mb_send_mail($to, $subject, $body, $headers);

		// redirect to complete page if succeed.
		if ($result){

			//construct URL of complete page.
			$completeHtmlUrl  = "http://";
			$completeHtmlUrl .= $_SERVER["SERVER_NAME"];
			$completeHtmlUrl .= dirname($_SERVER["SCRIPT_NAME"]);
			$completeHtmlUrl  = $settings['completeHtmlName'];

			//redirect
			header("Location: ".$completeHtmlUrl);
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
