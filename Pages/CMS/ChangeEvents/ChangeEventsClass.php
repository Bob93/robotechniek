<?php
	//Jorrit Overeem 16-6-2014,
	//Updated by Jorrit on 19-6-2014
	class ChangeEventsClass extends index 
	{
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
			$this->addScript("datepicker/datepick");			
		}
		
		public function GetContent()
		{
			//Set current date by Louis
			date_default_timezone_set('Europe/Amsterdam');
			$date = date('Y:m:d h:i:s', time());
			
			//Get all Active Events

			$AllActiveEvents = $this->Datahandler->Events->GetAllActiveEvents();
			$eventActiveFromForEach = '<table border="1">';
            $eventActiveFromForEach .= '<tr>
                                        <th>Eventnummer</th>
                                        <th>Evenementnaam</th>
                                        <th>Startdatum</th>
                                        <th>Eindatum</th>
                                        <th>Beschikbaar op</th>                                      
                                   </tr>';

            foreach($AllActiveEvents as $data){
                $eventActiveFromForEach .=
				'<tr>
				<td><a href="index.php?Page=CMS%2FChangeEvents&EventID='.$data->eventID.'">'.$data->eventID.'</a></td>
				<td> '.$data->eventName.' </td>
				<td> '.$data->startDate.' </td>
				<td> '.$data->endDate.' </td>
				<td> '.$data->showDate.' </td>
				</tr>';				
            }
            $eventActiveFromForEach .= '</table>';
			
			//Get all Events
			$AllEvents = $this->Datahandler->Events->GetAllEvents();
			$eventFromForEach = '<table border="1">';
            $eventFromForEach .= '<tr>
                                        <th>Eventnumber</th>
                                        <th>Eventname</th>
                                        <th>Start date</th>
                                        <th>Eind date</th>
                                        <th>Show date</th>
                                        <th>Active event</th>
                                   </tr>';

            foreach($AllEvents as $data){
            	if($data->active === '1'){
				$eventFromForEach .=
					'<tr>
					<td><a href="index.php?Page=CMS%2FChangeEvents&EventID='.$data->eventID.'">'.$data->eventID.'</a></td>
					<td> '.$data->eventName.' </td>
					<td> '.$data->startDate.' </td>
					<td> '.$data->endDate.' </td>
					<td> '.$data->showDate.' </td>
					<td> Yes </td>
					</tr>';	
				} else {
				$eventFromForEach .=
					'<tr>
					<td><a href="index.php?Page=CMS%2FChangeEvents&EventID='.$data->eventID.'">'.$data->eventID.'</a></td>
					<td> '.$data->eventName.' </td>
					<td> '.$data->startDate.' </td>
					<td> '.$data->endDate.' </td>
					<td> '.$data->showDate.' </td>
					<td> No </td>
					</tr>';	
				}			
            }
            $eventFromForEach .= '</table>';
			$content = '';
			$memberID = 1;
			
			if(isset($_POST['memberID'])){
				$this->Datahandler->Events->AddEvent($this->SEC->Secure("xss", $memberID, "num", ""), $this->SEC->Secure("xss", $_POST['s_Date'], "num", ""), $this->SEC->Secure("xss", $_POST['e_Date'], "num", ""), $this->SEC->Secure("xss", $_POST['a_Date'], "num", ""), $this->SEC->Secure("xss", $_POST['eventName'], "string", ""));
				header('location: index.php?Page=CMS%2FChangeEvents');
			}
			$member = $this->Datahandler->Members->GetMemberByID($memberID);
			
			$content .= $this->GetParent("GUI")->GetCMSMenu();
			$content .= $this->GetParent("GUI")->CreateBox('Add new event',
				'<form name="addEvent" method="post" action="index.php?Page=CMS%2FChangeEvents" >
				<label for="name">Name event</label>
				<input type="text" name="eventName"></br>
				<label for="creator">Member ID</label>
				<input type="text" value="'.$member->memberID.'"name="memberID" readonly></br>
				<label for="s_Date">Start date</label>
				<input type="text" id="dp_AddStartDate" value="'.$date.'" name="s_Date"></br>
				<label for="e_Date">Eind date</label>
				<input type="text" id="dp_AddEndDate" value="'.$date.'" name="e_Date"></br>
				<label for="a_Date">Show date</label>
				<input type="text" id="dp_AddShowDate" value="'.$date.'" name="a_Date"></br>
				<input type="submit" value="Add new event"/>
				
				
			</form>');
			if(isset($_GET['EventID'])) {
				$eventID = $_GET['EventID'];
			} else {
				$eventID = 1;
			}
			
			if(isset($_POST['eventID'])) {
				$this->Datahandler->Events->EditEvent($this->SEC->Secure("xss", $_POST['eventID'], "num", ""), $this->SEC->Secure("xss", $_POST['new_startDate'], "num", ""), $this->SEC->Secure("xss", $_POST['new_endDate'], "num", ""), 
				$this->SEC->Secure("xss", $_POST['new_showDate'], "num", ""), $this->SEC->Secure("xss", $_POST['eventName'], ""), $this->SEC->Secure("xss", $_POST['active'], "num", ""));
				header('location: index.php?Page=CMS%2FChangeEvents&EventID='.$eventID.'');
			}
			
			$event = $this->Datahandler->Events->GetEvent($eventID);
			
			$content .= $this->GetParent("GUI")->CreateBox('Edit event',
			'<form name="EditEvent" method="post" action="index.php?Page=CMS%2FChangeEvents&EventID='.$eventID.'" >
				<label for="name">Event ID</label>
				<input type="text" name="eventID" value="'.$event->eventID.'" readonly></br>
				<label for="name">Event Name</label>
				<input type="text" name="eventName" value="'.$event->eventName.'"></br>
				<label for="s_Date">New Startdate</label>
				<input type="text" id="dp_EditStartDate" name="new_startDate" value="'.$event->startDate.'"></br>
				<label for="e_Date">New Einddate</label>
				<input type="text" id="dp_EditEndDate" name="new_endDate" value="'.$event->endDate.'"></br>
				<label for="a_Date">New Showdate</label>
				<input type="text" id="dp_EditShowDate" name="new_showDate" value="'.$event->showDate.'"></br>
				<label for="a_Date">Active</label>
				<input type="radio" name="active" value="1"/>Yes
				<input type="radio" value="2" name="active">No</br>
				<input type="submit" value="Edit event"/>
				
			</form>');					
			if(isset($_GET['EventID'])) {
				$eventID = $_GET['EventID'];
			} else {
				$eventID = 1;
			}
			
			if(isset($_POST['SelEventID'])) {
				$this->Datahandler->Events->RemoveEvent($_POST['member'], $_POST['SelEventID']);
				header('location: index.php?Page=CMS%2FChangeEvents&EventID='.$eventID.'');
			}
			
			$member = $this->Datahandler->Members->GetMemberByID($memberID);
			$event = $this->Datahandler->Events->GetEvent($eventID);
			
			$content .= $this->GetParent("GUI")->CreateBox('Remove Event', 
			'<form name="RemoveEvent" method="post" action="index.php?Page=CMS%2FChangeEvents&EventID='.$eventID.'" >
			<label for="name">Member ID</label>
			<input type="text" value="'.$member->memberID.'" name="member" readonly>	</br>			
			<label for="name">Event ID</label>
			<input type="text" value="'.$event->eventID.'" name="SelEventID" readonly>	</br>
			<input type="submit" value="Remove Event"/>
			</form>');
			
			if(isset($_GET['EventID'])) {
				$selectedEventID = $_GET['EventID'];
			} else {
				$selectedEventID = 1;
			}
			$e = $this->Datahandler->Events->GetEvent($selectedEventID);
			if($e->active == 1){			
			$content .= $this->GetParent("GUI")->CreateBox('Current Event',
			'<ul style="list-style: none;">
				<li> Evenement Name: '.$e->eventName.'</li>
				<li> Startdate: '.$e->startDate.'</li>
				<li> Eindate: '.$e->endDate.'</li>
				<li> Show date: '.$e->showDate.'</li>				
			</ul>');
			} else {
				$content .= $this->GetParent("GUI")->CreateBox('Current Event',
				'<p>This event '.$e->eventName.' is closed</p>');
			}
			
			$content .= $this->GetParent("GUI")->CreateBox('All Events', $eventFromForEach);
			$content .= $this->GetParent("GUI")->CreateBox('All Active Events', $eventActiveFromForEach);
			return $content;
		}
	}
?>