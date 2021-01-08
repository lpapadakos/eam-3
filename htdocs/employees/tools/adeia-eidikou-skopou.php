<?php

require_once "../../common.php";

// Define variables and initialize with empty values
$afm = $name = $surname = "";
$name_err= "";

if (loggedin()) {
	// Include config file
	require_once "../../config.php";

	$afm = $_SESSION["afm"];

	// Prepare a select statement
	$sql = "SELECT name, surname FROM users WHERE afm = ?";

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
				mysqli_stmt_bind_result($stmt, $name, $surname);

				if (mysqli_stmt_fetch($stmt)) {
					//TODO assume wrong as before?
				}
			}
		} else {
			$name_err = "Κάτι πήγε στραβά. Παρακαλώ δοκιμάστε ξανά αργότερα.";
		}

		// Close statement
		mysqli_stmt_close($stmt);
	}

	// Close connection
	mysqli_close($link);
}

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
<?php include '../../topnav.php'; ?>
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
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
				<p>
					Η άδεια ειδικού σκοπού απευθύνεται σε εργαζόμενους γονείς με παιδιά έως 15 ετών και παραμένει σε ισχύ ανάλογα με την πορεία ανοίγματος των σχολικών μονάδων, των παιδικών και βρεφικών σταθμών.
				</p>
				<section>
				<h2 class="maintitle space-top">
					<span>Στοιχεία Χρήστη</span>
				</h2>

				<div class="c6 noleftmargin">
					<label for="name" class="required">Όνομα:</label>
					<input type="text" name="name" id="name" maxlength="64" required <?php if (loggedin()) echo 'value="' . $name . '" disabled'; ?>>
				</div>
				<div class="c6 norightmargin">
					<label for="surname" class="required">Επώνυμο:</label>
					<input type="text" name="surname" id="surname" maxlength="64" required <?php if (loggedin()) echo 'value="' . $surname . '" disabled'; ?>>
				</div>

				<div class="c6 noleftmargin">
					<label for="afm" class="required">ΑΦΜ:</label>
					<input type="text" name="afm" id="afm" pattern="\d*" minlength="9" maxlength="9" required <?php if (loggedin()) echo 'value="' . $afm . '" disabled'; ?>>
				</div>
				<!-- <div class="c6 norightmargin">
					<label for="amka" class="required">ΑΜΚΑ:</label>
					<input type="text" name="amka" id="amka" pattern="\d*" minlength="11" maxlength="11" required <?php if (loggedin() && !empty($amka)) echo 'value="' . $amka . '" disabled'; ?>>
				</div> -->
				</section>

				<section class="clear">
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
							<input type="number" name="children" id="children" min="1" value="1" required>
				</div>

				<div id="field-container">
					<fieldset class="c12" id="child" style="display: none">
						<legend>Τέκνο</legend>
						<div class="c4">
							<label for="age" class="required">Ηλικία:</label>
							<input type="number" name="age" id="age" min="1" required>
						</div>
						<div class="c8">
							<label for="edu" class="required">Εκπαιδευτική βαθμίδα:</label>
							<select name="edu" id="edu">
								<option disabled selected value> -- Επιλέξτε -- </option>
								<option value="1">Βρεφικός/Βρεφονηπιακός/Παιδικός σταθμός</option>
								<option value="2">Νηπιαγωγείο</option>
								<option value="3">Δημοτικό</option>
								<option value="4">Γυμνάσιο</option>
								<option value="5">Ειδικό σχολείο</option>
								<option value="6">ΑΜΕΑ</option>
							</select>
						</div>
					</fieldset>
				</div>
				</section>

				<section class="clear">
				<h2 class="maintitle space-top">
					<span>Ειδικές Σημειώσεις</span>
				</h2>
				<label for="special-notes">Γράψτε τα σχόλιά σας εδώ:</label>
				<textarea name="special-notes" id="special-notes"></textarea>
				</section>

				<section>
					<p class="text-right">
						Πατώντας <strong>Υποβολή</strong> δηλώνετε ότι τα παραπάνω στοιχεία είναι αληθή.
					<p>
				</section>

				<div  class="buttons space-top">
					<input type="reset" value="Καθαρισμός"> |
					<input type="submit" class="actionbutton" value="Υποβολή">
				</div>
			</form>
		</section>
		<!-- end main content -->
	</div>
</section><!-- end grid -->
<!-- FOOTER
================================================== -->
<?php include '../../footer.php' ?>

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
		//alert($(this).val());
		if ($(this).val() <= "4")
			$(this).closest('fieldset').find('.c4 input').attr("max", "15");
		else
			$(this).closest('fieldset').find('.c4 input').removeAttr("max");
	});
})

</script>
</body>
</html>
