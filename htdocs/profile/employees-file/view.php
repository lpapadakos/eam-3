<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Logged-in only page
if (!loggedin()) {
	header("location: /login.php?" . referrer());
	exit;
}

// Define variables and initialize with empty values
$afm = $name = $surname = $category = $company_afm = $company_name = $company_address = $from = $to = $sql_from = $sql_to = "";
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

// POST (adding entries)? "Emulate" GET.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$_GET["id"] = $_POST["id"];
	$_GET["from"] = $_POST["from"];

	// Reset 'to' on submit, to see newer addition
	//$_GET["to"] = $_POST["to"];
}

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

// Retrieve date info for this period

// Validate 'from' date
if (isset($_GET["from"])) {
	$from = trim($_GET["from"]);

	if ($from == "today")
		$from = date("Y-m-d");
}

// Validate 'to' date
if (isset($_GET["to"])) {
	$to = trim($_GET["to"]);

	if ($from > $to) {
	 	$from = $to = "";
	 	$err = "Λανθασμένο διάστημα χρόνου.";
	}
}

// Processing when table changes submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $category == "employer") {
	// Only start things rolling if we know the table that was modified
	if (isset($_POST["type"]) && !empty(trim($_POST["type"]))) {
		$type = trim($_POST["type"]);

		// Recalls/Deletions
		if (isset($_POST["remove"])) {
			$recall = $_POST["remove"];

			$sql = "DELETE FROM employee_info
				WHERE id = ?
					AND type = ?
					AND from_date = ?
					AND to_date = ?";

			$stmt = mysqli_prepare($link, $sql);

			for ($i = 0; $i < count($recall); $i++) {
				// Get from, to for deletion
				$dates = explode(' ', $recall[$i]);

				mysqli_stmt_bind_param($stmt, "ssss", $view_afm, $type, $dates[0], $dates[1]);
				mysqli_stmt_execute($stmt);
			}

			mysqli_stmt_close($stmt);
		}

		// Insertion
		if ((isset($_POST["insert-from"]) && !empty(trim($_POST["insert-from"]))) &&
		    (isset($_POST["insert-to"]) && !empty(trim($_POST["insert-to"])))) {
			$sql_from = trim($_POST["insert-from"]);
			$sql_to = trim($_POST["insert-to"]);

			// INSERT entry
			$sql = "INSERT INTO employee_info (id, type, from_date, to_date) VALUES (?, ?, ?, ?)";

			$stmt = mysqli_prepare($link, $sql);
			mysqli_stmt_bind_param($stmt, "ssss", $view_afm, $type, $sql_from, $sql_to);

			mysqli_stmt_execute($stmt);

			mysqli_stmt_close($stmt);
		}

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
		<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/sidenav.php'; ?>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<h2 class="stresstitle"><i class="icon-building smallrightmargin"></i><?php echo $company_name; ?></h2>
			<h3 class="space-top"><i class="icon-user smallrightmargin"></i><?php echo $view_name . ' ' . $view_surname; ?></h3>

			<form id="date-range-form" method="get" action="<?php echo samepage(); ?>">
				<p class="space-top">
					Αν επιλέξτε ένα χρόνικό διάστημα, Θα εμφανιστούν μόνο οι δηλωμένοι περίοδοι αδειών/αναστολής/τηλεργασίας που επικαλύπτονται με το διάστημα που επιλέξατε.
				</p>

				<?php
					if (!empty($err)) {
						echo '<p class="alert error">';
						echo '<i class="icon-warning-sign smallrightmargin"></i>' . $err;
						echo '</p>';
					}
				?>

				<input type="hidden" name="id" id="id" <?php autocomplete($view_afm); ?>>

				<p class="alert info" id="view-window">
					Φαίνονται οι καταχωρίσεις
					από: <input type="date" aria-label="Από:" name="from" id="from" <?php autocomplete($from); ?>>
					έως: <input type="date" aria-label="Έως:" name="to" id="to" <?php autocomplete($to); ?>>
					<noscript>
					<input type="submit" class="actionbutton" value="Υποβολή">
					</noscript>

					<a id="view-all" href="<?php echo samepage() . "?id=" . $view_afm; ?>">Προβολή όλων</a> |
					<a id="view-today" href="<?php echo samepage() . "?id=" . $view_afm . "&from=today"; ?>">Προβολή από σήμερα</a>
				</p>
			</form>

			<?php
				if ($submit_success) {
					echo '<p class="alert success clear">';
					echo '<i class="icon-ok-sign smallrightmargin"></i>Οι αλλαγές σας στο αρχείο αποθηκεύτηκαν.';
					echo '</p>';
				};
			?>

			<div id="employee-tables" class="clear space-top">
				<?php
					// Repeat and make the three tables
					$tables = array("leave", "suspension", "remote-work");
					$labels = array("Άδειες", "Αναστολή Σύμβασης", "Τηλεργασία");
					$icons = array("icon-coffee", "icon-home", "icon-laptop");

					for ($i = 0; $i < count($tables); $i++):
				?>
				<div class="<?php echo (($category == "employer") ? "c6" : "c4"); ?> noleftmargin">
				<?php

				// Get days count
				$sql = "SELECT SUM(DATEDIFF(to_date, from_date) + 1)
					FROM employee_info
					WHERE
						id = ?
						AND type = '" . $tables[$i] . "'
						AND from_date <= ?
						AND to_date >= ?";

				$stmt = mysqli_prepare($link, $sql);
				mysqli_stmt_bind_param($stmt, "sss", $view_afm, $to, $from);

				mysqli_stmt_execute($stmt);

				mysqli_stmt_store_result($stmt);
				mysqli_stmt_bind_result($stmt, $days);
				mysqli_stmt_fetch($stmt);

				mysqli_stmt_close($stmt);

				?>

				<?php if ($category == "employer"): ?>
				<form method="post" action="<?php echo samepage(); ?>">
						<!-- POST request? keep the GET parameters for view -->
						<input type="hidden" name="from" required <?php autocomplete($from); ?>>
						<input type="hidden" name="to" required <?php autocomplete($to); ?>>

						<input type="hidden" name="id" <?php autocomplete($view_afm); ?>>
						<input type="hidden" name="type" <?php autocomplete($tables[$i]); ?>>
				<?php endif; ?>
				<table id="<?php echo $tables[$i]; ?>">
					<tr>
						<th colspan="4"><h4><i class="<?php echo $icons[$i] ?> smallrightmargin"></i><?php echo $labels[$i] . ": " . (isset($days) ? $days : 0); ?> ημέρες</h4></th>
					</tr>
					<tr>
					<?php if ($category == "employer"): ?>
						<th>Διαχείριση</th>
					<?php endif; ?>
						<th>Δηλωμένη από</th>
						<th>Δηλωμένη έως</th>
						<th>Ημέρες</th>
					</tr>
					<?php

					// Get rows of entries
					$sql = "SELECT from_date, to_date, DATEDIFF(to_date, from_date) + 1
						FROM employee_info
						WHERE
							id = ?
							AND type = '" . $tables[$i] . "' "
							. (empty($to) ? "" : " AND from_date <= ? ")
							. (empty($from) ? "" : " AND to_date >= ? ")
						. "ORDER BY from_date";

					$stmt = mysqli_prepare($link, $sql);

					if (!empty($from) && !empty($to))
						mysqli_stmt_bind_param($stmt, "sss", $view_afm, $to, $from);
					elseif (!empty($from) && empty($to))
						mysqli_stmt_bind_param($stmt, "ss", $view_afm, $from);
					elseif (empty($from) && !empty($to))
						mysqli_stmt_bind_param($stmt, "ss", $view_afm, $to);
					else
						mysqli_stmt_bind_param($stmt, "s", $view_afm);

					mysqli_stmt_execute($stmt);

					mysqli_stmt_store_result($stmt);
					mysqli_stmt_bind_result($stmt, $sql_from, $sql_to, $days);

					// Fetch each row of the result, append to table
					$row = 1;
					while (mysqli_stmt_fetch($stmt)):
					?>
					<tr>
						<?php if ($category == "employer"): ?>
						<td>
						<?php if ($sql_to >= date("Y-m-d")): // Can't really recall John's leave from 2006 ?>
						<!-- input associated with form on other td -->
						<input type="checkbox" id="<?php echo $tables[$i] . "-checkbox" . $row; ?>" name="remove[]" value="<?php echo $sql_from . ' ' . $sql_to ?>">
						<label for="<?php echo $tables[$i] . "-checkbox" . $row; ?>">Ανάκληση</label>
						<?php endif; ?>
						</td>
						<?php endif; ?>
						<td>
						<?php echo $sql_from; ?>
						</td>
						<td>
						<?php echo $sql_to; ?>
						</td>
						<td>
						<?php echo $days; ?>
						</td>

					</tr>
					<?php
					endwhile;

					mysqli_stmt_close($stmt);

					if ($category == "employer"):
					?>
					<tr class="add">
						<td>
							<!-- <input type="reset" class="icon-" aria-label="Καθαρισμός" value="&#xf014;"> -->
							<input type="submit" class="actionbutton" value="Υποβολή">
						</td>
						<td><input type="date" name="insert-from" aria-label="Εισαγωγή από:" class="from"></td>
						<td><input type="date" name="insert-to" aria-label="Εισαγωγή έως:" class="to"></td>
						<td class="days">(Νέα)</td>
					</tr>
					<?php endif; ?>
				</table>
				<?php if ($category == "employer"): ?>
				</form>
				<?php endif; ?>
				</div>
				<?php
				endfor;

				// Close connection
				mysqli_close($link);
				?>
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

	// For the tables
	$('.from').change(function(){
		// Disallow "to" date, before "from" date
		$(this).closest('tr').find('.to').attr('min', $(this).val());
	});

	$('.from, .to').change(function(){
		// Show number of days
		const oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
		var from = new Date($(this).closest('tr').find('.from').val());
		var to = new Date($(this).closest('tr').find('.to').val());

		var dayCount = Math.round(Math.abs((to - from) / oneDay)) + 1;

		$(this).closest('tr').find('.days').html(dayCount);
	});

	// View all function
	$('#view-all').click(function(){
		$('#from').val("");
		$('#to').val("");

		$('#date-range-form').submit();
	});
});

</script>
</body>
</html>
