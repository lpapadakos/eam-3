<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php';

// Define variables and initialize with empty values
$afm = $name = $surname = $from = $to = $special_notes = "";
$children = 0;
$age = $edu = array();

$user_err = $leave_err = "";

// Used to show success message
$submit_success = false;

// Include MySQL config file
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/config.php';

// Autocomplete known fields for logged-in users (PERK!)
if (loggedin()) {
	$afm = $_SESSION["afm"];
	$name = $_SESSION["name"];

	$sql = "SELECT surname FROM users WHERE afm = ?";

	$stmt = mysqli_prepare($link, $sql);
	mysqli_stmt_bind_param($stmt, "s", $afm);

	mysqli_stmt_execute($stmt);

	mysqli_stmt_store_result($stmt);
	mysqli_stmt_bind_result($stmt, $surname);
	mysqli_stmt_fetch($stmt);

	mysqli_stmt_close($stmt);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Get these elements from the form, only if unknown from DB
	if (!loggedin()) {
		// Validate afm
		$afm = trim($_POST["afm"]);
		if (empty($afm)) {
			$user_err = "Παρακαλώ εισάγετε τον ΑΦΜ σας.";
		}

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
	}

	// Validate 'from' date
	$from = trim($_POST["from"]);
	if (empty($from)) {
		$leave_err = "Παρακαλώ εισάγετε την αρχική ημερομηνία για το διάστημα της άδειας.";
	}

	// Validate 'to' date
	$to = trim($_POST["to"]);
	if (empty($to)) {
		$leave_err = "Παρακαλώ εισάγετε την τελική ημερομηνία για το διάστημα της άδειας.";
	}

	// Validate number of children
	$children = trim($_POST["children"]);
	if (empty($children)) {
		$leave_err  = "Παρακαλώ εισάγετε τον αριθμό των τέκνων που ανήκουν στις σχετικές κατηγορίες.";
	}

	// Validate children information
	for ($i = 1; $i <= $children; $i++) {
		if (!isset($_POST["age" . $i])) {
			$leave_err  = "Παρακαλώ εισάγετε την ηλικία του/των τέκνου/ων.";
		} else {
			array_push($age, $_POST["age" . $i]);
			if (empty($age[$i - 1])) {
				$leave_err  = "Παρακαλώ εισάγετε την ηλικία του/των τέκνου/ων.";
			}
		}

		if (!isset($_POST["edu" . $i])) {
			$leave_err  = "Παρακαλώ εισάγετε την εκπαιδευτική βαθμίδα του/των τέκνου/ων.";
		} else {
			array_push($edu, $_POST["edu" . $i]);
			if (empty($edu[$i - 1])) {
				$leave_err  = "Παρακαλώ εισάγετε την εκπαιδευτική βαθμίδα του/των τέκνου/ων.";
			}
		}
	}

	$special_notes = trim($_POST["special-notes"]);

	// If no errors, proceed to insert values
	if (empty($user_err) && empty($leave_err)) {
		// 1. INSERT or UPDATE user entry, with number of children.
		// Name only changed from the form for logged out users
		// Don't modify existing 'registered' field because this might be a registered user operating while logged out.
		// TODO: In case where names differ? Use this or previous?
		$sql = "INSERT INTO users (afm, name, surname, children) VALUES (?, ?, ?, ?)
			ON DUPLICATE KEY UPDATE
				name = VALUES(name),
				surname = VALUES(surname),
				children = VALUES(children)";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ssss", $afm, $name, $surname, $children);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);


		// 2. INSERT children entries
		// Multiple bind calls aren't too wasteful because the number of children a parent has
		// (and/or is willing to input one by one in the form) is fairly limited
		$sql = "INSERT INTO children (parent_id, age, category) VALUES (?, ?, ?)";
			// Since the primary key is a composite of those three, no update needed
			// same for third sql
			// ON DUPLICATE KEY UPDATE
			// 	parent_id = VALUES(parent_id),
			// 	age = VALUES(age),
			// 	category = VALUES(category);"

		$stmt = mysqli_prepare($link, $sql);

		for ($i = 0; $i < $children; $i++) {
			mysqli_stmt_bind_param($stmt, "sss", $afm, $age[$i], $edu[$i]);
			mysqli_stmt_execute($stmt);
		}

		mysqli_stmt_close($stmt);


		// 3. INSERT special leave entry
		$sql = "INSERT INTO special_leave (parent_id, from_date, to_date, special_notes) VALUES (?, ?, ?, ?)";

		$stmt = mysqli_prepare($link, $sql);
		mysqli_stmt_bind_param($stmt, "ssss", $afm, $from, $to, $special_notes);

		mysqli_stmt_execute($stmt);

		mysqli_stmt_close($stmt);


		// If everything ran smoothly, it's time for the success page!
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
	<title>Άδεια Ειδικού Σκοπού - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
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
				<h1 class="titlehead">Αίτηση Άδειας Ειδικού Σκοπού</h1>
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
			<span>ΑΔΕΙΑ ΕΙΔΙΚΟΥ ΣΚΟΠΟΥ</span>
		</p>
	</nav>
	<div class="row">
		<!-- SIDEBAR -->
		<div class="c3">
			<a class="stresstitle" href="."><i class="icon-arrow-left smallrightmargin"></i>Επιστροφή στα Εργαλεία</a>
			<!-- <div class="leftsidebar">
				<nav id="sidebar-nav">
				<h2 class="title stresstitle">COVID-19</h2>
				<ul>
					<li><a href="employees.php">Εργαζόμενοι</a></li>
					<li><a href="employers.php">Εργοδότες</a></li>
				</ul>
				</nav>
			</div> -->
		</div>
		<!-- end sidebar -->
		<!-- MAIN CONTENT -->
		<section class="c9">
			<?php if (!$submit_success): ?>
			<!-- Step 1. The Form -->
			<form class="form" id="special-leave-form" method="post" action="<?php echo samepage(); ?>">
				<p>
					Η άδεια ειδικού σκοπού απευθύνεται σε εργαζόμενους γονείς με παιδιά έως 15 ετών και παραμένει σε ισχύ ανάλογα με την πορεία ανοίγματος των σχολικών μονάδων, των παιδικών και βρεφικών σταθμών.
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
					<label for="afm" class="required">ΑΦΜ:</label>
					<input type="text" name="afm" id="afm" pattern="[0-9]+" minlength="9" maxlength="9" required <?php autocomplete_disabled($afm); ?>>
				</div>

				<div class="c6 noleftmargin clear">
					<label for="name" class="required">Όνομα:</label>
					<input type="text" name="name" id="name" maxlength="64" required <?php autocomplete_disabled($name); ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="surname" class="required">Επώνυμο:</label>
					<input type="text" name="surname" id="surname" maxlength="64" required <?php autocomplete_disabled($surname); ?>>
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
					<span>Στοιχεία Άδειας</span>
				</h2>

				<div class="c6 noleftmargin">
					<label for="from" class="required">Από:</label>
					<input type="date" name="from" id="from" required>
				</div>
				<div class="c6 norightmargin">
					<label for="to" class="required">Έως:</label>
					<input type="date" name="to" id="to" required>
				</div>


				<label for="children" class="required">Αριθμός τέκνων <strong>ηλικίας κάτω των 15 ετών</strong> (εξαιρούνται τα τέκνα Ειδικής Αγωγής/ΑΜΕΑ):</label>
				<div class="c6 noleftmargin">
							<input type="number" name="children" id="children" min="1" max="200" value="1" required>
				</div>

				<div id="field-container">
				</div>
				</section>

				<section>
				<h2 class="maintitle space-top">
					<span>Ειδικές Σημειώσεις</span>
				</h2>
				<label for="special-notes">Γράψτε τα σχόλιά σας εδώ:</label>
				<textarea name="special-notes" id="special-notes" maxlength="2000"></textarea>
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
			<!-- Used to clone child fieldsets -->
			<fieldset class="c12" id="child" style="display: none">
				<legend>Τέκνο</legend>
				<div class="c4">
					<label for="age" class="required">Ηλικία:</label>
					<input type="number" name="age" id="age" min="1" required>
				</div>
				<div class="c8">
					<label for="edu" class="required">Εκπαιδευτική βαθμίδα:</label>
					<select name="edu" id="edu">
						<option disabled selected> -- Επιλέξτε -- </option>
						<option value="1">Βρεφικός/Βρεφονηπιακός/Παιδικός σταθμός</option>
						<option value="2">Νηπιαγωγείο</option>
						<option value="3">Δημοτικό</option>
						<option value="4">Γυμνάσιο</option>
						<option value="5">Ειδικό σχολείο</option>
						<option value="6">ΑΜΕΑ</option>
					</select>
				</div>
			</fieldset>
			<?php else: ?>
			<!-- Step 2. Success message! -->
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
	//$("#from").val(today);
	$('#from, #to').attr("min", today);

	// Disallow "to" date, before "from" date
	$('#from, #to').on('change', function(){
		$('#to').attr('min', $('#from').val());
	});


	// Children fieldsets
	function addTiles( start, end ) {
		for ( var i = start; i < end; i++ ) {
			var newChild = $("#child").clone();

			var name = "Τέκνο " + (i + 1);
			var id = "child" + (i + 1);
			var age_id = "age" + (i + 1);
			var edu_id = "edu" + (i + 1);

			newChild.attr("id", id)
				.appendTo("#field-container")
				.find("legend").html(name);

			newChild.find(".c4 label").attr("for", age_id);
			newChild.find(".c4 input")
					.attr("name", age_id)
					.attr("id", age_id);

			newChild.find(".c8 label").attr("for", edu_id);
			newChild.find(".c8 select")
				 	.attr("name", edu_id)
				 	.attr("id", edu_id);

			newChild.show();
		}
	}

	function removeTiles ( start, end ) {
		for ( var i = start; i < end; i++ ) {
			// Remove tiles backwards.
			$( '#field-container' ).find( 'fieldset:last-of-type' ).remove();
		}
	}

	// Handle input changes.
	function handleInput( e ) {
		var // Get input's val before change.
			oldVal = parseInt( $( this ).data( 'oldVal' ) ),
			// Get input's val after change.
			newVal = parseInt( $( this ).val() ),
			// Get input's max value, defined in input attribute.
			maxVal = parseInt( $( this ).attr( 'max' ) ),
			// Get input's min value, defined in input attribute.
			minVal = parseInt( $( this ).attr( 'min' ) );

		// When input values are removed completely by "Delete" and "Backspace" buttons, this fix changes null to 0.
		if ( !newVal  ) newVal = 0;

		// Allow only use of "Arrows", "Numbers", "Numpad Numbers", " Delete" and "Backspace" buttons, if value is inserted by keyboard.
		if ( e.type == 'keyup' && !( e.which == 8 || e.which == 46 || ( e.which > 36 && e.which < 41 ) || ( e.which > 47 && e.which < 58 ) || ( e.which > 95 && e.which < 106 ) ) ){
			$( this ).val( oldVal );
			return false;
		}

		// Limitation fix ( For browsers that do not support input[type=number] and fallback to the input input[type=text] )
		if ( newVal > maxVal ) {
			newVal = maxVal;
			$( this ).val( maxVal );
		}
		if ( newVal < minVal ) {
			newVal = minVal;
		}

		// Add - Remove tiles.
		if ( newVal > oldVal) {
			//Start loop from oldVal to append tiles beggining from the last and leave previous tiles intact.
			addTiles( oldVal, newVal );
		} else {
			// Oldval = what we had, newVal = what is left, difference = how many tiles to remove ( aka repeats of removing tiles backwards loop ).
			removeTiles( 0, oldVal-newVal );
		}

		//Update oldval for later use, if input is changed again.
		$( this ).data( 'oldVal', newVal);
	}

	//Add tiles based on the on-load value of input ( Number can be changed by input attribute "value").
	addTiles( 0, parseInt( $( '#children' ).val() ) );

	// Piece it up
	$( '#children' )
		// Store on-load value of input.
		.data( 'oldVal', $( '#children' ).val() )
		// Give focus to input. Not necessary of course. Just for immediate keyboard insert.
		.focus()
		// We update the value on blur, so if the inserted value is lower than the min limit, it changes back to the min value.
		.blur( function() { $( this ).val( $( this ).data( 'oldVal' ) ) } )
		// Safari fires the change event after input loses focus.
		// So we force input to lose focus so it can be updated but we focus back so user can click the input to insert from keyboard.
		.mouseup( function() { $( this ).blur().focus(); } )
		// Assign handleInput function to events
		.keyup( handleInput )
		.change( handleInput );


	// Age limit
	$('fieldset select').change(function(){
		if ($(this).val() <= "4")
			$(this).closest('fieldset').find('.c4 input').attr("max", "15");
		else
			$(this).closest('fieldset').find('.c4 input').removeAttr("max");
	});

	// Temporary until submit fixed
	$('form :submit').click(function(){
		$(this).closest('form').submit();
	});
});

</script>
</body>
</html>
