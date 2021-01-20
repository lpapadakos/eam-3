<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Define variables and initialize with empty values
$afm = $name = $surname = $email = $message = "";
$children = 0;
$age = $edu = array();

$err = "";

// Used to show success message
$submit_success = false;

// Include MySQL config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

// Autocomplete known fields for logged-in users (PERK!)
if (loggedin()) {
	$afm = $_SESSION["afm"];
	$name = $_SESSION["name"];

	$sql = "SELECT surname, email FROM users WHERE afm = ?";

	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, "s", $afm);

	mysqli_stmt_execute($stmt);

	mysqli_stmt_store_result($stmt);
	mysqli_stmt_bind_result($stmt, $surname, $email);
	mysqli_stmt_fetch($stmt);

	mysqli_stmt_close($stmt);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get these elements from the form, only if unknown from DB
	if (!loggedin()) {
		// Validate name
		$name = trim($_POST["name"]);
		if (empty($name)) {
			$err = "Παρακαλώ εισάγετε το όνομά σας.";
		}

		// Validate surname
		$surname = trim($_POST["surname"]);
		if (empty($surname)) {
			$err = "Παρακαλώ εισάγετε το επώνυμό σας.";
		}

		// Validate email
		$email = trim($_POST["email"]);
		if (empty($email)) {
			$email = "Παρακαλώ εισάγετε το email σας.";
		}

	}

	$message = trim($_POST["message"]);

	// If no errors, proceed to insert values
	if (empty($err)) {
		// 1. INSERT or UPDATE user entry, with email number.
		// Name only changed from the form for logged out users
		// Don't modify existing 'registered' field because this might be a registered user operating while logged out.
		// TODO: In case where names differ? Use this or previous?
		$sql = "INSERT INTO users (afm, name, surname, email) VALUES (?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				name = VALUES(name),
				surname = VALUES(surname),
				email = VALUES(email)";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ssss", $afm, $name, $surname, $email);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);


		// 2. INSERT contact entry
		$sql = "INSERT INTO contact (id, message) VALUES (?, ?)";

		$stmt = mysqli_prepare($link, $sql);

		mysqli_stmt_bind_param($stmt, "ss", $afm, $message);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);


		// If everything ran smoothly, it's time for the success page!
		$submit_success = true;
	}
}

// Close connection
mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="el">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width" />
	<title>Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
	<link rel="shortcut icon" href="/favicon.ico" />
	<!-- STYLES & JQUERY
	================================================== -->
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/css/icons.css" />
	<link rel="stylesheet" type="text/css" href="/css/slider.css" />
	<link rel="stylesheet" type="text/css" href="/css/skinblue.css" /><!-- change skin color -->
	<link rel="stylesheet" type="text/css" href="/css/responsive.css" />
	<script src="/js/jquery-1.9.0.min.js"></script><!-- the rest of the scripts at the bottom of the document -->
</head>

<body>
	<!-- TOP LOGO & MENU
================================================== -->
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/topnav.php'; ?>
	<!-- HEADER
================================================== -->
	<div class="undermenuarea">
		<div class="boxedshadow">
		</div>
		<div class="grid">
			<div class="row">
				<div class="c8">
					<h1 class="titlehead">Επικοινωνία</h1>
				</div>
			</div>
		</div>
	</div>
	<!-- CONTENT
================================================== -->
	<div class="grid">
		<nav id="breadcrumbs" class="c12">
			<p>
				<a href="/" rel="index" aria-label="Αρχική" class="icon-home"></a> /
				<span>ΕΠΙΚΟΙΝΩΝΙΑ</span>
			</p>
		</nav>
		<div class="row">
			<div class="wrapaction c12">
				<div class="c9">
					<h1 class="subtitles">Αλλαγές στη φυσική εξυπηρέτηση λόγω COVID-19</h1>
					Λόγω των περιοριστικών μέτρων, δεν είναι δυνατή η φυσική εξυπηρέτηση όλων των αιτημάτων. Όσοι πολίτες επιθυμούν να προσέλθουν στις Υπηρεσίες του υπουργείου, θα πρέπει να υποβάλλουν το αίτημά τους για ραντεβού στην αίτηση, δηλώνοντας τον λόγο προσέλευσης.
				</div>
				<div class="c3 text-center" style="margin-top: 40px;">
					<a class="actionbutton" href="/employees/tools/e-rendezvous.php">
						<i class="icon-bolt"></i> ΚΛΕΙΣΤΕ ΡΑΝΤΕΒΟΥ
					</a>
				</div>
			</div>
			<!-- CONTACT FORM -->
			<div class="c8 space-top">
				<h2 class="maintitle">
					<span><i class="icon-envelope-alt"></i> Επικοινωνήστε μαζί μας</span>
				</h2>
				<?php
					if (!empty($user_err)) {
						echo '<p class="alert error clear">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $user_err;
						echo '</p>';
					}
				?>
				<?php
					if ($submit_success) {
						echo '<p class="alert success">';
						echo '<i class="icon-ok-sign smallrightmargin"></i>Το μήνυμα σας εστάλη. ΘΑ σας απαντήσουμε το συντομότερο δυνατόν.';
						echo '</p>';
					};
				?>
				<div class="wrapcontact">
					<form method="post" action="<?php echo samepage(); ?>" id="contactform">
						<div class="form">
							<div class="c6 noleftmargin">
								<label for="name" class="required">Όνομα:</label>
								<input type="text" name="name" id="name" maxlength="64" required <?php autocomplete_disabled($name); ?>>
							</div>
							<div class="c6 norightmargin">
								<label for="surname" class="required">Επώνυμο:</label>
								<input type="text" name="surname" id="surname" maxlength="64" required <?php autocomplete_disabled($surname); ?>>
							</div>

							<label for="email" class="required">Email:</label>
							<input type="email" name="email" id="email" required <?php autocomplete_disabled($email); ?>>

							<label for="message">Μήνυμα:</label>
							<textarea name="message" class="ctextarea" rows="9"></textarea>

							<input type="submit" id="submit" class="actionbutton" value="Αποστολή">
						</div>
					</form>
				</div>
			</div>
			<div class="c4 space-top">
				<h1 class="maintitle">
					<span><i class="icon-map-marker"></i> Τοποθεσία Κεντρικών Γραφείων</span>
				</h1>
				<dl>
					<dt>Σταδίου 29, Αθήνα 105 59</dt>
					<dd>
						<span>
							Τηλέφωνο Επικοινωνίας: 213-1516649
						</span>
					</dd>
					<dd>Διεύθυνση Ηλεκτρονικού Ταχυδρομείου: <a href="more.html">pliroforisi-politi@ypakp.gr</a></dd>
				</dl>
			</div>
		</div>
	</div><!-- end grid -->

	<!-- FOOTER
================================================== -->
	<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/footer.php' ?>
	<!-- END CONTENT AREA -->
	<!-- JAVASCRIPTS
================================================== -->
	<!-- all -->
	<script src="/js/modernizr-latest.js"></script>

	<!-- menu & scroll to top -->
	<script src="/js/common.js"></script>

	<!-- twitter -->
	<!-- <script src="/js/jquery.tweet.js"></script> -->

</body>

</html>
