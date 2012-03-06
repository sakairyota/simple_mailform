<?php

/////////////////////
// Simple Mailform //
/////////////////////


// Comment out when you use UTF-8
//
// mb_internal_encoding('UTF-8');


// Configuration //

// email (REQUIRED)
// The is the mail address that mail is sent to.
//
$settings['email'] = 'info@example.com';


// subject (REQUIRED)
//     This is the mail subject.
// 
$settings['subject'] = 'Simple Mailform';


// from (optional)
//     This is the 'From' mail address.
//     If 'from' is not set, 'email' setting is set as 'From' address. 
// 
//$settings['from'] = 'info@example.com';


// parameters (optional)
//     This is a configuration of each form parameters.
//
//$settings['parameters'] = Array(
//	'email' => Array('required' => true, 'formalName' => 'Mail Address')
//);


// completeHtmlName (optional)
//     File name of complete page.
//     Default value is 'complete.html'
//
//$settings['completeHtmlName'] = 'complete.html';


// error_not_exist_required_parameter (optional)
//     Error message when required message does not contain texts;
//
$settings['error_not_exist_required_parameter'] = '%s is required.';

// reply_mail (optional)
//     enable reply mail for sender.
//
$settings['replyMail'] = false;

// reply_mail_param_name (optional)
//     defalut: 'email'
//
//$settings['replyMailParamName'] = 'email';

// reply_subject (optional)
//     default: $settings['subject']
//
//$settings['replySubject'] = 'Thank you!';

// reply_from (optional)
//     default: $settings['from']
//
//$settings['replyFrom'] = 'info@example.com';

// reply_from (required)
//     (optional if replyMail is false)
//     
//     replace [name] into name parameter
//
/*$settings['replyFormat'] = <<<END_FORMAT
thank you!

your email address is {email}.
END_FORMAT;*/

// Mailform Script //


$errors = Array();

if ($_POST){

	// check settings and set default settings
	if (!isset($settings['email'])) {
		$errors[] = "[Settings] 'email' setting is REQUIRED";
	}
	if (!isset($settings['subject'])) {
		$errors[] = "[Settings] 'subject' setting is REQUIRED";
	}
	if (!isset($settings['from'])) {
		$settings['from'] = $settings['email'];
	}
	if (!isset($settings['parameters'])) {
		$settings['parameters'] = Array();
	}
	if (!isset($settings['error_not_exist_required_parameter'])) {
		$settings['error_not_exist_required_parameter'] = '%s is required.';
	}
	if (!isset($settings['completeHtmlName'])) {
		$settings['completeHtmlName'] = 'complete.html';
	}
	if (!isset($settings['replyMailParamName'])) {
		$settings['replyMailParamName'] = 'email';
	}
	if (!isset($settings['replySubject'])) {
		$settings['replySubject'] = $settings['subject'];
	}
	if (!isset($settings['replyFrom'])) {
		$settings['replyFrom'] = $settings['from'];
	}
	if (!isset($settings['replyFormat']) && $settings['replyMail']) {
		$errors[] = "[Settings] 'replyFormat' setting is REQUIRED";
	}
	
	// form parameter variable
	$parameters = $settings['parameters'];
	foreach($_POST as $name => $param) {
		if (! isset($parameters[$name])) {
			$parameters[$name] = Array();
		}
		if (! isset($parameters[$name]['formalName'])) {
			$parameters[$name]['formalName'] = $name;
		}
	}

  // validate replyMailParamName parameter
	if ($settings['replyMail']) {
    if (! isset($parameters[$settings['replyMailParamName']])){
		  $errors[] = "[Settings] replyMailParamName does not exist in the parameter.";
    }
  }

	// validate required parameters
  foreach($parameters as $name => $parameter){
    if (isset($parameter['required']) && $parameter['required']){
      if (!isset($_POST[$name]) || $_POST[$name] === ''){
				$errors[] = sprintf($settings['error_not_exist_required_parameter'], $parameters[$name]['formalName']);
      }
    }
  }

	// mail variables
	$from = $settings['from'];
	$to = $settings['email'];
	$subject = $settings['subject'];
	$headers = '';
	$body = '';

	// construct mail headers
	$headers .= "From: <{$from}>";

	// construct mail body
	foreach($_POST as $name => $param){

		// print parameter name
		$body .= $parameters[$name]['formalName'] . ":\n";

		// print parameter content
		$body .= $param . "\n\n";
	}

	// send mail if not exist errors
	if (!$errors) {

		//send mail
		$result = mb_send_mail($to, $subject, $body, $headers);

		// redirect to complete page if succeed.
		if ($result) {

      //send reply mail
      if($settings['replyMail']) {
	      $replay_from = $settings['replyFrom'];
	      $reply_to = $_POST[$settings['replyMailParamName']];
	      $reply_subject = $settings['replySubject'];
	      $reply_headers = '';
	      $reply_body = $settings['replyFormat'];

	      $reply_headers .= "From: <{$from}>";

        // replace [name] to actual parameter
        foreach($_POST as $name => $param) {
          $reply_body = ereg_replace("\{{$name}\}", $param, $reply_body);
        }

		    $result = mb_send_mail($reply_to, $reply_subject, $reply_body, $reply_headers);
      }

			//construct URL of complete page.
			$completeHtmlUrl  = 'http://';
			$completeHtmlUrl .= $_SERVER['SERVER_NAME'];
			$completeHtmlUrl .= dirname($_SERVER['SCRIPT_NAME']);
			$completeHtmlUrl  = $settings['completeHtmlName'];

			//redirect
			header('Location: '.$completeHtmlUrl);
			exit;
		} else {
			$error[] = 'The mail was not sent.';
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
