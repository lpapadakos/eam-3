<?php

// TODO: Logout user?

// Define variables and initialize with empty values
$afm = $amka = $name = $surname = $email = $password = "";
$email_err = $name_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Include config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

	// Make sure this is a new user
	$email = trim($_POST["email"]);
	$afm = trim($_POST["afm"]);

	if (empty($email)) {
		$email_err = "Παρακαλώ εισάγετε έγκυρη διεύθυνση email.";
	} else if (empty($afm)) {
		$name_err = "Παρακαλώ εισάγετε τον ΑΦΜ σας.";
	} else {
		$sql = "SELECT id FROM users WHERE registered = TRUE AND email = ? OR afm = ?;";

		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ss", $email, $afm);

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				// Store result
				mysqli_stmt_store_result($stmt);

				// Check if user exists, if yes then verify password
				if (mysqli_stmt_num_rows($stmt) > 0) {
					$email_err = "Υπάρχει ήδη εγγεγραμμένος χρήστης με αυτή τη διεύθυνση email ή το AΦΜ.";
				}
			} else {
				// DEBUG: show the actual error
				$email_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
	}

	// AMKA can be empty for now, just make it NULL for MySQL
	$amka = trim($_POST["amka"]);
	if (empty($amka)) {
		$amka = NULL;
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
	if (empty($email_err) && empty($name_err) && empty($password_err)) {
		// Prepare an insert statement. Update previously unregistered user's info
		$sql = "INSERT INTO users (afm, amka, name, surname, registered, email, password) VALUES (?, ?, ?, ?, TRUE, ?, ?)
			ON DUPLICATE KEY UPDATE
				amka = VALUES(amka),
				name = VALUES(name),
				surname = VALUES(surname),
				registered = VALUES(registered),
				email = VALUES(email),
				password = VALUES(password);";

		if ($stmt = mysqli_prepare($link, $sql)) {
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ssssss", $afm, $amka, $name, $surname, $email, $password);

			// Hash password before insert
			$password = password_hash($password, PASSWORD_DEFAULT);

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
				session_start();

				// Store data in session variables
				$_SESSION["loggedin"] = true;
				$_SESSION["afm"] = $afm;
				$_SESSION["name"] = $name;

				// SUCCESS. Redirect user to home page
				header("location: /");
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
			<h1 class="title stresstitle">Εγγραφή Χρήστη</h1>
		</header>
		<form id="register-form" class="form c8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
				if (!empty($name_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $name_err;
					echo '</p>';
				}
			?>
			<div class="c6 noleftmargin">
				<label for="afm" class="required">ΑΦΜ:</label>
				<input type="text" name="afm" id="afm" pattern="[0-9]+" minlength="9" maxlength="9" required >
			</div>
			<div class="c6 norightmargin">
				<label for="amka">ΑΜΚΑ:</label>
				<input type="text" name="amka" id="amka" pattern="[0-9]+" minlength="11" maxlength="11">
			</div>

			<div class="c6 noleftmargin">
				<label for="name" class="required">Όνομα:</label>
				<input type="text" name="name" id="name" maxlength="64" required>
			</div>
			<div class="c6 norightmargin">
				<label for="surname" class="required">Επώνυμο:</label>
				<input type="text" name="surname" id="surname" maxlength="64" required>
			</div>

			<?php
				if (!empty($password_err)) {
					echo '<p class="alert error clear">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $password_err;
					echo '</p>';
				}
			?>
			<label for="password" class="required">Κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" maxlength="16" required>

			<label for="confirm-password" class="required">Επανάληψη κωδικού πρόσβασης:</label>
			<input type="password" name="confirm-password" id="confirm-password" maxlength="16" required>

			<input type="checkbox" name="consent" id="consent" required>
			<label for="consent" class="required" style="display: inline-block">Συμφωνώ να <del>απολέσω τα νεφρά μου</del> με τους <a href="#">Όρους Χρήσης</a> και την <a href="#">Πολιτική Απορρήτου</a></label>

			<div  class="buttons space-top">
				<input type="reset" value="Καθαρισμός"> |
				<input type="submit" class="actionbutton" value="Υποβολή">
			</div>
		</form>
	</section>
</body>
