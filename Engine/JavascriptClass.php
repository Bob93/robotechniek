<?php
class JavascriptTest
{
	private $jsEnabled = '1';
	private $content = '
	<img id="ROBO" width="150" class="robologo" src="logo2.png" alt="logo">
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<p>Deze website maakt gebruik van Javascript. Zet uw Javascript aan om deze site te kunnen weergeven</p>
	</div>
	';

	public function SetJavascriptvariable($jsEnabled)
	{
		$result = ' 
		<html>
			<head>
				<link href="stylesheet.css" rel="stylesheet">
			</head>
			<body>
				<script type="text/javascript" language="javascript">
					var val = '.$jsEnabled.';
					var jse = val + 1;
					alert(jse);
				</script>
				<noscript>
					'.$content.'
				</noscript>
			</body>
		</html>
		';
		
		return $result;
	}
}
?>