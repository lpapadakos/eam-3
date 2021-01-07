<!-- TODO BOIII
	include_path = ".${_SERVER['DOCUMENT_ROOT']}";
-->

<!DOCTYPE html>
<html lang="el">
<head>
	<meta name="viewport" content="width=device-width"/>
	<title>Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
	<link rel="shortcut icon" href="/favicon.ico"/>
	<!-- STYLES & JQUERY
	================================================== -->
	<link rel="stylesheet" type="text/css" href="/css/style.css"/>
	<link rel="stylesheet" type="text/css" href="/css/icons.css"/>
	<link rel="stylesheet" type="text/css" href="/css/slider.css"/>
	<link rel="stylesheet" type="text/css" href="/css/skinblue.css"/><!-- change skin color -->
	<link rel="stylesheet" type="text/css" href="/css/responsive.css"/>
	<script src="/js/jquery-1.9.0.min.js"></script><!-- the rest of the scripts at the bottom of the document -->
</head>
<body>
<!-- TOP LOGO & MENU
================================================== -->
<?php include 'topnav.php'; ?>
<div class="undermenuarea">
	<div class="boxedshadow">
	</div>
	<!-- SLIDER AREA
	================================================== -->
	<div id="da-slider" class="da-slider">
		<!--Slide 1-->
		<div class="da-slide">
			<h2> Ηλεκτρονικά Ραντεβού </h2>
			<p>
			Οι επισκέπτες δύνανται να κλείσουν ηλεκτρονικό ραντεβού μέσω της σχετικής πλατφόρμας μόνο για συγκεκριμένες κατηγορίες αιτήματος.
			</p>
			<a href="#" class="da-link" style="width:202px;">Δήλωση</a>
			<div class="da-img">
				<img src="https://startpage.com/av/proxy-image?piurl=https%3A%2F%2Fencrypted-tbn0.gstatic.com%2Fimages%3Fq%3Dtbn%3AANd9GcQBCZg0SSoZ6yIbH0HAdnbtmr8mQZjwVq-RDX0KX8RA29vIT1-A%26s&sp=1607880060T47965a34d0579d1357f31a1346a5945936fbaa85d125c087fb098af21e000b28" alt="">
			</div>
		</div>
		<!--Slide 2-->
		<div class="da-slide">
			<h2>Covid-19</h2>
			<p>
				Οδηγιες για την ασφαλεια στην εργασια απο τον Covid-19
			</p>
			<a href="#" class="da-link" style="width:192px;">Οδηγίες</a>
			<div class="da-img">
				<img src="https://frtntech.com/wp-content/uploads/2020/03/covid-banner.jpg" alt="">
			</div>
		</div>
	</div>
</div>
<!-- UNDER SLIDER - BLACK AREA
================================================== -->
<div class="undersliderblack" id="covid-area">
	<div class="grid">
		<div class="row space-bot">
			<div class="c12">
				<div class="c3 introbox">
					<div class="introboxinner">
						<a href="/health-and-safety/covid-19"><h2><i class="icon-medkit smallrightmargin"></i>COVID-19</h2></a>
						<p>Γρήγοροι σύνδεσμοι για το κοινό</p>
					</div>
				</div>
				<!--Box 2-->
				<a href="#"class="c3 introbox introboxfirst">
					<div class="introboxinner">
						<i class="icon-file-alt homeicone"></i>
						<h3>Οδηγίες</h3>
						<p>Πρόληψη και Αντιμετώπιση στο χώρο εργασίας</p>
					</div>
				</a>
				<!--Box 3-->
				<a href="#" class="c3 introbox introboxmiddle">
					<div class="introboxinner">
						<i class="icon-suitcase homeicone"></i>
						<h3>Επιλογές Εργασίας</h3>
						<p>Ενημερωθείτε για τις επιλογές σας ως εργαζόμενοι</p>
					</div>
				</a>
				<!--Box 1-->
				<a href="#" class="c3 introbox introboxlast">
					<div class="introboxinner">
						<i class="icon-bolt homeicone"></i>
						<h3>Ηλεκτρονικό Ραντεβού</h3>
						<p>Μπορείτε να ορίσετε ημερομηνία για εξυπηρέτηση δια ζώσης</p>
					</div>
				</a>

			</div>
		</div>
	</div>
</div>
<div class="shadowunderslider">
</div>
<!-- START content area
================================================== -->
<!--ΕΡΓΑΛΕΙΑ-->
<div class="grid">
	<!--INTRO-->
	<div class="c12">
		<div class="royalcontent">
			<h1 class="royalheader"><i class="icon-wrench smallrightmargin"></i>ΣΥΧΝΕΣ ΛΕΙΤΟΥΡΓΙΕΣ</h1>
			<!-- <h1 class="title" style="text-transform:none;">νβσφκηβεξκ</h1> -->
		</div>
	</div>
	<div id="tools" class="row space-bot flex">
		<!--Persona 1-->
		<div class="c4">
			<h2 class="title hometitlebg"><a href="/employers/tools">ΕΡΓΟΔΟΤΕΣ</a></h2>
			<ul class="noshadowbox">
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Αρχείο Κατάστασης Εργαζομένων</a></li>
				<li><a href="#"><i class="icon-large icon-money smallrightmargin"></i>Έκπτωση Εισφορών</a></li>
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Κρατική Επιχορήγηση</a></li>
			</ul>
		</div>
		<!--Persona 2-->
		<div class="c4">
			<h2 class="title hometitlebg"><a href="/employees/tools">ΕΡΓΑΖΟΜΕΝΟΙ</a></h2>
			<ul class="noshadowbox">
				<li><a href="#"><i class="icon-large icon-home smallrightmargin"></i>Άδεια Ειδικού Σκοπού</a></li>
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Προβολή Ενσήμων</a></li>
				<li><a href="#"><i class="icon-large icon-file smallrightmargin"></i>Καταγγελία στο Σ.ΕΠ.Ε</a></li>
			</ul>
		</div>
		<!--Persona 3-->
		<div class="c4">
			<h2 class="title hometitlebg"><a href="/unemployed/tools">ΑΝΕΡΓΟΙ</a></h2>
			<ul class="noshadowbox">
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Αίτηση Επιδόματος Ανεργίας</a></li>
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Αίτηση Χορήγησης Παροχών Μητρότητας</a></li>
				<li><a href="#"><i class="icon-large icon-cog smallrightmargin"></i>Αναζήτηση Θέσεων Εργασίας</a></li>
			</ul>
		</div>
	</div>
	<!-- SHOWCASE
	================================================== -->
	<div class="row space-top">
		<div class="c12 space-top">
			<h1 class="maintitle ">
			<span>Δελτια Τύπου και Ανακοινώσεις</span>
			</h1>
		</div>
	</div>
	<div class="row space-bot">
		<div class="c12">
			<div class="list_carousel">
				<div class="carousel_nav">
					<a class="prev" id="car_prev" href="#"><span>prev</span></a>
					<a class="next" id="car_next" href="#"><span>next</span></a>
				</div>
				<div class="clearfix">
				</div>
				<ul id="recent-projects">
					<!--featured-projects 1-->
					<li>
					<div class="featured-projects">
						<div class="featured-projects-content">
							<h1><a href="#">ΚΥΑ - ΔΙΑΔΙΚΑΣΙΑ ΓΙΑ ΤΗΝ ΨΗΦΙΑΚΗ ΣΥΝΔΕΣΗ ΠΑΙΔΙΩΝ ΜΕ ΥΠΟΨΗΦΙΟΥΣ ΑΝΑΔΟΧΟΥΣ Η ΘΕΤΟΥΣ ΓΟΝΕΙΣ (DT_2020-08-0_01)</a></h1>
							<p>
							Υπoγράφηκε χθες η Κοινή Υπουργική Απόφαση (ΚΥΑ) για την ολοκλήρωση της σύνδεσης των παιδιών που βρίσκονται σε δομές παιδικής προστασίας και των υποψηφίων γονέων. Η απόφαση προβλέπει, για πρώτη φορά, την ακριβή διαδικασία τοποθέτησης παιδιών σε ανάδοχες ή θετές οικογένειες μετά την πρόταση σύνδεσης από το Πληροφοριακό Σύστημα.								</p>
							<a href="#">Περισσότερα...</a>
						</div>
					</div>
					</li>
					<!--featured-projects 2-->
					<li>
					<div class="featured-projects">
						<div class="featured-projects-content">
							<h1><a href="#">ΤΕΛΕΤΗ ΠΑΡΑΛΑΒΗΣ ΤΟΥ ΥΦΥΠ. ΚΟΙΝΩΝΙΚΗΣ ΑΣΦΑΛΙΣΗΣ ΣΤΟ ΥΠΟΥΡΓΕΙΟ ΕΡΓΑΣΙΑΣ (DT_2020-08-05_01)</a></h1>
							<p>
							Τελετή παραλαβής στο Υπουργείο Εργασίας και Κοινωνικών Υποθέσεων από τον νέο Υφυπουργό Κοινωνικής Ασφάλισης Παναγιώτη Τσακλόγλου με αρμοδιότητα την Κοινωνική Ασφάλιση
							</p>
							<a href="#">Περισσότερα...</a>
						</div>
					</div>
					</li>
					<!--featured-projects 3-->
					<li>
					<div class="featured-projects">
						<div class="featured-projects-content">
							<h1><a href="#">ΑΝΑΡΤΗΣΗ ΟΡΙΣΤΙΚΩΝ ΠΙΝΑΚΩΝ ΓΙΑ ΤΟ ΠΡΟΓΡΑΜΜΑ ΚΟΙΝΩΦΕΛΟΥΣ ΑΠΑΣΧΟΛΗΣΗΣ 36.500 ΑΝΕΡΓΩΝ (DT_2020-08-04_01)</a></h1>
							<p>
							Αναρτήθηκαν σήμερα, Δευτέρα 3 Αυγούστου 2020, στη διαδικτυακή πύλη του ΟΑΕΔ www.oaed.gr ο Οριστικός Πίνακας Κατάταξης Ανέργων, στο Πρόγραμμα «Προώθηση της απασχόλησης μέσω προγραμμάτων κοινωφελούς χαρακτήρα, συμπεριλαμβανομένης και της κατάρτισης για 36.500 άτομα σε Δήμους, Περιφέρειες...								</p>
							<a href="#">Περισσότερα...</a>
						</div>
					</div>
					</li>
					<!--featured-projects 4-->
					<li>
					<div class="featured-projects">
						<div class="featured-projects-content">
							<h1><a href="#">ΈΚΤΑΚΤΗ ΑΝΑΚΟΙΝΩΣΗ - ΝΕΕΣ ΑΝΑΣΤΟΛΕΣ (DT_2020-08-03_01)</a></h1>
							<p>
							Το Υπουργείο Εργασίας και Κοινωνικών Υποθέσεων σε συνεργασία με το Υπουργείο Οικονομικών, στο πλαίσιο της κλιμάκωσης των μέτρων προστασίας από την πανδημία που έλαβε πριν λίγο η Πολιτική Προστασία, δίνει ξανά τη δυνατότητα στις επιχειρήσεις της εστίασης, του πολιτισμού και του αθλητισμού να θέτουν σε αναστολή τις συμβάσεις εργασίας...								</p>
							<a href="#">Περισσότερα...</a>
						</div>
					</div>
					</li>
					<!--featured-projects 5-->
					<li>
					<div class="featured-projects">
						<div class="featured-projects-content">
							<h1><a href="#">ΟΜΙΛΙΑ ΥΦΥΠΟΥΡΓΟΥ ΕΡΓΑΣΙΑΣ ΚΑΙ ΚΟΙΝΩΝΙΚΩΝ ΥΠΟΘΕΣΕΩΝ Κ. ΔΟΜΝΑΣ ΜΙΧΑΗΛΙΔΟΥ ΓΙΑ ΤΗΝ ΠΑΓΚΟΣΜΙΑ ΗΜΕΡΑ ΚΑΤΑ ΤΗΣ ΕΜΠΟΡΙΑΣ ΑΝΘΡΩΠΩΝ ΣΤΗΝ ΕΙΔΙΚΗ ΣΥΝΕΔΡΙΑΣΗ ΣΤΗ ΒΟΥΛΗ (DT_2020-07-31_01)</a></h1>
							<p>
							Για ένα έγκλημα κατά της ανθρώπινης αξιοπρέπειας και του πολιτισμού έκανε λόγο σήμερα η Υφυπουργός Εργασίας και Κοινωνικών Υποθέσεων κ. Δόμνα Μιχαηλίδου, στην ειδική εκδήλωση της Βουλής των Ελλήνων για την Παγκόσμια Ημέρα κατά της Εμπορίας Ανθρώπων...								</p>
							<a href="#">Περισσότερα...</a>
						</div>
					</div>
					</li>
					<!--featured-projects 6-->
				</ul>
				<div class="clearfix">
				</div>
			</div>
		</div>
	</div>
</div><!-- end grid -->

<!-- FOOTER
================================================== -->
<?php include 'footer.php' ?>
<!-- END CONTENT AREA -->
<!-- JAVASCRIPTS
================================================== -->
<!-- all -->
<script src="/js/modernizr-latest.js"></script>

<!-- menu & scroll to top -->
<script src="/js/common.js"></script>

<!-- slider -->
<script src="/js/jquery.cslider.js"></script>

<!-- cycle -->
<script src="/js/jquery.cycle.js"></script>

<!-- carousel items -->
<script src="/js/jquery.carouFredSel-6.0.3-packed.js"></script>

<!-- twitter -->
<!-- <script src="/js/jquery.tweet.js"></script> -->

<!-- Call Showcase - change 4 from min:4 and max:4 to the number of items you want visible -->
<script type="text/javascript">
$(window).load(function(){
			$('#recent-projects').carouFredSel({
				responsive: true,
				width: '100%',
				auto: true,
				circular	: true,
				infinite	: false,
				prev : {
					button		: "#car_prev",
					key			: "left",
						},
				next : {
					button		: "#car_next",
					key			: "right",
							},
				swipe: {
					onMouse: true,
					onTouch: true
					},
				scroll : 2000,
				items: {
					visible: {
						min: 4,
						max: 4
					}
				}
			});
		});
</script>

<!-- Call opacity on hover images from carousel-->
<script type="text/javascript">
$(document).ready(function(){
$("img.imgOpa").hover(function() {
$(this).stop().animate({opacity: "0.6"}, 'slow');
},
function() {
$(this).stop().animate({opacity: "1.0"}, 'slow');
});
});
</script>
</body>
</html>
