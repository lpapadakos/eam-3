<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Logged-in only page
if (!loggedin()) {
	header("location: /login.php?" . referrer());
	exit;
}

// Define variables and initialize with empty values
$afm = $name = $surname = $category = $company_afm = $company_name = $company_address = $from = $to = "";
$err = "";

// Used to show success message
$submit_success = false;

// Include MySQL config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

// Get user info
$afm = $_SESSION["afm"];

$sql = "SELECT surname, category, company_id FROM users WHERE afm = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $afm);

mysqli_stmt_execute($stmt);

mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $surname, $category, $company_afm);
mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

// Assume unauthorized
$authorized = false;

if (isset($_GET['id']) && !empty(trim($_GET['id']))) { // Specific id set.
	$view_afm = trim($_GET['id']);

	if ($category == "employee" && $view_afm == $afm) { // Employees can see their own status
		$view_afm = $afm;
		$view_name = $_SESSION['name'];
		$view_surname = $surname;

		$authorized = true;
	} else {
		// Get viewed user info
		$sql = "SELECT name, surname, company_id FROM users WHERE afm = ?";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "s", $view_afm);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_store_result($stmt);
		mysqli_stmt_bind_result($stmt, $view_name, $view_surname, $view_company_afm);
		mysqli_stmt_fetch($stmt);

		mysqli_stmt_close($stmt);

		// Employers can see the status of workers in the company
		if ($category == "employer" && $view_company_afm == $company_afm)
			$authorized = true;
	}
} else { // No id set; See own status
	$view_afm = $afm;
	$view_name = $_SESSION['name'];
	$view_surname = $surname;

	$authorized = true;
}

// Kick out unauthorized users
if (!$authorized) {
	// Close connection
	mysqli_close($link);

	header("location: /");
	exit;
}

// User is authorized to see this. Let's go

// Get company info
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

// Retrieve info for this period

// Validate 'from' date
if (isset($_GET["from"])) {
	$from = trim($_GET["from"]);
	if (empty($from)) {
		$err = "Παρακαλώ εισάγετε την αρχική ημερομηνία για το διάστημα εμφάνισης.";
	}
}

// Validate 'to' date
if (isset($_GET["to"])) {
	$to = trim($_GET["to"]);
	if (empty($to)) {
		$err = "Παρακαλώ εισάγετε την τελική ημερομηνία για το διάστημα εμφάνισης.";
	}
}

?>

<!DOCTYPE HTML>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Κατάσταση Εργαζομένου - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
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
				<h1 class="titlehead">Κατάσταση Εργαζομένου</h1>
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
			<a href="/profile" rel="up up">ΠΡΟΦΙΛ</a> /
			<a href="/profile/employees-file" rel="up">ΑΡΧΕΙΟ ΕΡΓΑΖΟΜΕΝΩΝ</a> /
			<span>ΚΑΤΑΣΤΑΣΗ ΕΡΓΑΖΟΜΕΝΟΥ</span>
		</p>
	</nav>
	<div class="row">
		<!-- SIDEBAR -->
		<div class="c3">
			<nav id="sidebar-nav">
			<h2 class="title stresstitle">ΠΡΟΦΙΛ</h2>
			<ul>
				<?php if ($category == "employer"): ?>
				<li class="active" style="background: #efe188"><a href="."><i class="icon-file-alt smallrightmargin"></i>Αρχείο Εργαζομένων</a></li>
				<?php else: ?>
				<li class="active" style="background: #efe188"><a href="view.php"><i class="icon-file-alt smallrightmargin"></i>Το Αρχείο Μου</a></li>
				<?php endif; ?>
				<li><a href="change-password.php">Αλλαγή κωδικού πρόσβασης</a></li>
				<li><a href="#" class="alert error">Διαγραφή λογαριασμού</a></li>
			</ul>
			</nav>
		</div>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<h2 class="stresstitle"><i class="icon-building smallrightmargin"></i><?php echo $company_name; ?></h2>
			<h3 class="space-top"><i class="icon-user smallrightmargin"></i><?php echo $view_name . ' ' . $view_surname; ?></h3>

			<form class="form" id="date-range-form" method="get" action="<?php echo samepage(); ?>">
				<p class="space-top">
					Επιλέξτε διάστημα ώστε να εμφανιστούν τα στοιχεία του εργαζόμενου.
				</p>

				<?php
					if (!empty($err)) {
						echo '<p class="alert error">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $err;
						echo '</p>';
					}
				?>

				<input type="hidden" name="id" id="id" <?php autocomplete($view_afm); ?>>

				<div class="c3 noleftmargin">
					<label for="from" class="required">Από:</label>
					<input type="date" name="from" id="from" required <?php autocomplete($from); ?>>
				</div>
				<div class="c2 text-center">
					<br><i class="icon-arrow-right homeicon" style="vertical-align: bottom"></i>
				</div>
				<div class="c3">
					<label for="to" class="required">Έως:</label>
					<input type="date" name="to" id="to" required <?php autocomplete($to); ?>>
				</div>

				<noscript>
				<div class="c4 norightmargin text-right">
					<input type="reset" value="Καθαρισμός"> |
					<input type="submit" class="actionbutton" value="Υποβολή">
				</div>
				</noscript>
			</form>

			<div id="employee-tables" class="clear space-top">
			<div class="c4 noleftmargin">
			<table id="leave">
				<tr>
				<th><h4>Άδειες: <?php echo 'χ'; ?> ημέρες</h4></th>
				</tr>
				<tr>
				<?php if ($category == "employer"): ?>
				<td><button class="add"><i class="icon-plus smallrightmargin"></i>Προσθήκη</button></td>
				<?php endif; ?>
				</tr>
			</table>
			</div>

			<div class="c4">
			<table id="suspension">
				<tr>
				<th><h4><i class="icon-home smallrightmargin"></i>Αναστολή Σύμβασης: <?php echo 'χ'; ?> ημέρες</h4></th>
				</tr>
				<tr>
				<?php if ($category == "employer"): ?>
				<td><button class="add"><i class="icon-plus smallrightmargin"></i>Προσθήκη</button></td>
				<?php endif; ?>
				</tr>
			</table>
			</div>

			<div class="c4 norightmargin">
			<table id="remote-work">
				<tr>
				<th><h4><i class="icon-laptop smallrightmargin"></i>Τηλεργασία: <?php echo 'χ'; ?> ημέρες</h4></th>
				</tr>
				<?php
					// Close connection
					mysqli_close($link);
				?>
				<tr>
				<?php if ($category == "employer"): ?>
				<td><button class="add"><i class="icon-plus smallrightmargin"></i>Προσθήκη</button></td>
				<?php endif; ?>
				</tr>
			</table>
			</div>
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
	$('#from, #to').change(function(){
		// Disallow "to" date, before "from" date
		$('#to').attr('min', $('#from').val());

		// Submit dynamically on change
		if (Date.parse($('#from').val()) <= Date.parse($('#to').val()))
			$("#date-range-form").submit();
	});
});

</script>
</body>
</html>
