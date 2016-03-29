<?php
class CMSLoginClass extends index
{
	public function Start()
	{
		
	}
	
	public function GetContent()
	{
		$content = '<div id="loginShade"></div>';
		if (!isset($_SESSION["loggedUser"]))
		{
			$content .= '<div id="loginDiv">
						<h1>Inloggen</h1>';
	
			$content .= $this->GetLoginForm($_GET["Page"]);
		
			$content .= '</div>';
		}
		else
		{
			$content .= '<div id="loginDiv">
							<h1>U bent al ingelogd!</h1>
						</div>';	
		}

		
		return $content;
	}
}
?>