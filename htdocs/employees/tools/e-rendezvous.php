<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Define variables and initialize with empty values
$afm = $name = $surname = $email = $phone = $date = $time = $reason = "";
$user_err = $rendezvous_err = "";

// Used to show success message
$submit_success = false;

// Autocomplete known fields for logged-in users (PERK!)
if (loggedin()) {
	// Include config file
	require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

	$afm = $_SESSION["afm"];
	$name = $_SESSION["name"];

	// Prepare a select statement
	$sql = "SELECT surname, email, phone FROM users WHERE afm = ?;";

	if ($stmt = mysqli_prepare($link, $sql)) {
		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "s", $afm);

		// Attempt to execute the prepared statement
		if (mysqli_stmt_execute($stmt)) {
			// Store result
			mysqli_stmt_store_result($stmt);

			// Check if user exists, if yes then verify password
			if (mysqli_stmt_num_rows($stmt) == 1) {
				// Bind result variables
				mysqli_stmt_bind_result($stmt, $surname, $email, $phone);

				if (!mysqli_stmt_fetch($stmt)) {
					$user_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
				}
			}
		} else {
			$user_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
		}

		// Close statement
		mysqli_stmt_close($stmt);
	}
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// TODO: Extra validation?

	// Get these elements from the form, only if the user isn't logged in
	if (!loggedin()) {
		// Include config file
		require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

		// Validate name
		$name = trim($_POST["name"]);
		if (empty($name)) {
			$user_err = "Παρακαλώ εισάγετε το όνομά σας.";
		}

		// Validate surname
		$surname = trim($_POST["surname"]);
		if (empty($surname)) {
			$user_err = "Παρακαλώ εισάγετε το επώνυμό σας.";
		}

		// Validate afm
		$afm = trim($_POST["afm"]);
		if (empty($afm)) {
			$user_err = "Παρακαλώ εισάγετε τον ΑΦΜ σας.";
		}

		// Validate email
		$email = trim($_POST["email"]);
		if (empty($afm)) {
			$user_err = "Παρακαλώ εισάγετε τη διεύθνυση email σας.";
		}

		$phone = trim($_POST["phone"]);
	}

	// Validate date
	$date = trim($_POST["date"]);
	if (empty($date)) {
		$rendezvous_err = "Παρακαλώ εισάγετε την ημερομηνία συνάντησης.";
	}

	// Validate time
	$time = trim($_POST["time"]);
	if (empty($time)) {
		$rendezvous_err = "Παρακαλώ εισάγετε την ώρα συνάντησης.";
	}

	// Validate reason
	$reason = trim($_POST["reason"]);
	if (empty($reason)) {
		$rendezvous_err  = "Παρακαλώ εισάγετε τον αίτημα σας, ώστε να σας εξυπηρετήσουμε.";
	}

	// If no errors, proceed to insert values
	if (empty($user_err) && empty($rendezvous_err)) {
		// 1. Update number of children

		// For LOGGED-IN user: simple update on existing record
		if (loggedin() && isset($phone) && $phone != "") {
			$sql = "UPDATE users SET phone = ? WHERE afm = ?;";

			if ($stmt = mysqli_prepare($link, $sql)) {
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "ss", $phone, $afm);

				// Attempt to execute the prepared statement
				if (!mysqli_stmt_execute($stmt)) {
					$user_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		} else { // For LOGGED-OUT/UNREGISTERED user: Update or insert user entry, with email and phone number.
			// Don't modify existing 'registered' field or email (used for login) because this might be a registered user operating while logged out.
			// TODO: In case where names differ? Use this or previous?
			$sql = "INSERT INTO users (afm, name, surname, registered, email, phone) VALUES (?, ?, ?, FALSE, ?, ?)
				ON DUPLICATE KEY UPDATE
					name = VALUES(name),
					surname = VALUES(surname),
					phone = VALUES(phone);";

			if ($stmt = mysqli_prepare($link, $sql)) {
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssss", $afm, $name, $surname, $email, $phone);

				// Attempt to execute the prepared statement
				if (!mysqli_stmt_execute($stmt)) {
					$user_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}

		// For ANY user:

		// 2. Create e-rendezvous entry
		if (empty($user_err)) {
			$sql = "INSERT INTO e_rendezvous (user_id, time, reason) VALUES (?, ?, ?);";

			if ($stmt = mysqli_prepare($link, $sql)) {
				$datetime = "";

				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sss", $afm, $datetime, $reason);

				// Concat values of inputs to get full datetime
				$datetime = $date . ' ' . $time;

				// Attempt to execute the prepared statement
				if (!mysqli_stmt_execute($stmt)) {
					$user_err = "Σφάλμα: [" . mysqli_error($link) . "]. Παρακαλώ δοκιμάστε ξανά αργότερα.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
		}
	}

	// Close connection
	mysqli_close($link);

	// If everything ran smoothly, it's time for the success page!
	if (empty($user_err) && empty($rendezvous_err)) {
		$submit_success = true;
	}
}

?>

<!DOCTYPE HTML>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Ηλεκτρονικό Ραντεβού - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
	<link rel="shortcut icon" href="/favicon.ico"/>
	<!-- STYLES & JQUERY
	================================================== -->
	<link rel="stylesheet" type="text/css" href="/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="/css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="/css/skinblue.css"/><!-- change skin color -->
	<link rel="stylesheet" type="text/css" href="/css/responsive.css"/>
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
				<h1 class="titlehead">Ηλεκτρονικό Ραντεβού</h1>
			</div>
		</div>
	</div>
</div>
<!-- CONTENT
================================================== -->
<section class="grid">
	<nav id="breadcrumbs" class="c12">
		<p>
			<a href="/" rel="index up up" aria-label="Αρχική" class="icon-home"></a> /
			<a href="/employees" rel="up up">ΕΡΓΑΖΟΜΕΝΟΙ</a> /
			<a href="/employees/tools" rel="up">ΕΡΓΑΛΕΙΑ</a> /
			<span>ΗΛΕΚΤΡΟΝΙΚΟ ΡΑΝΤΕΒΟΥ</span>
		</p>
	</nav>
	<div class="row">
		<!-- SIDEBAR -->
		<div class="c3">
			<a class="stresstitle" href="."><i class="icon-arrow-left smallrightmargin"></i>Επιστροφή στα Εργαλεία</a>
		</div>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<!-- Step 1. The Form -->
			<?php if (!$submit_success): ?>
			<form class="form" id="special-leave-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<p>
					Εάν <strong>επείγει</strong> κάποιο ζήτημα για το οποίο <strong>δεν μπορείτε να εξυπηρετηθήτε ηλεκτρονικά</strong>, υπάρχει η δυνατότητα καθορισμού συγκεκριμένης ημερομηνίας και ώρας για φυσική εξυπηρέτηση.
				</p>

				<section>
				<?php
					if (!empty($user_err)) {
						echo '<p class="alert error clear">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $user_err;
						echo '</p>';
					}
				?>
				<h2 class="maintitle space-top">
					<span>Στοιχεία Χρήστη</span>
				</h2>

				<div class="c6 noleftmargin">
					<label for="name" class="required">Όνομα:</label>
					<input type="text" name="name" id="name" maxlength="64" required <?php perk($name); ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="surname" class="required">Επώνυμο:</label>
					<input type="text" name="surname" id="surname" maxlength="64" required <?php perk($surname); ?>>
				</div>

				<div class="c6 noleftmargin">
					<label for="afm" class="required">ΑΦΜ:</label>
					<input type="text" name="afm" id="afm" pattern="[0-9]+" minlength="9" maxlength="9" required <?php perk($afm); ?>>
				</div>
				<!-- <div class="c6 norightmargin">
					<label for="amka" class="required">ΑΜΚΑ:</label>
					<input type="text" name="amka" id="amka" pattern="[0-9]+" minlength="11" maxlength="11" required <?php perk($amka); ?>>
				</div> -->

				<div class="clear">
					<div class="c6 noleftmargin">
						<label for="email" class="required">Email:</label>
						<input type="email" name="email" id="email" maxlength="64" required <?php perk($email); ?>>
					</div>
					<div class="c6 norightmargin">
						<label for="phone">Τηλέφωνο (Ελλάδας, χωρίς το πρόθημα +30):</label>
						<input type="tel" name="phone" id="phone" pattern="[0-9]+" minlength="10" maxlength=10" <?php perk($phone); ?>>
					</div>
				</div>
				</section>

				<section class="clear">
				<?php
					if (!empty($leave_err)) {
						echo '<p class="alert error clear">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $leave_err;
						echo '</p>';
					}
				?>
				<h2 class="maintitle space-top">
					<span>Στοιχεία Ραντεβού</span>
				</h2>

				<div>
					<div class="c6 noleftmargin">
						<label for="from" class="required">Επιλογή ημέρας:</label>
						<input type="date" name="date" id="date" required>
					</div>
					<div class="c6 norightmargin">
						<label for="from" class="required">Επιλογή ώρας:</label>
						<input type="time" name="time" id="time" required>
					</div>
				</div>



				<label for="reason" class="required">Περιγράψτε το αίτημά σας εδώ, εφόσον εμπίπτει στις επιτρεπόμενες συναλλαγές:</label>
				<textarea name="reason" id="reason" maxlength="2000" required></textarea>
				</section>

				<div class="c8 noleftmargin">
					<p class="alert info" style="display: inline-block">
						<i class="icon-info-sign smallrightmargin"></i>Πατώντας <strong>Υποβολή</strong> δηλώνετε ότι τα παραπάνω στοιχεία είναι αληθή.
					</p>
				</div>
				<div class="buttons c4 norightmargin">
					<input type="reset" value="Καθαρισμός"> |
					<input type="submit" class="actionbutton" value="Υποβολή">
				</div>
			</form>
			<!-- Step 2. Success message! -->
			<?php else: ?>
				<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/success.php' ?>
			<?php endif; ?>

		</section>
		<!-- end main content -->
	</div>
</section><!-- end grid -->
<!-- FOOTER
================================================== -->
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/footer.php' ?>

<!-- JAVASCRIPTS
================================================== -->
<!-- all -->
<script src="/js/modernizr-latest.js"></script>

<!-- menu & scroll to top -->
<script src="/js/common.js"></script>

<!-- cycle -->
<script src="/js/jquery.cycle.js"></script>

<!-- twitter -->
<!-- <script src="/js/jquery.tweet.js"></script> -->

<script>

$(document).ready(function(){
	// Date validation
	var today = new Date().toISOString().split('T')[0];

	// Disallow dates before today
	//$("#date").val(today);
	$('#date').attr("min", today);

	// Disallow "to" date, before "from" date
	// $('#from, #to').on('change', function(){
	// 	$('#to').attr('min', $('#from').val());
	// });
});

</script>
</body>
</html>
