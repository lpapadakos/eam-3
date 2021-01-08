<?php

// Define variables and initialize with empty values
$email = $password = $name = $surname = "";
$email_err = $password_err = $name_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Include config file
	require_once "config.php";

	// Validate email
	$email = trim($_POST["email"]);
	if (empty($email)) {
		$email_err = "Παρακαλώ εισάγετε έγκυρη διεύθυνση email.";
	} else {
		$sql = "SELECT id FROM users WHERE email = ?";

		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $email);

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				// Store result
				mysqli_stmt_store_result($stmt);

				// Check if user exists, if yes then verify password
				if (mysqli_stmt_num_rows($stmt) == 1) {
					$email_err = "Υπάρχει ήδη χρήστης με αυτή τη διεύθυνση email.";
				}
			} else {
				$email_err = "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξανά αργότερα.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
	}

	// Validate name
	$name = trim($_POST["name"]);
	if (empty($name)) {
		$name_err = "Παρακαλώ εισάγετε το όνομά σας.";
	}

	// Validate surname
	$surname = trim($_POST["surname"]);
	if (empty($surname)) {
		$name_err = "Παρακαλώ εισάγετε το επώνυμό σας.";
	}

	// Validate password
	$password = trim($_POST["password"]);
	if (empty($password)) {
		$password_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασής σας.";
	} elseif (strlen($password) < 8) {
		$password_err = "Ο κωδικός πρόσβασης πρέπει να έχει τουλάχιστον 8 χαρακτήρες.";
	}

	// Validate confirm-password
	$confirm_password = trim($_POST["confirm-password"]);
	if (empty($confirm_password)) {
		$password_err = "Παρακαλώ επαληθεύστε τον κωδικό πρόσβασής σας.";
	} elseif (empty($password_err) & ($password != $confirm_password)) {
		$password_err = "Οι κωδικοί πρόσβασης δεν ταιριάζουν.";
	}

	// If no errors, proceed to insert values
	if (empty($email_err) && empty($password_err) && empty($name_err)) {
		// Prepare an insert statement
		$sql = "INSERT INTO users (email, password, name, surname) VALUES (?, ?, ?, ?)";

		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ssss", $email, $password, $name, $surname);

			// Hash password before insert
			$password = password_hash($password, PASSWORD_DEFAULT);

			//echo $email . $password . $name . $surname;
			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				session_start();

				// Store data in session variables
				$_SESSION["loggedin"] = true;
				$_SESSION["email"] = $email;
				$_SESSION["name"] = $name;

				// SUCCESS. Redirect user to home page
				header("location: /");
			} else {
				$email_err = "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξανά αργότερα";
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
	<div class="grid">
		<header class="royalcontent">
			<img id="login-logo" src="/images/logo.gif" class="logo" alt="Λογότυπο Υπουργείου">
			<h1 class="title stresstitle">Εγγραφή Χρήστη</h1>
		</header>
		<section>
		<form id="register-form" class="form c8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<?php
				if (!empty($email_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $email_err;
					echo '</p>';
				}
			?>
			<label for="email" class="required">Email:</label>
			<input type="email" name="email" id="email" required>

			<?php
				if (!empty($name_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $name_err;
					echo '</p>';
				}
			?>
			<div class="c6 noleftmargin">
				<label for="name" class="required">Όνομα:</label>
				<input type="text" name="name" id="name" required>
			</div>
			<div class="c6 norightmargin">
				<label for="surname" class="required">Επώνυμο:</label>
				<input type="text" name="surname" id="surname" required>
			</div>

			<?php
				if (!empty($password_err)) {
					echo '<p class="alert error clear">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $password_err;
					echo '</p>';
				}
			?>
			<label for="password" class="required">Κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" required>
			<label for="confirm-password" class="required">Επανάληψη κωδικού πρόσβασης:</label>
			<input type="password" name="confirm-password" id="confirm-password" required>

			<input type="checkbox" name="consent" id="consent" required>
			<label for="consent" class="required" style="display: inline-block">Συμφωνώ να <del>απολέσω τα νεφρά μου</del> με τους <a href="#">Όρους Χρήσης</a> και την <a href="#">Πολιτική Απορρήτου</a></label>

			<div  class="buttons space-top">
				<input type="reset" value="Καθαρισμός"> |
				<input type="submit" class="actionbutton" value="Υποβολή">
			</div>
		</form>
		</section>
	</div>
</body>
