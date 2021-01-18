<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php'; ?>
<!DOCTYPE HTML>
<html lang="el">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width"/>
	<title>Εργαλεία Εργοδοτών - Υπουργείο Εργασίας &amp; Κοινωνικών Υποθέσεων</title>
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
				<h1 class="titlehead">Εργαλεία Εργοδοτών</h1>
			</div>
		</div>
	</div>
</div>
<!-- CONTENT
================================================== -->
<div class="grid">
	<nav id="breadcrumbs" class="c12">
		<p>
			<a href="/" rel="index up" aria-label="Αρχική" class="icon-home"></a> /
			<a href="/employees" rel="up">ΕΡΓΟΔΟΤΕΣ</a> /
			<span>ΕΡΓΑΛΕΙΑ</span>
		</p>
	</nav>
	<!-- begin categories -->
	<div class="row space-bot">
		<div class="c12">
			<div id="nav">
				<ul class="option-set">
					<li><a href="" data-filter="*" class="selected">Προβολή όλων</a></li>
					<li><a href="" data-filter=".cat1">Εργασιακά</a></li>
					<li><a href="" data-filter=".cat2">Ασφάλιση</a></li>
					<li><a href="" data-filter=".cat3">COVID-19</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- end categories -->
	<div class="row space-top">
		<div id="content">
			<div class="boxfourcolumns cat1 cat3">
				<div class="boxcontainer">
					<a href="#">
					<div class="mosaic-block cover mosaicover4col">
						<div class="mosaic-overlay">
							<img src="/images/adeia-eidikou-skopou.jpg" alt="Εργαζόμενη μητέρα με το παιδί της">
						</div>
						<div class="mosaic-backdrop blue">
						<div class="details">
							<p>
								Εδώ μπορείτε να δηλώσετε τα απαραίτητα στοιχεία για εργαζομένους. Επιπλέον, παρέχεται η δυνατότητα δημιουργίας, επεξεργασίας ή διαγραφής αναστολής/εξ αποστάσεως εργασίας ενός εργαζομένου.
							</p>
							<i class="icon-cog mosaiclink"></i>
						</div>
						</div>
					</div>
					<h2><i class="icon-large icon-home smallrightmargin"></i>Εργαζόμενοι και Αναστολή/Εξ Αποστάσεως Εργασία</h2>
					</a>
				</div>
			</div>
			<div class="boxfourcolumns cat1 cat3">
				<div class="boxcontainer">
					<a href="e-rendezvous.php">
					<div class="mosaic-block cover mosaicover4col">
						<div class="mosaic-overlay">
							<img src="/images/ypoyrgeio_ergasias.jpg" alt="Το Υπουργείο Εργασίας">
						</div>
						<div class="mosaic-backdrop green">
						<div class="details">
							<p>
								Εδώ είναι δυνατό να κάνετε αίτηση για κρατική επιχορήγηση.
							</p>
							<i class="icon-cog mosaiclink"></i>
						</div>
						</div>
					</div>
					<h2><i class="icon-large icon-bolt smallrightmargin"></i>Κρατική Επιχορήγηση</h2>
					</a>
				</div>
			</div>
			<div class="boxfourcolumns cat2">
				<div class="boxcontainer">
					<a href="#">
					<div class="mosaic-block cover mosaicover4col">
						<div class="mosaic-overlay">
							<img src="http://placehold.it/278x180" alt="">
						</div>
						<div class="mosaic-backdrop yellow">
						<div class="details">
							<p></p>
							<i class="icon-cog mosaiclink"></i>
						</div>
						</div>
					</div>
					<h2><i class="icon-large icon-cog smallrightmargin"></i>Εργαλείο 3</h2>
					</a>
				</div>
			</div>
			<div class="boxfourcolumns cat1 cat2">
				<div class="boxcontainer">
					<a href="#">
					<div class="mosaic-block cover mosaicover4col">
						<div class="mosaic-overlay">
							<img src="http://placehold.it/278x180" alt="">
						</div>
						<div class="mosaic-backdrop red">
						<div class="details">
							<p></p>
							<i class="icon-cog mosaiclink"></i>
						</div>
						</div>
					</div>
					<h2><i class="icon-large icon-cog smallrightmargin"></i>Εργαλείο 4</h2>
					</a>
				</div>
			</div>
		</div>
	</div>
</div><!-- end grid -->
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
<!-- <script src="js/jquery.tweet.js"></script> -->

<!-- filtering -->
<script src="/js/jquery.isotope.min.js"></script>

<!-- hover effects -->
<script type="text/javascript" src="/js/mosaic.1.0.1.min.js"></script>

<!-- CALL hover effects -->
<script type="text/javascript">
			$(document).ready(function($){
				$('.cover').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'578'		//Horizontal position on hover
				});
		    });
</script>

<!-- CALL isotope filtering -->
<script>
$(document).ready(function(){
var $container = $('#content');
  $container.imagesLoaded( function(){
        $container.isotope({
	filter: '*',
	animationOptions: {
     duration: 750,
     easing: 'linear',
     queue: false,
   }
});
});
$('#nav a').click(function(){
  var selector = $(this).attr('data-filter');
    $container.isotope({
	filter: selector,
	animationOptions: {
     duration: 750,
     easing: 'linear',
     queue: false,
   }
  });
  return false;
});
$('#nav a').click(function (event) {
    $('a.selected').removeClass('selected');
    var $this = $(this);
    $this.addClass('selected');
    var selector = $this.attr('data-filter');
    $container.isotope({
         filter: selector
    });
    return false; // event.preventDefault()
});
});
 </script>
</body>
</html>
