<?php
$jsEnabled = 0;
$content = '
	<img id="ROBO" width="150" class="robologo" src="logo2.png" alt="logo">
	<p>Deze website maakt gebruik van Javascript. Zet uw Javascript aan om deze site te kunnen weergeven</p>
';

//PHP Variable is being set to a javascript variable. If javascript is disabled, a page will be shown
echo '
<html>
	<head>
		<link href="stylesheet.css" rel="stylesheet">
	</head>
	<body>
		<script type="text/javascript" language="javascript">
			var val = '.$jsEnabled.';
			var jse = val + 1;
			alert(jse);
			'.$jsEnabled + 1 .'
		</script>
		<noscript>
			
		</noscript>
	</body>
</html>
'; 

echo $jsEnabled; 
?>