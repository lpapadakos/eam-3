<div class="grid">
	<div class="row space-bot">
		<!--Logo-->
		<div class="c4">
			<a href="/">
				<img src="/images/logo.gif" class="logo" alt="Λογότυπο Υπουργείου">
			</a>
		</div>
		<!--Menu-->
		<div id="menu" class="c8 no-js flex">
			<nav id="top-nav" class="c10">
			<ul id="responsivemenu">
				<li <?php if ($_SERVER["PHP_SELF"] == "/index.php") echo 'class="active"'; ?>><a href="/"><i class="icon-home homeicon"></i>ΑΡΧΙΚΗ</a></li>
				<li <?php if (substr($_SERVER["PHP_SELF"], 0, 9) == "/ministry") echo 'class="active"'; ?>><a href="/ministry">ΤΟ ΥΠΟΥΡΓΕΙΟ</a>
				<ul>
					<li><a href="/ministry/structure.php">Οργανωτική Δομή</a></li>
					<li><a href="/ministry/secretariat">Γενικές Γραμματείες</a>
					<!-- <ul>
						<li><a href="/ministry/structure.php">Οργανωτική Δομή</a></li>
						<li><a href="/ministry/secretariat">Γενικές Γραμματείες</a></li>
					</ul> -->
					</li>
					<li><a href="/ministry/sepe.php">Σ.ΕΠ.Ε</a></li>
				</ul>
				</li>
				<li <?php if (substr($_SERVER["PHP_SELF"], 0, 10) == "/employers") echo 'class="active"'; ?>><a href="/employers">ΕΡΓΟΔΟΤΕΣ</a>
				<ul>
					<li><a href="/employers/tools">Εργαλεία</a></li>
					<li><a href="/employers/legal">Νομοθεσία</a></li>
					<li><a href="/employers/programs.php">Προγράμματα</a></li>
				</ul>
				</li>
				<li <?php if (substr($_SERVER["PHP_SELF"], 0, 10) == "/employees") echo 'class="active"'; ?>><a href="/employees">ΕΡΓΑΖΟΜΕΝΟΙ</a>
				<ul>
					<li><a href="/employees/tools">Εργαλεία</a></li>
					<li><a href="/employees/legal">Νομοθεσία</a></li>
					<li><a href="/employees/programs.php">Προγράμματα</a></li>
				</ul>
				</li>
				<li <?php if (substr($_SERVER["PHP_SELF"], 0, 11) == "/unemployed") echo 'class="active"'; ?>><a href="/unemployed">ΑΝΕΡΓΟΙ</a>
				<ul>
					<li><a href="/unemployed/tools">Εργαλεία</a></li>
					<li><a href="/unemployed/legal">Νομοθεσία</a></li>
					<li><a href="/unemployed/programs.php">Προγράμματα</a></li>
				</ul>
				</li>
				<li <?php if (substr($_SERVER["PHP_SELF"], 0, 18) == "/health-and-safety") echo 'class="active"'; ?>><a href="/health-and-safety">ΑΣΦΑΛΕΙΑ</a>
				<ul>
					<li><a href="/health-and-safety/covid-19">COVID-19</a></li>
					<li><a href="/health-and-safety/legal">Νομοθεσία</a></li>
				</ul>
				</li>
			</ul>
			</nav>
			<div id="login-area" class="c2">
				<?php if (loggedin()): ?>
				<a id="profile" class="actionbutton" title="Προφίλ" href="/profile"><i class="icon-user smallrightmargin"></i><?php echo $_SESSION["name"]; ?></a>
				<a id="logout" href="<?php echo '/logout.php?' . referrer(); ?>">Αποσύνδεση <i class="icon-signout"></i></a>
				<?php else: ?>
				<a id="login" class="actionbutton" href="<?php echo '/login.php?' . referrer(); ?>"><i class="icon-signin"></i> ΣΥΝΔΕΣΗ</a>
				<a id="register" href="<?php echo '/register.php?' . referrer(); ?>">ΕΓΓΡΑΦΗ</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
