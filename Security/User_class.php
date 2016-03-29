<?php
	class Users 
	{
		private $DBH;
		
		public function __construct($dbh){
			$this->DBH = $dbh;
		}
		//Jorrit Overeem 13-6-2014 9.35
		public function registerUser($email, $firstName, $lastName, $userTypeID) {
			
			$stmntUser = $this->DBH->prepare('INSERT INTO Users (Email_Adres, FirstName, LastName, UserTypeID) 
			VALUE(:email, :firstName, :lastName, :userTypeID)'); 
			$stmntUser->bindParam(':email', $email);
			$stmntUser->bindParam(':firstname', $firstName);
			$stmntUser->bindParam(':lastName', $lastName);
			$stmntUser->bindParam(':userTypeID', $userTypeID);
			$stmntUser->execute();
		}	
		//Jorrit Overeem 13-6-2014 10.34
		public function mailUser($hash) {
			
			$stmntMail = $this->DBH->prepare('SELECT Email_Adres, LastName FROM Users WHERE UserPassword IS NULL');
			$row = $stmntMail->fetch(PDO::FETCH_ASSOC);
			
			$email = $row['Email_Adres'];
			$user = $row['LastName'];
			
			$from = "oveeemjorrit@gmail.com"; // sender
			$reciever = $email; 
		    $subject = 'Instellen wachtwoord';
		    $message = '<p>Welkom '.$user.'</p>
		    <p>U ben als gebruiker geregistreerd bij ROBO, door op deze <a href="passwordform.php?token='.$hash.'>link</a> te klikken kunt u 
			zelf eenmailig een wachtwoord instellen</p>';
		    // send mail
			
			if(mail($reciever,$subject,$message,"From: $from\n")) {
				$message = 'Mail sent to '.$reciever;
				return $message;
			} else {
				$message = 'Mail not sent';
				return $message;
			}
		}
		
		//Jorrit Overeem 13-6-2014 9:57
		public function registerPass($password) {

			$stmntUserID = $this->DBH->prepare('SELECT UserID FROM Users WHERE UserPassword IS NULL');
			$stmntUserID->execute();
			$row = $stmntUserID->fetch(PDO::FETCH_ASSOC);
			$hashedPass = hash('sha512', $password);
			$userID = $row['userID'];
			
			$stmntRegPass = $this->DBH->prepare('UPDATE Users SET UserPassword = :password WHERE UserID = :userID');
			$stmntRegPass->bindParam(':userID', $userID);
			$stmntRegPass->bindParam(':password', $hashedPass);
			$stmntRegPass->execute();
		}
		
		public function loginUser($email, $password) {
			/*check anti-csrf token*/
			$SEC->CSRFCheck($email);
			$SEC->CSRFCheck($password);
			
			/* Check wachtwoord/gebruikersnaam combinatie*/
			$pass = hash('sha512', $password);
			
			$stmntUser = $this->DBH->prepare('SELECT count(*) FROM Users WHERE Email_Adres = :username AND UserPassword = :password;'); 
			$stmntUser->bindParam(':username', $email);
			$stmntUser->bindParam(':password', $pass);
			
			$stmtUser->execute();
			
			$count = $stmtUser->fetchColumn();
			
			if($count == '1'){
				/* Sla de gebruikernaam en token op in een sessie variabele */
				$_SESSION['username'] = $email;
				
				//Jorrit Overeem 13-6-2014 11:14
				/*Datum tijd van laatste login registeren in database:*/
				$lastLogin = date('Y/m/d H:i:s');
				$stmt = $this->DBH->prepare('UPDATE Users SET Last_Loging = :log WHERE Email_Adress = :email');
				$stmt->bindParam(':log', $lastLogin);
				$stmt->bindParam(':email', $email);
				$stmt->execute();
				
				return true;
			}else{
				return false;
			}	
		}
		
		public function removeUser($user) {
			$stmt = $this->DBH->prepare('UPDATE users set active=0 WHERE userID=:u');
			$stmt->bindParam(':u', $user);

			$stmt->execute();
			
			$stmt = $this->DBH->prepare('SELECT Active FROM users WHERE userID=:u');
			$stmt->bindParam(':u', $user);

			$stmt->execute();
			
			$activeTable;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$activeTable = $row["Active"];
			}

			if($ativeTable == 0)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function changePermissions($user, $permission) {
			$stmt = $this->DBH->prepare('UPDATE users set UserTypeID=:p WHERE userID=:u');
			$stmt->bindParam(':u', $user);
			$stmt->bindParam(':p', $permission);

			$stmt->execute();
			
			$stmt = $this->DBH->prepare('SELECT UserTypeID FROM users WHERE userID=:u');
			$stmt->bindParam(':u', $user);

			$stmt->execute();
			
			$UserTypeID;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$UserTypeID = $row["UserTypeID"];
			}

			if($UserTypeID == $permission)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		//Created by Jorrit Overeem 12-6-2014 @ 18.43
		public function changePassword($user, $email, $password) {
			$changedpass = hash('sha512', $password);
			$stmntPass = $this->DBH->prepare("UPDATE user SET UserPassword = :changedpass WHERE UserName = :user AND Email_Adres = :email");
			$stmntPass->bindParam(':user', $user);
			$stmntPass->bindParam(':email', $email);
			$stmntPass->bindParam(':changedpass', $changedpass); 
			$stmntPass->execute();
			
			
		}
		
	}
?>