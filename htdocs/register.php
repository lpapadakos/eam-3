<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Define variables and initialize with empty values
$afm = $amka = $name = $surname = $email = $password = $category = "";
$email_err = $name_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] != "POST") { // Save referrer on GET, to redirect on success
	if (isset($_GET['page']))
		$_SESSION['referrer'] = $_GET['page'];
	else
		$_SESSION['referrer'] = "/";
} else { // Processing form data when form is submitted
	// Include MySQL config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

	// Make sure this is a new user
	$email = trim($_POST["email"]);
	$afm = trim($_POST["afm"]);

	if (empty($email)) {
		$email_err = "Παρακαλώ εισάγετε έγκυρη διεύθυνση email.";
	} else if (empty($afm)) {
		$name_err = "Παρακαλώ εισάγετε τον ΑΦΜ σας.";
	} else {
		$sql = "SELECT * FROM users WHERE registered = TRUE AND email = ? OR afm = ?";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ss", $email, $afm);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_store_result($stmt);
		if (mysqli_stmt_num_rows($stmt) > 0) {
			$email_err = "Υπάρχει ήδη εγγεγραμμένος χρήστης με αυτή τη διεύθυνση email ή το AΦΜ.";
		}

		mysqli_stmt_close($stmt);
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

	// Validate category
	$category = trim($_POST["category"]);
	if (empty($category)) {
		$name_err = "Παρακαλώ επιλέξτε την κατηγορία σας (π.χ. Εργαζόμενος).";
	}

	// Validate password
	$password = $_POST["password"];
	if (empty($password)) {
		$password_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασής σας.";
	} elseif (strlen($password) < 8) {
		$password_err = "Ο κωδικός πρόσβασης πρέπει να έχει τουλάχιστον 8 χαρακτήρες.";
	}

	// Validate confirm-password
	$confirm_password = $_POST["confirm-password"];
	if (empty($confirm_password)) {
		$password_err = "Παρακαλώ επαληθεύστε τον κωδικό πρόσβασής σας.";
	} elseif (empty($password_err) & ($password != $confirm_password)) {
		$password_err = "Οι κωδικοί πρόσβασης δεν ταιριάζουν.";
	}

	// If no errors, proceed to insert values
	if (empty($email_err) && empty($name_err) && empty($password_err)) {
		// INSERT or UPDATE previously unregistered user's info
		$sql = "INSERT INTO users (afm, amka, name, surname, registered, email, password, category) VALUES (?, ?, ?, ?, TRUE, ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				amka = VALUES(amka),
				name = VALUES(name),
				surname = VALUES(surname),
				registered = VALUES(registered),
				email = VALUES(email),
				password = VALUES(password),
				category = VALUES(category)";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "sssssss", $afm, $amka, $name, $surname, $email, $password, $category);

		// Hash password before insert
		$password = password_hash($password, PASSWORD_DEFAULT);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);

		// LOG IN
		$referrer = $_SESSION['referrer'];

		// Unset all of the session variables
		$_SESSION = array();

		// Destroy the session.
		session_destroy();

		// New session with this user
		session_start();

		// Store data in session variables
		$_SESSION["loggedin"] = true;
		$_SESSION["afm"] = $afm;
		$_SESSION["name"] = $name;

		switch ($category) {
			case '1':
				$_SESSION["category"] = "employer";
				break;
			case '2':
				$_SESSION["category"] = "employee";
				break;
			case '3':
				$_SESSION["category"] = "unemployed";
				break;
		}

		// SUCCESS. Redirect user to referring page
		header("location: " . $referrer);
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
		<form id="register-form" class="c8" method="post" action="<?php echo samepage(); ?>">
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

			<label for="category" class="required">Ιδιότητα:</label>
			<select name="category" id="category">
				<!-- <option disabled selected> -- Επιλέξτε -- </option> -->
				<option value="1">Εργοδότης/τρια</option>
				<option value="2">Εργαζόμενος/η</option>
				<option value="3">Άνεργος/η</option>
			</select>

			<?php
				if (!empty($password_err)) {
					echo '<p class="alert error clear">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $password_err;
					echo '</p>';
				}
			?>
			<label for="password" class="required">Κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" autocomplete="new-password" minlength="8" maxlength="16" required>

			<label for="confirm-password" class="required">Επανάληψη κωδικού πρόσβασης:</label>
			<input type="password" name="confirm-password" id="confirm-password" autocomplete="new-password" minlength="8" maxlength="16" required>

			<input type="checkbox" name="consent" id="consent" required>
			<label for="consent" class="required" style="display: inline-block">Συμφωνώ να <del>απολέσω τα νεφρά μου</del> με τους <a href="#">Όρους Χρήσης</a> και την <a href="#">Πολιτική Απορρήτου</a></label>

			<div  class="buttons space-top">
				<input type="reset" value="Καθαρισμός"> |
				<input type="submit" class="actionbutton" value="Υποβολή">
			</div>
		</form>
	</section>
</body>
