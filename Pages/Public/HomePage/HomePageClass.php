<?php

class HomePageClass extends Index
{
	public function Start()
	{	
		Index::$title = "Home";
	}

	public function GetContent()
	{	//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
		$value = '';
		$content = '
		
				<div id="scrollbox" class="mCustomScrollbar">';
		//loop schilderijen
				$menu = $this->Datahandler->Menus->GetMenu(1);
				foreach ($menu->menuItems as $item) {
			$content .= '<div class="col-md-6">
						<a href="'.$item->menuLink.'" class="PaintingLink"> 
							<div class="painting">
								<div class="s-content">
									<div class="s-title">
										<div class="s-Ruler">
											<h1>'.$item->menuLabel.'</h1>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>';
		}
		
		
			
		$content .= '
			</div>
			
			<div class="col-md-4">  
				<div class="is-form">
					<h2 class="hide-mobile"> INLOGGEN </h2>';
					$content .= $this->GetLoginForm($_GET["Page"]);
					$content .= '
				</div>
			</div>
		
		</div>
		</div>';

		$value .= $this->GetParent("Standard")->GetStandardPage($content);

		return $value;
	}
	
}

?>