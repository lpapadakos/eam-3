<?php

function samepage() {
	return htmlspecialchars(str_replace('index.php', '', $_SERVER["PHP_SELF"]));
}

function referrer() {
	return "page=" . samepage();
}

function loggedin() {
	return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
}

function autocomplete($input) {
	$flag = loggedin();

	if ($flag)
		echo 'value="' . $input . '"';

	return $flag;
}

function autocomplete_disabled($input) {
	if (autocomplete($input) && isset($input) && !empty($input))
		echo 'disabled';
}

session_start();

?>
