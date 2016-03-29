<?php
class Standard extends index {

	public function Start() {

		$this->AddScript("jQuery1_11");
		$this->AddScript("bootstrap/bootstrap.min");
		$this->AddScript("bootstrap/bootstrap");
		$this->AddScript("bootstrap/less");
		$this->AddScript("jquery.mCustomScrollbar.concat.min");

		$this->AddStyle("Bootstrap/bootstrap");
		$this->AddStyle("Bootstrap/bootstrap.min");
		$this->AddStyle("Bootstrap/bootstrap-theme");
		$this->AddStyle("Bootstrap/bootstrap-theme.min");
		$this->AddStyle("jquery.mCustomScrollbar");
		$this->AddStyle("stylesheet");
		$this->AddStyle("stylesheet - blue");
	
			//$this->ShowBrowserData();
	
		$this->MetaTags->SetDescription("Hier kunt u uw eigen gegevens zowel van anderen bekijken en navigeren door het leden gedeelte van de website");
		$this->MetaTags->addKeyWords(array("ROBO", "Infra", "Bedrijf", "Leden", "Members", "Menu", "Navigatie", "Evenementen", "Bestanden", "Home",));
		$this->MetaTags->setAuthor("ROBO Arnhem/Nijmegen");
	}

	public function GetStandardPage($addContent) {
		$menu = $this->Datahandler->Menus->GetMenu(2);
		$isLoggedIn = false;
		if (isset($_SESSION["loggedUser"]))	{
			$isLoggedIn = true;
		}
		else
		{
			header('Location: ../index.php?Page=CMS%2FCMSLogin');
		}

		$content = '<div class="container">
						<div class="row">
							<div class="col-md-3">
								<div id="robologo">
									<a href="http://www.facebook.com"><img id="facebooklogo" src="Images/fblogo.png" alt="facebook"></a>
									<img id="ROBO" src="Images/RoboLogoBE.png" alt="robo">
								</div>
							</div>
							<div class="col-md-9">
								<div id="header">';
									$content.= $this->Datahandler->Header->GetHeader(1);
		$content.='				</div>
								<nav class="navbar navbar-default" role="navigation">
									<div class="container-fluid">
										<div class="navbar-header">
											<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
											<span class="sr-only">Toggle navigation</span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											</button>
										</div>
										<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
											<ul class="nav navbar-nav">';
												foreach ($menu->menuItems as $item) {
													$content .= '<li><a href="' . $item->menuLink . '">' . $item->menuLabel . '</a></li>';
												}
		$content .= '						<br>
											</ul>
										</div>
									</div>
								</nav>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div id="portretbar">';
									if ($isLoggedIn){
										$content .= '<a href="Engine/LogoutPage.php"><button>Uitloggen</button></a>';
									}
		$content .= '			</div>
							</div>';
		$content .= $addContent;
		$content .='<div class="p-footer">
					<div class="p-right-text">';
		$content .= $this->Datahandler->Footer->getFooter(1);
		$content .='</div></div>';
		$content .='</div></div>';
		return $content;
	}
}
?>