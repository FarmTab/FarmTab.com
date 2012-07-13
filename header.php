<html>

	<head>
		<title>Farm Tab</title>
		
		<link href="http://farmtab.com/style.css" rel="stylesheet" type="text/css" />
		<link href="http://farmtab.com/google-buttons.css" rel="stylesheet" type="text/css" />
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>		
		
		
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
				
		<script type="text/javascript" >
			google.load("jquery", "1.7.1");
		</script>
		
		<script type="text/javascript">
		
			var fadeTime = 2000;
			var delayTime = 3000;
			
			var imgCount = 1;
			
			$(document).ready(function() {
				$(<?= '"#' . $_GET['page'] . '-nav"' ?>).attr('class', 'selected');
				
				//setInterval(crossFade(),5000);
				crossFade();
				//setInterval(function(){alert("Hello")},1000);
			})
			
			function crossFade(){
				$('#header-image').delay(fadeTime, function(){$(this).attr('src', "img/banner/" + imgCount + ".jpg")});
				if(imgCount<2) imgCount ++;
				else imgCount = 0;
				console.log(imgCount);
				$('#header-imageB').fadeIn(fadeTime, function(){$(this).hide().attr('src', "img/banner/" + imgCount + ".jpg")});
				//setInterval(crossFade(),delayTime+fadeTime);
			}
			
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

				
			<div id="banner">
				<img id="header-image" src="http://farmtab.com/img/banner/0.jpg"/>
				<img id="header-imageB" src="http://farmtab.com/img/banner/1.jpg"/>
				<a href="?page=home"><img id="logo" src="http://farmtab.com/img/logo.png"/></a>
				
				<div id="main-nav">
					<a id="shoppers-nav" href="?page=shoppers" >For Shoppers</a>
					<a id="farmers-nav" href="?page=farmers" >For Farmers</a>
					<a id="contact-nav" href="?page=contact" >Contact</a>
				</div>	
				
				
			</div> <!-- END banner -->
			
				