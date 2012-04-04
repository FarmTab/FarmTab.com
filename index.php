<html>

	<head>
		<title>Farm Tab</title>
		
		<link href="style.css" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
		
		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				
		<script type="text/javascript" >
			google.load("jquery", "1.7.1");
			google.load("jqueryui", "1.8.17");
		</script>
		
		<script type="text/javascript">
			
			$(document).ready(function() {
				$(".about").html( $(".home").html() );
				
				$("nav a").click(function () {
					$(".about").html( $("." + $(this).attr('name')).html() );
				});	
				
			})
			
		</script>
		
		<script type="text/javascript">
		
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-27846417-1']);
			_gaq.push(['_setDomainName', 'farmtab.net']);
			_gaq.push(['_trackPageview']);
			
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
		
		</script>
	
	</head>
	
	<body>
	
		<div id="wrap">
	
		<div id="header">
						
			<nav id="top-nav">
				<a href="#login">Login</a> | 
				<a href="#signup">Signup</a>
			</nav>
			
			<div id="banner">
				<img id="header-image" src="img/market01.jpg"/>
				<img id="logo" src="img/banner.png"/>
			</div>
			
					</div> <!-- END header -->
			
			<div id="content">
			
			<nav>
			
			<a href="#" name="home">Home</a>
			<a href="#" name="shoppers">For Shoppers</a>
			<a href="#" name="farmers">For Farmers</a>
			<a href="#" name="locations">Locations</a>
			<a href="#" name="contact">Contact</a>
			</nav>
			<div class="about"></div> <!-- END about -->	
			</div> <!-- END content -->
	

			<nav id="footer">
				<a href="privacy.html">Privacy Statement</a> | 
				<a href="terms.html">Terms of Service</a>
			</nav>
			
		</div> <!-- END wrap -->
		
		
		<div class="home tab">
	<p>A system to open a credit tab with a local farm. FarmTab is a mobile application stimulating local economies and fostering community development by enabling you to invest in local farmers. The FarmTab app allows users to micro-fund their favorite local farms and provides merchant farmers with new options for transacting business. The customer's initial investment in the Merchant Farmer starts a credit tab. After this investment, the customer receives goods and also gains access to the merchant's FarmTab mobile app page. The FarmTab page provides access to merchant inventory, product price and scheduled Farmer's Market appearances with maps.  
				</p>
				<p>
				Support farmers through a customer prepaid credit system. Follow us on twitter <a href="http://twitter.com/#!/FarmTab">@farmtab</a> for updates on our launch!
			</p>
		</div> <!-- END home-tab -->
			
			
		<div class="shop tab">
			<p>test of shop tab</p>
		</div> <!-- END shop tab -->
			
		<div class="farmers tab">
			<p>test of shop tab</p>
		</div> <!-- END shop tab -->
				
		<div class="farmers tab">
			<p>test of shop tab</p>
		</div> <!-- END shop tab -->	
	</body>
</html>