<?php

class BEHomeClass extends Index
{
	public function Start()
	{		
		Index::$title = "ROBO";
	}

	public function GetContent()
	{	$menu = $this->Datahandler->Menus->GetMenu(2);
		$value='';
		$content='

				<div id="scrollbox" class="mCustomScrollbar">';
		//loop schilderijen
				$menu = $this->Datahandler->Menus->GetMenu(2);
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
					</div>
					';
		}
		if ($_SESSION["loggedUser"]->userType == 2)
		{
			
				$content .='	
					<div class="col-md-6">
						<a href="index.php?Page=CMS%2FChangePages&page=getPages" class="PaintingLink"> 
							<div class="painting">
								<div class="s-content">
									<div class="s-title">
										<div class="s-ruler">
											<h1> CMS</h1>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>';
		}
				$content .='
					<div class="col-md-6">
						<a href="index.php?Page=Public%2FHomePage" class="PaintingLink">
							<div class="painting">
								<div class="s-content">
									<div class="s-title">
										<div class="s-ruler">
											<h1> Public</h1>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>';
		$value .= $this->GetParent("Standard")->GetStandardPage($content);
		return $value;
	}
	
}

?>