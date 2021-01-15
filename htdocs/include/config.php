<?php

// Enable exception handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
set_exception_handler(function ($e) {
	error_log($e->getMessage());

	// Don't reveal sensitive info to users.
	//die("Σφάλμα Βάσης Δεδομένων. Παρακαλώ δοκιμάστε αργότερα");

	// Debug version:
	die("Σφάλμα Βάσης Δεδομένων: " . $e->getMessage());
});

// Attempt to connect to MySQL database
$link = mysqli_connect("localhost", "root", "", "sdi1700117");

// We'll be using Greek, after all
mysqli_set_charset($link, "utf8mb4");

?>
