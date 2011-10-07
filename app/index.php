<?php

/////////////////////
// Simple Mailform //
/////////////////////

$errors = Array();

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
			<p><input name="email"></p>
			<p><input type="submit" value="send"></p>
		</form>
	</body>
</html>
