<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Logged-in only page
if (!loggedin()) {
	header("location: /login.php?" . referrer());
	exit;
}

// Define variables and initialize with empty values
$afm = $amka = $name = $surname = $email = $hashed_password = $phone = $category = $company_afm = $company_name = $company_address =  "";
$user_err = $company_err = "";

// Used to show success message
$submit_success = false;

// Include MySQL config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

// Autocomplete known fields. Users can change their information here.

// Get user section
$afm = $_SESSION["afm"];
$name = $_SESSION["name"];
$category = $_SESSION["category"];

$sql = "SELECT amka, surname, email, password, phone, company_id FROM users WHERE afm = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $afm);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $amka, $surname, $email, $hashed_password, $phone, $company_afm);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


// Get company section
$sql = "SELECT name, address FROM companies WHERE afm = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $company_afm);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) == 1) {
	mysqli_stmt_bind_result($stmt, $company_name, $company_address);
	mysqli_stmt_fetch($stmt);
}

mysqli_stmt_close($stmt);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get the changed elements from the form, and update records in db

	// User info
	// AFM is immutable

	// Validate amka
	if (empty(trim($_POST["amka"])) && !is_null($amka) && !empty($amka))
		$user_err = "Παρακαλώ εισάγετε τον ΑΜΚΑ σας.";
	else
		$amka = trim($_POST["amka"]);

	// Validate name
	if (empty(trim($_POST["name"])))
		$user_err = "Παρακαλώ εισάγετε το όνομά σας.";
	else
		$name = trim($_POST["name"]);

	// Validate surname
	if (empty(trim($_POST["surname"])))
		$user_err = "Παρακαλώ εισάγετε το επώνυμό σας.";
	else
		$surname = trim($_POST["surname"]);

	// Validate email
	if (empty(trim($_POST["email"])))
		$user_err = "Παρακαλώ εισάγετε το email σας.";
	else
		$email = trim($_POST["email"]);

	// Validate phone
	if (empty(trim($_POST["phone"])) && !is_null($phone) && !empty($phone))
		$user_err = "Παρακαλώ εισάγετε τον αριθμό τηλεφώνου σας.";
	else
		$phone = trim($_POST["phone"]);

	//TODO: hmm
	// Category is immutable

	if ($category == "employer") {
		// Existing company afm is immutable, else insert new company
		if (!isset($company_afm)) {
			// Validate company afm
			if (empty(trim($_POST["company-afm"])))
				$company_err = "Παρακαλώ εισάγετε τον ΑΦΜ/VAT της εταιρείας.";
			else
				$company_afm = trim($_POST["company-afm"]);
		}

		// Validate company name
		if (empty(trim($_POST["company-name"])))
			$company_err = "Παρακαλώ εισάγετε την επνυμία της εταιρείας.";
		else
			$company_name = trim($_POST["company-name"]);

		// Validate company address
		if (empty(trim($_POST["company-address"])))
			$company_err = "Παρακαλώ εισάγετε τη διεύθυνση της εταιρείας.";
		else
			$company_address = trim($_POST["company-address"]);
	}

	// Validate password to apply changes
	$password = $_POST["password"];
	if (!password_verify($password, $hashed_password))
		$user_err = "Παρακαλώ εισάγετε τον κωδικό πρόσβασής σας ώστε να αποθηκευτούν οι αλλαγές.";

	// If no errors, proceed to insert values
	if (empty($user_err) && empty($company_err)) {
		// Update user info
		$sql = "UPDATE users SET
				amka = ?,
				name = ?,
				surname = ?,
				email = ?,
				phone = ?
			WHERE afm = ?";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ssssss", $amka, $name, $surname, $email, $phone, $afm);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);

		// Employers can change company details
		if ($category == "employer") {
			// Update company info
			$sql = "INSERT INTO companies (afm, name, address) VALUES (?, ?, ?)
				ON DUPLICATE KEY UPDATE
					name = VALUES(name),
					address = VALUES(address)";

			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, "sss", $company_afm, $company_name, $company_address);

			mysqli_stmt_execute($stmt);

			mysqli_stmt_close($stmt);
		}

		// If everything ran smoothly, it's time for the success message!
		$submit_success = true;
	}
}

// Close connection
mysqli_close($link);

?>

<!DOCTYPE HTML>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Προφίλ - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
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
				<h1 class="titlehead">Προφίλ Χρήστη</h1>
			</div>
		</div>
	</div>
</div>
<!-- CONTENT
================================================== -->
<section class="grid">
	<nav id="breadcrumbs" class="c12">
		<p>
			<a href="/" rel="index" aria-label="Αρχική" class="icon-home"></a> /
			<span>ΠΡΟΦΙΛ</span>
		</p>
	</nav>
	<div class="row">
		<!-- SIDEBAR -->
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/sidenav.php'; ?>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<?php
				if ($submit_success) {
					echo '<p class="alert success">';
					echo '<i class="icon-ok-sign smallrightmargin"></i>Οι αλλαγές στο προφίλ σας αποθηκεύτηκαν.';
					echo '</p>';
				};
			?>
			<form id="profile-data-form" method="post" action="<?php echo samepage(); ?>">
				<p>
					Εδώ μπορείτε να δείτε και <strong>αλλάξετε</strong> τα στοιχεία του προφίλ σας, καθώς και να ενημερωθείτε για την κατάσταση σας στην επιχείριση: (ημέρες αναστολής, τηλεργασίας, άδειες).
				</p>

				<section>
				<h2 class="maintitle space-top">
					<span>Στοιχεία Χρήστη</span>
				</h2>
				<?php
					if (!empty($user_err)) {
						echo '<p class="alert error">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $user_err;
						echo '</p>';
					}
				?>

				<div class="c6 noleftmargin">
					<label for="afm" class="required">ΑΦΜ:</label>
					<input type="text" name="afm" id="afm" pattern="[0-9]+" minlength="9" maxlength="9" required <?php autocomplete_disabled($afm); ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="amka">ΑΜΚΑ:</label>
					<input type="text" name="amka" id="amka" pattern="[0-9]+" minlength="11" maxlength="11" <?php autocomplete($amka); ?>>
				</div>

				<div class="c6 noleftmargin">
					<label for="name" class="required">Όνομα:</label>
					<input type="text" name="name" id="name" maxlength="64" required <?php autocomplete($name); ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="surname" class="required">Επώνυμο:</label>
					<input type="text" name="surname" id="surname" maxlength="64" required <?php autocomplete($surname); ?>>
				</div>

				<div class="c6 noleftmargin">
					<label for="email" class="required">Email:</label>
					<input type="email" name="email" id="email" maxlength="64" required <?php autocomplete($email); ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="phone">Τηλέφωνο (Ελλάδας, χωρίς το πρόθημα +30):</label>
					<input type="tel" name="phone" id="phone" pattern="[0-9]+" minlength="10" maxlength=10" <?php autocomplete($phone); ?>>
				</div>

				<label for="category" class="required">Ιδιότητα:</label>
				<select name="category" id="category" disabled>
					<!-- <option disabled selected> -- Επιλέξτε -- </option> -->
					<option value="1" <?php if ($category == "employer") echo 'selected' ?>>Εργοδότης/τρια</option>
					<option value="2" <?php if ($category == "employee") echo 'selected' ?>>Εργαζόμενος/η</option>
					<option value="3" <?php if ($category == "unemployed") echo 'selected' ?>>Άνεργος/η</option>
				</select>
				</section>

				<section class="clear">
				<h2 class="maintitle space-top">
					<span>Στοιχεία Επιχείρισης</span>
				</h2>
				<?php
					if (!empty($company_err)) {
						echo '<p class="alert error">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $company_err;
						echo '</p>';
					}
				?>

				<?php if ($category != "employer"): ?>
				<p class="alert info">
					<i class="icon-info-sign smallrightmargin"></i>Τα παρακάτω στοιχεία ορίζονται από τον εργοδότη.
				</p>
				<?php endif; ?>

				<div class="c6 noleftmargin">
					<label for="company-afm" class="required">ΑΦΜ/VAT:</label>
					<input type="text" name="company-afm" id="company-afm" pattern="[0-9]+" minlength="9" maxlength="9" required <?php autocomplete_disabled($company_afm); if ($category != "employer") echo ' disabled'; ?>>
				</div>

				<div class="clear">
					<label for="company-name" class="required">Επωνυμία:</label>
					<input type="text" name="company-name" id="company-name" maxlength="64" required <?php autocomplete($company_name); if ($category != "employer") echo ' disabled'; ?>>
				</div>

				<div class="clear">
					<label for="company-address" class="required">Διεύθυνση:</label>
					<input type="text" name="company-address" id="company-address" maxlength="64" required <?php autocomplete($company_address); if ($category != "employer") echo ' disabled'; ?>>
				</div>
				</section>

				<div class="alert info">
					<label for="password" class="required">Εισάγετε τον <strong>κωδικό πρόσβασής</strong> σας ώστε να αποθηκευτούν οι αλλαγές:</label>
					<input type="password" name="password" id="password" maxlength="16" required>
				</div>

				<div class="buttons norightmargin text-right">
					<input type="reset" value="Επαναφορά πεδίων"> |
					<input type="submit" class="actionbutton" value="Αποθήκευση αλλαγών">
				</div>
			</form>

			<!-- <form class="space-top red">
				<section>
				<h2 class="maintitle space-top">
					<span>ΔΙΑΓΡΑΦΗ ΛΟΓΑΡΙΑΣΜΟΥ</span>
				</h2>
				</section>
			</form> -->
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

<!-- <script>

$(document).ready(function(){

});

</script> -->
</body>
</html>
