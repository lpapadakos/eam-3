<div class="c3">
	<nav id="sidebar-nav">
	<?php if (substr($_SERVER["PHP_SELF"], 0, 8) == "/profile"): ?>

	<a href="/profile"><h2 class="title stresstitle">ΠΡΟΦΙΛ</h2></a>
	<ul>

		<li style="background: #efe188" <?php if (substr($_SERVER["PHP_SELF"], 0, 23) == "/profile/employees-file") echo 'class="active"'; ?>>
			<?php if (isset($category) && $category == "employer"): ?>
			<a href="/profile/employees-file"><i class="icon-file-alt smallrightmargin"></i>Αρχείο Εργαζομένων</a>
			<?php else: ?>
			<a href="/profile/employees-file/view.php"><i class="icon-file-alt smallrightmargin"></i>Το Αρχείο Μου</a>
			<?php endif; ?>
		</li>
		<li><a href="/profile/change-password.php">Αλλαγή κωδικού πρόσβασης</a></li>
		<li><a href="#" class="alert error">Διαγραφή λογαριασμού</a></li>
	</ul>
	<?php elseif (substr($_SERVER["PHP_SELF"], 0, 27) == "/health-and-safety/covid-19"): ?>
	<a href="/health-and-safety/covid-19"><h2 class="title stresstitle">COVID-19</h2></a>
	<ul>
		<li <?php if ($_SERVER["PHP_SELF"] == "/health-and-safety/covid-19/employees.php") echo 'class="active"'; ?>><a href="/health-and-safety/covid-19/employees.php">Εργαζόμενοι</a></li>
		<li <?php if ($_SERVER["PHP_SELF"] == "/health-and-safety/covid-19/employers.php") echo 'class="active"'; ?>><a href="/health-and-safety/covid-19/employers.php">Εργοδότες</a></li>
	</ul>
	<?php endif; ?>
	</nav>
</div>
