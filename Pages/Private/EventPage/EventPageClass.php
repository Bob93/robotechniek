<?php

class EventPageClass extends Index
{
	public function Start()
	{
		Index::$title = "ROBO Events";
	}

	public function GetContent()
	{
		$menu = $this->Datahandler->Menus->GetMenu(2);
		$value='';
		$content='
			
			<div id="scrollbox" class="mCustomScrollbar">
		<div class="col-md-12">   
			<div class="c-container">		
			<div class="content">                   
					<div class="contenttext">
						<div class="row">
							<div class="col-md-12">
							<div class="table-responsive">';
									$AllActiveEvents = $this->Datahandler->Events->GetAllActiveEvents();
									$content .= '<table border="1" class="table">';
									$content .= '<tr>
									<th>Eventnummer</th>
									<th>Evenementnaam</th>
									<th>Startdatum</th>
									<th>Eindatum</th>
									<th>Beschikbaar op</th>                                      
									</tr>';

									foreach($AllActiveEvents as $data){
										$content .=
										'<tr>
										<td> '.$data->eventID.'</td>
										<td> '.$data->eventName.' </td>
										<td> '.$data->startDate.' </td>
										<td> '.$data->endDate.' </td>
										<td> '.$data->showDate.' </td>
										</tr>';				
									}
									$content .= '</table>';
									
					$content .='</div>
							</div>
						</div>	
					</div>
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


?>