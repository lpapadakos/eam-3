<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to homepage
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
	header("location: /");
	exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Check if email is empty
	if (empty(trim($_POST["email"]))) {
		$email_err = "Παρακαλώ εισάγετε έγκυρη διεύθυνση email!";
	} else {
		$email = trim($_POST["email"]);
	}

	// Check if password is empty
	if (empty($_POST["password"])) {
		$password_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασής σας!";
	} else{
		$password = $_POST["password"];
	}

	// Validate credentials
	if(empty($email_err) && empty($password_err)) {
		// Prepare a select statement
		$sql = "SELECT password, name FROM users WHERE email = ?";

		if ($stmt = mysqli_prepare($link, $sql)) {
			//TODO: SQL injection?
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $email);

			// Attempt to execute the prepared statement
			if (mysqli_stmt_execute($stmt)) {
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

							// Redirect user to home page
							header("location: /");
							exit;
						}
					}
				}

				// Display an error message if user doesn't exist
				$email_err = "Λανθασμένο email ή κωδικός πρόσβασης.";
			} else {
				echo "Κάτι πήγε στραβα. Παρακαλώ δοκιμάστε ξανα αργότερα";
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
	<script src="/js/jquery-1.9.0.min.js"></script><!-- the rest of the scripts at the bottom of the document -->
</head>
<body>
	<div class="grid">
		<div class="royalcontent">
			<img id="login-logo" src="/images/logo.gif" class="logo" alt="Λογότυπο Υπουργείου">
			<h1 class="title stresstitle">Σύνδεση Χρήστη</h1>
		</div>
		<form id="login-form" class="form c8" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<label for="email">Email: <?php echo $email_err ?></label>
			<input type="email" name="email" id="email">
			<label for="password">Κωδικός πρόσβασης: <?php echo $password_err ?></label>
			<input type="password" name="password" id="password">
			<input type="submit" id="login" class="actionbutton" value="ΣΥΝΔΕΣΗ">
		</form>
	</div>
</body>
