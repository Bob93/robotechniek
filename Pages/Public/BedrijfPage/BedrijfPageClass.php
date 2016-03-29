<?php

class BedrijfPageClass extends Index
{
	public function Start()
	{		
		Index::$title = "Bedrijven";

		$this->MetaTags->setDescription("Hier kunt u alle bedrijven die staan aangemeld bij robo vinden en verdere informatie vinden");
		$this->MetaTags->addKeyWords(array("Bedrijf", "Bedrijven", "Aangemeld", "Informatie", "Info", "Organisatie", "lid", "leden"));
		$this->MetaTags->setAuthor("ROBO");
	}

	public function GetContent()
	{//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
			$menu=$this->Datahandler->Menus->GetMenu(1);
			$c=$this->Datahandler->Contents->getContent(6);
			$value = '';
			$content= '

		<div class="col-md-9">   
			<div class="c-container">		
			<div class="content">                   
					<div class="contenttext">
					<div class="row">
					<div id="scrollbox" class="mCustomScrollbar">
							<div class="col-md-12">';
							$content.=$c->content;
							$content.='	</div>
						</div>
						</div>
				</div>
			</div>
		</div>
		</div>
	</div>
	</div>
	';
			$value .= $this->GetParent("Standard")->GetStandardPage($content);
			return $value;
		
	}
	
	
}

?>