<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Kick out non-logged-in users
if (!loggedin()) {
	header("location: /");
	exit;
}

// Define variables and initialize with empty values
$afm = $hashed_password = $previous_password = $password = $confirm_password = "";
$err = "";

$afm = $_SESSION["afm"];

if ($_SERVER["REQUEST_METHOD"] != "POST") { // Save referrer on GET, to redirect on success
	if (isset($_GET['page']))
		$_SESSION['referrer'] = $_GET['page'];
	else
		$_SESSION['referrer'] = "/";
} else { // Processing form data when form is submitted
	// Include MySQL config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

	// Validate old password
	$previous_password = $_POST["previous-password"];
	if (empty($previous_password)) {
		$err = "Παρακαλώ εισάγετε τον προηγούμενο κωδικό πρόσβασής σας.";
	}

	// Validate new password
	$password = $_POST["password"];
	if (empty($password)) {
		$err = "Παρακαλώ εισάγετε τον νέο κωδικό πρόσβασής σας.";
	} elseif (strlen($password) < 8) {
		$err = "Ο κωδικός πρόσβασης πρέπει να έχει τουλάχιστον 8 χαρακτήρες.";
	}

	// Validate confirm password
	$confirm_password = $_POST["confirm-password"];
	if (empty($confirm_password)) {
		$err = "Παρακαλώ επαληθεύστε τον νέο κωδικό πρόσβασής σας.";
	} elseif (empty($err) & ($password != $confirm_password)) {
		$err = "Οι κωδικοί πρόσβασης δεν ταιριάζουν.";
	}

	// Validate credentials
	if (empty($err)) {
		// Assume wrong information entered
		$err = "Λανθασμένος κωδικός πρόσβασης.";

		$sql = "SELECT password FROM users WHERE afm = ?";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "s", $afm);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_store_result($stmt);
		// Check if user exists, if yes then verify password
		if (mysqli_stmt_num_rows($stmt) == 1) {
			mysqli_stmt_bind_result($stmt, $hashed_password);

			if (mysqli_stmt_fetch($stmt)) {
				if (password_verify($previous_password, $hashed_password)) {
					$err = "";

					// Update password
					$sql = "UPDATE users SET password = ? WHERE afm = ?";

					$stmt = mysqli_prepare($link, $sql);
					mysqli_stmt_bind_param($stmt, "ss", $password, $afm);

					// Hash password before insert
					$password = password_hash($password, PASSWORD_DEFAULT);

					mysqli_stmt_execute($stmt);

					mysqli_stmt_close($stmt);

					// SUCCESS. Redirect user to referring page
					header("location: " . $_SESSION['referrer']);
					unset($_SESSION['referrer']);
				}
			}
		}

		mysqli_stmt_close($stmt);
	}

	// Close connection
	mysqli_close($link);
}

?>
<!DOCTYPE html>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Εγγραφή - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
	<link rel="shortcut icon" href="/favicon.ico"/>
	<!-- STYLES & JQUERY
	================================================== -->
	<link rel="stylesheet" type="text/css" href="/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="/css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="/css/slider.css"/>
	<link rel="stylesheet" type="text/css" href="/css/skinblue.css"/><!-- change skin color -->
	<link rel="stylesheet" type="text/css" href="/css/responsive.css"/>
</head>
<body>
	<section class="grid">
		<header class="royalcontent">
			<a href="/"><img id="login-logo" src="/images/logo.gif" class="logo" alt="Λογότυπο Υπουργείου"></a><br>
			<h1 class="title stresstitle">Αλλαγή Κωδικού</h1>
		</header>
		<form id="register-form" class="c8" method="post" action="<?php echo samepage(); ?>">
			<?php
				if (!empty($err)) {
					echo '<p class="alert error clear">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $err;
					echo '</p>';
				}
			?>
			<label for="previous-password" class="required">Προηγούμενος κωδικός πρόσβασης:</label>
			<input type="password" name="previous-password" id="previous-password" required>

			<label for="password" class="required">Νέος κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" autocomplete="new-password" minlength="8" maxlength="16" required>

			<label for="confirm-password" class="required">Επανάληψη νέου κωδικού πρόσβασης:</label>
			<input type="password" name="confirm-password" id="confirm-password" autocomplete="new-password" minlength="8" maxlength="16" required>

			<div class="buttons space-top">
				<input type="submit" class="actionbutton" value="Αλλαγή Κωδικού">
			</div>
		</form>
	</section>
</body>
