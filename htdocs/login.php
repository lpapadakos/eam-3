<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect to homepage
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: /");
	exit;
}

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Include config file
	require_once "config.php";

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
		$sql = "SELECT password, name FROM users WHERE email = ?";

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
					mysqli_stmt_bind_result($stmt, $hashed_password, $name);

					if (mysqli_stmt_fetch($stmt)) {
						if (password_verify($password, $hashed_password)) {
							// Password is correct, so start a new session
							session_start();

							// Store data in session variables
							$_SESSION["loggedin"] = true;
							$_SESSION["email"] = $email;
							$_SESSION["name"] = $name;

							// SUCCESS. Redirect user to home page and clear error
							$email_err = "";
							header("location: /");
						}
					}
				}
			} else {
				$email_err = "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξανά αργότερα.";
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
	<div class="grid">
		<header class="royalcontent">
			<img id="login-logo" src="/images/logo.gif" class="logo" alt="Λογότυπο Υπουργείου">
			<h1 class="title stresstitle">Σύνδεση Χρήστη</h1><br>
			<span>(ας υποθέσουμε ότι αυτό γίνεται μέσω taxisNET)</span>
		</header>
		<section>
		<form id="login-form" class="form c8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
				if (!empty($password_err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $password_err;
					echo '</p>';
				}
			?>
			<label for="password" class="required">Κωδικός πρόσβασης:</label>
			<input type="password" name="password" id="password" required>
			<input type="submit" id="login" class="actionbutton" value="Σύνδεση">
		</form>
		<p class="royalcontent">
			Δεν έχετε λογαριασμό; <a href="register.php">Δημιουργήστε έναν</a>.
		</p>
		</section>
	</div>
</body>
