<?php

function loggedin() {
	return (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
}

function perk($input) {
	if (loggedin() && isset($input)) {
		echo 'value="' . $input . '" disabled';
	}
}

session_start();

?>
