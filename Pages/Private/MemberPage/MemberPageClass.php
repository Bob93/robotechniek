<?php

class MemberPageClass extends Index
{
	public function Start()
	{
		Index::$title = "ROBO";
	}

	public function GetContent()
	{
		$members = $this->Datahandler->Members->GetAllMembers();
		$menu = $this->Datahandler->Menus->GetMenu(2);
		$comMenu = $this->Datahandler->Menus->GetMenu(3);
		$value='';
		$content='	<div class="col-md-9">   
						<div class="c-container">		
							<div class="content">                   
								<div class="contenttext">
									<div class="row">
									<ul class="memberLink">';
									foreach ($members as $member) {
										$pID = $this->Datahandler->Pages->GetPageID($member->memberID);
										$PageLink = $this->Datahandler->Pages->getPageLink($pID);
										$content .='<li><a href="' . $PageLink . '">'.$member->name.'</a></li>';
									}
		$content.='					</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>';
		$value .= $this->GetParent("Standard")->GetStandardPage($content);
		return $value;
	}
	
}


?><?php

