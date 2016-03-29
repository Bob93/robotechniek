<?php

class ContactPageClass extends Index
{
	public function Start()
	{		
		Index::$title = "Contact";
	}

	public function GetContent()
	{//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
			$menu=$this->Datahandler->Menus->GetMenu(1);
			$c=$this->Datahandler->Contents->getContent(7);
			$value = '';
			$content= '

		<div class="col-md-9">   
			<div class="c-container">		
			<div class="content">                   
					<div class="contenttext">
					<div class="row">
							<div class="col-md-6">';
							$content.=$c->content;
							$content.='			</div>
						</div>
						<div class="row">	
							<div class="col-md-3">	
								<p>
									*Naam:
								</p>
								<p>
									*E-mail:
								</p>
								<p>
									Onderwerp:
								</p>
								<p>
									Vragen of opmerkingen:
								</p>
							</div>						
							<div class="col-md-4">
								<form action="mailto:sjoerdhageman@chello.nl" method=post method="post">
								<p>
									<input name="name" class="i-form-input" class="textbox" type="text"/>
								</p>
								<p>
									<input name="mail" class="i-form-input" class="textbox" type="text"/>
								</p>
								<p>
									<input name="about" class="i-form-input" class="textbox" type="text"/>
								</p>
								<p>
									<textarea name="area" class="textarea"></textarea>
								</p>
								<p>
									<input  class="redbutton" type="submit" value="Verstuur"/>
									<input class="redbutton" type="button" value="Reset"/>
								</p>
								</form>
							</div>
							<div class="col-md-5">
							<img src="Images/map.png" width="250">
							</div>	
						</div>
						<div class="row">
							<div class="col-md-5">
								*Deze velden zijn verplicht.
								<br>
								<br>
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