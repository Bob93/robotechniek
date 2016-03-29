<?php
	//Created by Joost Houtbeckers
	//Updated by Jorrit Overeem 18-6-2014
	//v2 Updated by Jorrit/Joost
	class ChangeMembersClass extends index
	{
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
		}
		
		public function GetContent()
		{
			$content = '';
			
			//Get all Members
			$AllMembers = $this->Datahandler->Members->GetAllMembers();
			$memberFromForEach = '<table border=1>';
            $memberFromForEach .= '<tr>
                                        <th>Number</th>
                                        <th>Name</th>
                                        <th>Street number</th>
                                        <th>Zip code</th>
                                        <th>Phone</th>
                                   </tr>';

            foreach($AllMembers as $data){
            	
                $memberFromForEach .=
				'<tr>
				<td><a href="index.php?Page=CMS%2FChangeMembers&MemberID='.$data->memberID.'">'.$data->memberID.'</a></td>				
				<td> '.$data->name.' </td>
				<td> '.$data->streetNr.' </td>
				<td> '.$data->ZIP.' </td>
				<td> '.$data->phoneNr.' </td>
				</tr>';
            }
				$memberFromForEach .= '</table>';

			//Add member
			if (isset($_POST['naam'])){
				$this->Datahandler->Members->AddMember($this->SEC->Secure("xss", $_POST['naam'], "string", ""), $this->SEC->Secure("xss", $_POST['streetNr'], "num", ""), $this->SEC->Secure("xss", $_POST['zipCode'], "string", ""), $this->SEC->Secure("xss", $_POST['phone'], "num", ""));
				header('location: index.php?Page=CMS%2FChangeMembers');
			}
			
			$content .= $this->GetParent("GUI")->GetCMSMenu();

			$content .= $this->GetParent("GUI")->CreateBox('Add member',
				'<form name="addMembers" method="post" action="index.php?Page=CMS%2FChangeMembers" >
				<label for="name">Add member</label>
				<input type="text" name="naam"></br>
				<label for="street">Street number</label>
				<input type="text" name="streetNr"></br>
				<label for="zipcode">Zipcode</label>
				<input type="text" name="zipCode"></br>
				<label for="phone">Phone number</label>
				<input type="text" name="phone"></br>
				<input type="submit" value="Add new member"/>				
			</form>');
			
			//Edit member
			if(isset($_GET['MemberID'])) {
				$memberID = $_GET['MemberID'];
			} else {
				$memberID = 1;
			}
			if (isset($_POST['n_naam'])){
				$this->Datahandler->Members->EditMember($this->SEC->Secure("xss", $memberID, "num", ""), $this->SEC->Secure("xss", $_POST['n_naam'], "string", ""), 
				$this->SEC->Secure("xss", $_POST['n_streetNr'], "num", ""), $this->SEC->Secure("xss", $_POST['n_zipCode'], "string", ""), $this->SEC->Secure("xss", $_POST['n_phone'], "num", ""));
				header('location: index.php?Page=CMS%2FChangeMembers&MemberID='.$memberID.'');

			}
			$member = $this->Datahandler->Members->GetMemberByID($memberID);
			$content .= $this->GetParent("GUI")->CreateBox('Edit member',
				'<form name="addMembers" method="post" action="index.php?Page=CMS%2FChangeMembers&MemberID='.$memberID.'" >
				<label for="name">Membername</label>
				<input type="text" value="'.$member->name.'" name="n_naam"></br>
				<label for="street">Street Nr</label>
				<input type="text" value="'.$member->streetNr.'" name="n_streetNr"></br>
				<label for="zipcode">Zipcode</label>
				<input type="text" value="'.$member->ZIP.'" name="n_zipCode"></br>
				<label for="phone">Phonenumber</label>
				<input type="text" value="'.$member->phoneNr.'" name="n_phone"></br>
				<input type="submit" value="Edit member"/>				
			</form>');
			
			//Remove member
			if(isset($_GET['MemberID'])) {
				$memberID = $_GET['MemberID'];
			} else {
				$memberID = 1;
			}
			if (isset($_POST['name'])){
				$this->Datahandler->Members->RemoveMember($memberID);
				header('location: index.php?Page=CMS%2FChangeMembers&MemberID='.$memberID.'');
			}
			
			$m = $this->Datahandler->Members->GetMemberByID($memberID);
			$content .= $this->GetParent("GUI")->CreateBox('Remove member',
				'<form name="removeMember" method="post" action="index.php?Page=CMS%2FChangeMembers&MemberID='.$memberID.'" >
				<label for="name">Membername</label>
				<input type="text" value="'.$m->name.'"name="name" readonly></br>
				<input type="submit" value="Verwijder lid"/>				
			</form>');
			
			$m = $this->Datahandler->Members->GetMemberByID($memberID);			
			$content .= $this->GetParent("GUI")->CreateBox('Selected member',
			'<ul style="list-style: none;">
				<li> Member No: '.$m->memberID.'</li>				
				<li> Membername: '.$m->name.'</li>
				<li> Street number: '.$m->streetNr.'</li>
				<li> Zipcode: '.$m->ZIP.'</li>
				<li> Phonenumber: '.$m->phoneNr.'</li>		
			</ul>');
						
			$content .= $this->GetParent("GUI")->CreateBox('All active members', $memberFromForEach);
			
			return $content;
		}	
	}
?>