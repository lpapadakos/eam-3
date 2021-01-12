<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/include/common.php'; ?>

<!DOCTYPE html>
<html lang="el">
<head>
	<meta charset="utf-8">
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
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/topnav.php'; ?>

<div class="grid">
    <nav id="breadcrumbs" class="c12">
		<p>
			<a href="/" rel="index up" aria-label="Αρχική" class="icon-home"></a> /
			<span>Επικοινωνία</span>
		</p>
	</nav>
		<div class="row space-top">
			<!-- CONTACT FORM -->
			<div class="c8 space-top">
				<h1 class="maintitle">
				<span><i class="icon-envelope-alt"></i> Ηλεκτρονικό Ραντεβού</span>
				</h1>
				<div class="wrapcontact">
					<div class="done">
						<div class="alert-box success ctextarea">
							 Η αίτηση σας υποβλήθηκε<a href="" class="close">x</a>
						</div>
					</div>
					<form method="post" action="contact.php" id="contactform">
						<div class="form">
                            <div class="c3 noleftmargin">
                                <label for="name" class="required">Όνομα:</label>
                                <input type="text" name="name" id="name" maxlength="64" required <?php if (loggedin()) echo 'value="' . $name . '" disabled'; ?>>
                            </div>
                            <div class="c3 norightmargin">
                                <label for="surname" class="required">Επώνυμο:</label>
                                <input type="text" name="surname" id="surname" maxlength="64" required <?php if (loggedin()) echo 'value="' . $surname . '" disabled'; ?>>
                            </div>
							<div class="c7 noleftmargin">
								<label>Διεύθυνση Ηλεκτρονικού Ταχυδρομείου</label>
								<input type="text" name="email">
                            </div>
                                <label class="c12" id="nopadding">Ειδικές Σημειώσεις</label>
                                <textarea name="comment" class="ctextarea" rows="9"></textarea>
                                <input type="submit" id="submit" class="button" style="font-size:12px;" value="Υποβολή">
                        
						</div>
					</form>
				</div>
			</div>
			<div class="c4 space-top">
				<h1 class="maintitle">
				<span><i class="icon-map-marker"></i> Τοποθεσία Κεντρικών Γραφείων</span>
				</h1>
				<dl>
					<dt>Σταδίου 29, Αθήνα 105 59</dt>
                    <dd>
                        <span>
                            Τηλέφωνο Επικοινωνίας: 213-1516649
                        </span>
                    </dd>
					<dd>Διεύθυνση Ηλεκτρονικού Ταχυδρομείου: <a href="more.html">pliroforisi-politi@ypakp.gr</a></dd>
				</dl>
			</div>
		</div>
</div><!-- end grid -->

<!-- FOOTER
================================================== -->
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/include/footer.php' ?>
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
