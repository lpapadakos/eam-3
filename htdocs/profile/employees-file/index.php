<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Logged-in only page
if (!loggedin()) {
	header("location: /login.php?" . referrer());
	exit;
}

// Define variables and initialize with empty values
$afm = $name = $surname = $email = $phone = $category = $contract = $role = $company_afm = $company_name = $company_address =  "";
$err = "";

// Used to show success message
$submit_success = false;

// Include MySQL config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

// Get (hopefully) employer's info
$afm = $_SESSION["afm"];
$name = $_SESSION["name"];

$sql = "SELECT category, company_id FROM users WHERE afm = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $afm);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $category, $company_afm);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

// Only employers allowed here. Redirect others to their own page
if ($category != "employer") {
	mysqli_close($link);

	header("location: view.php");
	exit;
}

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
	// Adding a new employee

	// Validate afm
	$afm = trim($_POST["afm"]);
	if (empty($afm)) {
		$err = "Παρακαλώ εισάγετε τον ΑΦΜ του εργαζόμενου.";
	}

	// Validate name
	$name = trim($_POST["name"]);
	if (empty($name)) {
		$err = "Παρακαλώ εισάγετε το όνομά του εργαζόμενου.";
	}

	// Validate surname
	$surname = trim($_POST["surname"]);
	if (empty($surname)) {
		$err = "Παρακαλώ εισάγετε το επώνυμό του εργαζόμενου.";
	}

	// Validate contract
	$contract = trim($_POST["contract"]);
	if (empty($contract)) {
		$err = "Παρακαλώ εισάγετε τον τύπο απασχόλησης του εργαζόμενου.";
	}

	// Validate role
	$role = trim($_POST["role"]);
	if (empty($role)) {
		$err = "Παρακαλώ εισάγετε την ειδικότητα του εργαζόμενου.";
	}

	// Validate email
	$email = trim($_POST["email"]);
	if (empty($afm)) {
		$err = "Παρακαλώ εισάγετε τη διεύθυνση email του εργαζόμενου.";
	}

	$phone = trim($_POST["phone"]);

	// If no errors, proceed to insert values
	if (empty($err)) {
		// Update or insert employee
		$sql = "INSERT INTO users (afm, name, surname, email, phone, category, company_id, contract, role) VALUES (?, ?, ?, ?, ?, 'employee', ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				category = VALUES(category),
				company_id = VALUES(company_id),
				contract = VALUES(contract),
				role = VALUES(role)";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ssssssss", $afm, $name, $surname, $email, $phone, $company_afm, $contract, $role);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);

		// If everything ran smoothly, it's time for the success message!

		$submit_success = true;
	}
}

?>

<!DOCTYPE HTML>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Αρχείο Εργαζομένων - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
	<link rel="shortcut icon" href="/favicon.ico"/>
	<!-- STYLES & JQUERY
	================================================== -->
	<link rel="stylesheet" type="text/css" href="/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="/css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="/css/skinblue.css"/><!-- change skin color -->
	<link rel="stylesheet" type="text/css" href="/css/responsive.css"/>
	<link rel="stylesheet" href="/css/theme.blue.css">
	<script src="/js/jquery-1.9.0.min.js"></script><!-- the rest of the scripts at the bottom of the document -->
	<script type="text/javascript" src="/js/jquery.tablesorter.combined.js"></script>
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
				<h1 class="titlehead">Αρχείο Εργαζομένων</h1>
			</div>
		</div>
	</div>
</div>
<!-- CONTENT
================================================== -->
<section class="grid">
	<nav id="breadcrumbs" class="c12">
		<p>
			<a href="/" rel="index up" aria-label="Αρχική" class="icon-home"></a> /
			<a href="/profile" rel="up">ΠΡΟΦΙΛ</a> /
			<span>ΑΡΧΕΙΟ ΕΡΓΑΖΟΜΕΝΩΝ</span>
		</p>
	</nav>
	<div class="row">
		<!-- SIDEBAR -->
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/sidenav.php'; ?>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<h2 class="stresstitle"><i class="icon-building smallrightmargin"></i><?php echo $company_name; ?></h2>
			<p class="space-top">
				Παρακάτω εμφανίζονται οι εργαζόμενοι στην επιχείρισή σας. Μπορείτε να προσθέσετε νέους εργαζόμενους, καθώς και διαχειριστείτε τα στοιχεία αδειών, τηλεργασίας κ.α., κάνωντας κλίκ στην σειρά του πίνακα, για το κάθε ατόμο.
			</p>

			<?php
				if (!empty($err)) {
					echo '<p class="alert error">';
					echo '<i class="icon-warning-sign smallrightmargin"></i>' . $err;
					echo '</p>';
				}

				if ($submit_success) {
					echo '<p class="alert success">';
					echo '<i class="icon-ok-sign smallrightmargin"></i>Προστέθηκε νέος εργαζόμενος στην εταιρεία.';
					echo '</p>';
				};
			?>

			<div class="c10 noleftmargin">
				<input class="search" type="search" data-column="all" placeholder="Αναζήτηση σε όλες τις στήλες...">
			</div>
			<button type="button" class="reset c2">Καθαρισμός φίλτρου</button>
			<table id="employees" class="tablesorter-blue">
				<thead>
				<tr>
				<th>ΑΦΜ</th>
				<th>Επώνυμο</th>
				<th>Όνομα</th>
				<th>Απασχόληση</th>
				<th>Ειδικότητα</th>
				<th>Email</th>
				<th>Τηλέφωνο</th>
				</tr>
				</thead>
				<tbody>
			<?php

			// Get employees
			$sql = "SELECT afm, surname, name, contract+0, role, email, phone FROM users WHERE company_id = ? AND category = 'employee'";

			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, "s", $company_afm);

			mysqli_stmt_execute($stmt);

			mysqli_stmt_store_result($stmt);
			mysqli_stmt_bind_result($stmt, $afm, $surname, $name, $contract, $role, $email, $phone);

			// Fetch each row of the result, append to table
			while (mysqli_stmt_fetch($stmt)) {
				echo '<tr onclick="window.location=\'view.php?id=' . $afm . '\'">';
				echo "<td>" . $afm . "</td>";
				echo "<td>" . $surname . "</td>";
				echo "<td>" . $name . "</td>";
				echo "<td>" . (($contract == '1') ? "Πλήρης" : "Μερική") . "</td>";
				echo "<td>" . $role ."</td>";
				echo "<td>" . $email . "</td>";
				echo "<td>" . $phone ."</td>";
				echo "</tr>";
			}

			mysqli_stmt_close($stmt);

			// Close connection
			mysqli_close($link);

			?>
			</tbody>
			</table>

			<form id="add-employee-form" method="post" action="<?php echo samepage(); ?>">
				<fieldset id="employee-data">
					<legend>Νέα καταχώρηση εργαζομένου</legend>

					<div class="c6 noleftmargin">
						<label for="afm" class="required">ΑΦΜ:</label>
						<input type="text" name="afm" id="afm" pattern="[0-9]+" minlength="9" maxlength="9" required >
					</div>

					<div class="clear">
					<div class="c6 noleftmargin">
						<label for="name" class="required">Όνομα:</label>
						<input type="text" name="name" id="name" maxlength="64" required>
					</div>
					<div class="c6 norightmargin">
						<label for="surname" class="required">Επώνυμο:</label>
						<input type="text" name="surname" id="surname" maxlength="64" required>
					</div>

					<div class="clear">
					<div class="c6 noleftmargin">
						<label for="contract" class="required">Τύπος απασχόλησης:</label>
						<select name="contract" id="contract">
							<!-- <option disabled selected> -- Επιλέξτε -- </option> -->
							<option value="1">Πλήρης</option>
							<option value="2">Μερική</option>
						</select>
					</div>
					<div class="c6 norightmargin">
						<label for="role" class="required">Ειδικότητα:</label>
						<input type="text" name="role" id="role" maxlength="255" required>
					</div>
					</div>

					<div class="clear">
					<div class="c6 noleftmargin">
						<label for="email" class="required">Email:</label>
						<input type="email" name="email" id="email" maxlength="64" required ?>
					</div>
					<div class="c6 norightmargin">
						<label for="phone">Τηλέφωνο (Ελλάδας, χωρίς το πρόθημα +30):</label>
						<input type="tel" name="phone" id="phone" pattern="[0-9]+" minlength="10" maxlength=10>
					</div>
				</div>

				</fieldset>

				<div class="buttons norightmargin text-right">
					<input type="reset" value="Καθαρισμός"> |
					<input type="submit" class="actionbutton" value="Προσθήκη">
				</div>
			</form>
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
	$("#employees").tablesorter({
		//theme: 'blue',
		widgets: ["filter"],
		widgetOptions : {
			// filter_anyMatch replaced! Instead use the filter_external option
			// Set to use a jQuery selector (or jQuery object) pointing to the
			// external filter (column specific or any match)
			filter_external : '.search',

			// add a default type search to the first name column
			//filter_defaultFilter: { 1 : '~{query}' },

			// column filters
			filter_columnFilters: false,
			// filter_placeholder: { search : 'Search...' },
			// filter_saveFilters : true,
			filter_reset: '.reset'
		}
	});
});

</script>
</body>
</html>
