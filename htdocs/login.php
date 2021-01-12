<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Define variables and initialize with empty values
$afm = $name = $email = $password = "";
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
	if (isset($_GET['url'])) {
		$_SESSION['referrer'] = $_GET['url'];
	} else {
		$_SESSION['referrer'] = "/";
	}

	// Check if the user is already logged in, if yes then redirect to homepage
	if (loggedin()) {
		header("location: /");
		exit;
	}
} else { // Processing form data when form is submitted
	// Include config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

	// Check if email is empty
	$email = trim($_POST["email"]);
	if (empty($email)) {
		$email_err = "Παρακαλώ εισάγετε έγκυρη διεύθυνση email.";
	}

	// Check if password is empty
	$password = $_POST["password"];
	if (empty($password)) {
		$password_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασής σας.";
	}

	// Validate credentials
	if (empty($email_err) && empty($password_err)) {
		// Prepare a select statement
		$sql = "SELECT afm, name, password FROM users WHERE email = ?;";

		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $email);

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				// Assume wrong information entered
				$email_err = "Λανθασμένο email ή κωδικός πρόσβασης.";

				// Store result
				mysqli_stmt_store_result($stmt);

				// Check if user exists, if yes then verify password
				if (mysqli_stmt_num_rows($stmt) == 1) {
					// Bind result variables
					mysqli_stmt_bind_result($stmt, $afm, $name, $hashed_password);

					if (mysqli_stmt_fetch($stmt)) {
						if (password_verify($password, $hashed_password)) {
							// Password is correct, so start a new session
							session_start();

							// Store data in session variables
							$_SESSION["loggedin"] = true;
							$_SESSION["afm"] = $afm;
							$_SESSION["name"] = $name;

							// SUCCESS. Redirect user to referring page and clear error
							$email_err = "";
							header("location: " . $_SESSION['referrer']);
							unset($_SESSION['referrer']);
						}
					}
				}
			} else {
				$email_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
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
	<title>Σύνδεση - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
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
			<h1 class="title stresstitle">Σύνδεση Χρήστη</h1><br>
			<span>(ας υποθέσουμε ότι αυτό γίνεται μέσω taxisNET)</span>
		</header>
		<form id="login-form" class="form c8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<?php
				if (!empty($email_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $email_err;
					echo '</p>';
				}
			?>
			<label for="email" class="required">Email:</label>
			<input type="email" name="email" id="email" maxlength="64" required>

			<?php
				if (!empty($password_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $password_err;
					echo '</p>';
				}
			?>
			<label for="password" class="required">Κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" maxlength="16" required>
			<input type="submit" id="login" class="actionbutton" value="Σύνδεση">
		</form>
		<p class="royalcontent">
			Δεν έχετε λογαριασμό; <a href="register.php<?php if (isset($_SESSION['referrer']) && $_SESSION['referrer'] != "/") echo "?url=" . $_SESSION['referrer']; ?>">Δημιουργήστε έναν!</a>.
			Έτσι θα φτάνετε στους στόχους σας πιο εύκολα και γρήγορα, γλιτώνοντας χρόνο σε συμπλήρωση φορμών.
		</p>
	</section>
</body>
