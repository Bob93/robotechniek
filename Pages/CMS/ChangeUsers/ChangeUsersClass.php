<?php
//ob_start();
	//Created by Jorrit Overeem
	class ChangeUsersClass extends index
	{
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
		}
		
		public function GetContent()
		{
			$content = '';

			$Allmembers = $this->Datahandler->Members->GetAllMembers();
			$eachMember = '';
			foreach($Allmembers as $memberdata){
				$eachMember .= '<option value="'.$memberdata->memberID.'">'.$memberdata->name.'</option>';
			}
			
			//Get all Users
			$Allusers = $this->Datahandler->Users->GetAllUsers();
			$userFromForEach = '<table>';
            $userFromForEach .= '<tr>
									<td>ID</td>
                                    <td>Email</td>
                                    <td>Last Login</td>
                                    <td>First Name</td>
									<td>Last Name</td>
									<td>Member</td>
									<td>User Type</td>
                                    <td>Active</td>
                                </tr>';
            foreach ($Allusers as $value) {
			
				$sMID = $this->Datahandler->Members->GetMemberByUserID($value->userID);
				$m = $this->Datahandler->Members->GetMemberByID($sMID);
				
				$a = ($value->active == 1) ? "Yes" : "No";
                $userFromForEach .= '<tr class="CMStr"> ';
                $userFromForEach .= "<td>" . $value->userID . "</td>";
                $userFromForEach .= "<td>" . $value->emailAdress . "</td>";
                $userFromForEach .= "<td>" . $value->lastLogin . "</td>";
                $userFromForEach .= "<td>" . $value->firstName . "</td>";
				$userFromForEach .= "<td>" . $value->lastName . "</td>";
				$userFromForEach .= "<td>" . $m->name . "</td>";
				$userFromForEach .= "<td>" . $value->userType . "</td>";
				$userFromForEach .= "<td>" . $a . "</td>";
                $userFromForEach .= "<td>" . '<a href="index.php?Page=CMS%2FChangeUsers&UserID='.$value->userID.'">'. "Edit User" .'</a>' . "</td>";
                $userFromForEach .= "</tr>";
            }
			$userFromForEach .= '</table>';

			//Add member
			$chars = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o'.'p','q','r','s','t','u','v','w','x',
			'y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W'.'X','Y','Z',
			'1', '2','3','4','5','6','7','8','9','0');
			$token = '';
			for ($i = 0; $i < 2; $i++){
				$rule = $chars[rand(0, count($chars) - 1)];
				$token .= $rule;
			}
			
			$message = '';
			if (isset($_POST['RegistreerGebruiker']))
			{
				if(!empty($_POST['email']))
                {
                    $eE = $this->Datahandler->Users->CheckExistingEmail($_POST['email']);
                    //True means: Email exists
                    //Else means: Email doesn't exists
					if($eE == 0)
                    {
                        if (isset($_POST['email'])
						and isset($_POST['permission'])
						and isset($_POST['firstName'])
						and isset($_POST['lastName'])
						and isset($_POST['sel_member']))
						{
                            $newMail = $_POST['email'];
							
							if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
								$password = $_POST['password'];
								$permission = $_POST['permission'];
								$firstName = $_POST['firstName'];
								$lastName  = $_POST['lastName'];
								$sel_member = $_POST['sel_member'];

								$this->Datahandler->Users->registerUser($newMail, $password, $permission, $firstName, $lastName, $sel_member);
								$this->Datahandler->Users->mailUser($token);
								echo '<div class="success">Inserted successfully!</div>';
								header('refresh:1;url=index.php?Page=CMS%2FChangeUsers');	
							}
							else{
							    echo '<div class="warning">The given email adres is not valid</div>';
								header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');	
							}
                        }else{
                            echo '<div class="warning">Please fill all input fields in</div>';
                        }
                    }else{
                        echo '<div class="warning">The email already exists, please use another email</div>';
                        header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');
                    }
                }else{
                    echo '<div class="warning">Please give an email address</div>';
                    header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');
                }


			}

			$content .= $this->GetParent("GUI")->GetCMSMenu();
			$content .= $this->GetParent("GUI")->CreateBox('User Registration',
				'<form name="addUser" method="post" action="index.php?Page=CMS%2FChangeUsers" >
				<label for="name">Email</label>
				<input type="text" name="email"/></br>
				<label for="member">Member</label>
				<select name="sel_member">
				'.$eachMember.'
				</select>
				<input type="hidden" name="password" value="'.$token.'"/></br>
				<label for="permissions">Select permission</label></br>
				<input type="radio" name="permission" value="2"/>Administrator</br>
				<input type="radio" name="permission" value="1"/>User</br>	
				<label for="firstName">Firstname</label>
				<input type="text" name="firstName"/></br>
				<label for="lastName">Lastname</label>
				<input type="text" name="lastName"/></br>
				<input type="submit" value="Register user" name="RegistreerGebruiker"/>			
			</form>');
			
			//Edit member
			if(isset($_GET['UserID'])) {
				$userID = $_GET['UserID'];
			} else {
				$userID = 1;
			}
			if (isset($_POST['EditUser'])){
                if(	!empty($_POST['mail']) && 
					!empty($_POST['permission']) && 
					!empty($_POST['firstName']) && 
					!empty($_POST['lastName']))
                {
                    $editMail 		= $_POST['mail'];
                    $editPermission = $_POST['permission'];
                    $editFirstName  = $_POST['firstName'];
                    $editLastName   = $_POST['lastName'];
					$editMember		= $_POST['selectedMember'];

                    $this->Datahandler->Users->EditUser($userID, $editMail, $editPermission, $editFirstName, $editLastName, $editMember);
                    echo '<div class="success">Changed successfully, reloading...</div>';
                    header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');

                }else{
                    echo '<div class="warning">Please fill all fields in</div>';
                    header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');
                }
			}
			
			$user = $this->Datahandler->Users->GetUserByID($userID);
				$val = '
					<form class="editUserForm" name="editUser" method="post" action="index.php?Page=CMS%2FChangeUsers&UserID='.$userID.'" >
					<label for="name">Change email</label>
					<input class="Bladii" type="text" value="'.$user->emailAdress.'" name="mail"/></br><br>
					<label>Member</label>
					<select name="selectedMember">';
				foreach ($Allmembers as $m)
				{
					$sMID = $this->Datahandler->Members->GetMemberByUserID($user->userID);
					if ($m->memberID == $sMID)
					{
						$val .= '<option selected="selected" value="' . $m->memberID . '">' . $m->name . '</option>';
					}
					else
					{
						$val .= '<option value="' . $m->memberID . '">' . $m->name . '</option>';
					}
				}
				$val .='</select><br><br>
                        <label for="street">Change Usertype</label><br>
                        <input type="radio" name="permission" value="2" />Administrator
                        <input type="radio" value="1" name="permission"/>Gebruiker</br>

                        <label for="firstName">Change Firstname</label>
                        <input type="text" value="'.$user->firstName.'" name="firstName"/></br>

                        <label for="lastName">Change Lastname</label>
                        <input type="text" value="'.$user->lastName.'" name="lastName"/></br>

					    <input type="submit" value="Edit" name="EditUser"/>
					</form>';	

					$content .= $this->GetParent("GUI")->CreateBox('Edit User', $val);					
			
			//Remove member
			if(isset($_GET['UserID'])) {
				$userID = $_GET['UserID'];
			} else {
				$userID = 1;
			}
			if (isset($_POST['removeUser'])){
                if(!empty($_POST['name'])){
                    $this->Datahandler->Users->RemoveUser($userID);
                    echo '<div class="success">Removed successfully, reloading...</div>';
					header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');
                }else{
                    echo '<div class="warning">Name is not filled in</div>';
					header('refresh:2;url=index.php?Page=CMS%2FChangeUsers');
                }

			}
			
			$user = $this->Datahandler->Users->GetUserByID($userID);
			$content .= $this->GetParent("GUI")->CreateBox('Remove User',
				'<form name="removeMember" method="post" action="index.php?Page=CMS%2FChangeUsers&UserID='.$userID.'" >
				<label for="name">Email</label>
				<input type="text" value="'.$user->emailAdress.'" name="name" readonly></br>
				<input type="submit" value="Remove user" name="removeUser"/>
			</form>');
			
			$content .= $this->GetParent("GUI")->CreateBox('Selected User',
			'<ul style="list-style: none;">
				<li> User ID: '.$user->userID.'</li>				
				<li> Email: '.$user->emailAdress.'</li>
				<li> User type: '.$user->userType.'</li>
				<li> Firstname: '.$user->firstName.'</li>
				<li> Lastname: '.$user->lastName.'</li>			
			</ul>
			<script>


                if ($(".warning").is(":visible"))
                  {
                      $(".warning").show();
                      setTimeout(function() {
                          $(".warning").slideUp();
                      }, 1800);
                  }
                if ($(".success").is(":visible"))
                  {
                      $(".success").show();
                      setTimeout(function() {
                          $(".success").slideUp();
                      }, 1800);
                  }
			</script>
			<style>
			    .warning{
			        background: red;
			        color: white;
			        text-align: center;
			        width: 100%;
			        top: -10px;
			    }
			    .success{
			        background: green;
			        color: white;
			        text-align: center;
			        width: 100%;
			        top: -10px;
			    }

			</style>
			');
						
			$content .= $this->GetParent("GUI")->CreateBox('Overview Users', $userFromForEach);
			
			return $content;
		}	
	}
?>