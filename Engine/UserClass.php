<?php
class UserClass
{
	private $DBH;
	
	public function __construct($dbh){
		$this->DBH = $dbh;
	}
	//Jorrit Overeem 13-6-2014 9.
	//-------------------------//
	//Updated by Osman 23-6-2014
	// A function used to create users and assign them to a member.
	public function RegisterUser($email, $password, $userTypeID, $firstName, $lastName, $memberID) 
	{
		try{
			$stmntUser = $this->DBH->prepare('INSERT INTO users (Email_Adres, UserPassword, UserTypeID, FirstName, LastName, Active)
			VALUES(:email, :password, :userTypeID, :firstName, :lastName, :active)'); 
			$active = 1;
			$stmntUser->bindParam(':email', $email);
			$stmntUser->bindParam(':password', $password);
			$stmntUser->bindParam(':userTypeID', $userTypeID);
			$stmntUser->bindParam(':firstName', $firstName);
			$stmntUser->bindParam(':lastName', $lastName);
			$stmntUser->bindParam(':active', $active);
			$stmntUser->execute();

			$stmt = $this->DBH->prepare('SELECT MAX(userID) FROM users ORDER BY userID DESC LIMIT 0,1');
			$stmt->execute();
			while($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$GetLastInsertedUser = $row['MAX(userID)'];
			}

			$stmntRep = $this->DBH->prepare('INSERT INTO representatives (UserID, MemberID) VALUES(:u_id, :m_id)');
			$stmntRep->bindParam(':u_id', $GetLastInsertedUser);
			$stmntRep->bindParam(':m_id', $memberID);	
			$stmntRep->execute();

		}catch(PDOException $ex){
			return "Error!!!" . $ex->getMessage();
		}
	}	

	//Jorrit Overeem 13-6-2014 10.34
	//Updated by Jorrit on 19-6-2014 0.00
	//This function sends a mail to newly registered users.
	public function MailUser($token) {
		
		$stmntMail = $this->DBH->prepare('SELECT Email_Adres, LastName FROM users WHERE UserPassword = :token');
		$stmntMail->bindParam(':token', $token);
		$row = $stmntMail->fetch(PDO::FETCH_ASSOC);
		
		$email = $row['Email_Adres'];
		$user = $row['LastName'];
		
		$from = "overeemjorrit@gmail.com"; // sender
		$receiver = $email; 
	    $subject = 'Instellen wachtwoord';
	    $message = '<p>Welkom '.$user.'</p>
	    <p>U ben als gebruiker geregistreerd bij ROBO, door op deze <a href="passwordform.php?token='.$token.'>link</a> te klikken kunt u 
		zelf eenmailig een wachtwoord instellen</p>';
	    // send mail
		
		if(mail($receiver,$subject,$message,"From: $from\n")) {
			$message = 'Mail sent to '.$receiver;
			return $message;
		} else {
			$message = 'Mail not sent';
			return $message;
		}
	}
	
	//Jorrit Overeem 13-6-2014 9:57
	//Update password after the mail has been opened
	public function RegisterPass($password, $oldPass) {			
		$stmntUserID = $this->DBH->prepare('SELECT UserID FROM users WHERE UserPassword = :token');
		$stmntUserID->bindParam(':token', $oldPass);
		$stmntUserID->execute();
		$row = $stmntUserID->fetch(PDO::FETCH_ASSOC);
		$hashedPass = hash('sha512', $password);
		$userID = $row['userID'];
					$stmntRegPass = $this->DBH->prepare('UPDATE users SET UserPassword = :password WHERE UserID = :userID');
		$stmntRegPass->bindParam(':userID', $userID);
		$stmntRegPass->bindParam(':password', $hashedPass);
		$stmntRegPass->execute();
	}
	
	//Updated on 2014-06-23 23:32:00 by Erwin Jansen, moved the login part to Login.php
	//Register the last time the users log in.
	public function LoginUser(&$userID) {		
		//Jorrit Overeem 13-6-2014 11:14
		/*Datum tijd van laatste login registeren in database:*/
		$lastLogin = date('Y/m/d H:i:s');
		$stmt = $this->DBH->prepare('UPDATE users SET Last_Loging = :log WHERE UserID = :userID');
		$stmt->bindParam(':log', $lastLogin);
		$stmt->bindParam(':userID', $userID);
		$stmt->execute();
	}
	
	//set the user inactive
	public function RemoveUser($userID) {
		$stmt = $this->DBH->prepare('UPDATE users set active=0 WHERE userID=:u');
		$stmt->bindParam(':u', $userID);

		$stmt->execute();
	}

	//Edit the information of the user.
	public function EditUser($userID, $email, $permission, $firstName, $lastName, $memberID) {
	
		$stmt = $this->DBH->prepare('UPDATE users
											set Email_Adres = :e,  UserTypeID = :t, FirstName = :f, LastName = :l
											  WHERE UserID = :u;
											  
									 UPDATE representatives
									 SET MemberID = :m WHERE UserID = :u;');
		$stmt->bindParam(':u', $userID);
		$stmt->bindParam(':e', $email);
		$stmt->bindParam(':t', $permission);
		$stmt->bindParam(':f', $firstName);
		$stmt->bindParam(':l', $lastName);
		$stmt->bindParam(':m', $memberID);
		
		$stmt->execute();
	}
		
	//Created by Jorrit Overeem 12-6-2014 @ 18.43
	//Change the users password
	public function ChangePassword($user, $email, $password) {
		$changedPass = hash('sha512', $password);
		$stmntPass = $this->DBH->prepare("UPDATE user SET UserPassword = :changedpass WHERE UserName = :user AND Email_Adres = :email");
		$stmntPass->bindParam(':user', $user);
		$stmntPass->bindParam(':email', $email);
		$stmntPass->bindParam(':changedpass', $changedPass); 
		$stmntPass->execute();			
	}
		
	//Select all the users that are representatives of the member with $memberID
	public function GetAllUserIDsByMemberID($memberID)
	{
		$userIDs = array();
	
		$stmt = $this->DBH->prepare("SELECT * FROM representatives WHERE MemberID = :memberID");
		$stmt->bindParam(":memberID", $memberID);
		
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			array_push($userIDs, $row["UserID"]);
		}
		
		return $userIDs;
	}
	
	//Jorrit 19-6-2014
	//Get the Rights of the user based on their Email adress.
	public function GetTypeIDByEmail($emailAdress) {
		$email = Array();
		$stmtUserTypeID = $this->DBH->prepare("SELECT UserTypeID From users WHERE Email_Adress = :username");
		$stmtUserTypeID->bindParam(":username", $emailAdress);
		$stmtUserTypeID->execute;
		while($row = $stmtUserTypeID->fetch(PDO::FETCH_ASSOC)){
			$user = new User();
			$user->emailAdress = $row["Email_Adres"];
			array_push($email, $user);
		}	
	}
	
	//get all user information based on the ID
	public function GetUserByID($userID)
	{	
		$user = new User();
	
		$stmt = $this->DBH->prepare("SELECT UserID, UserType, Email_Adres, FirstName, LastName FROM users U 
		INNER JOIN usertypes Ut ON U.UserTypeID = Ut.UserTypeID WHERE U.UserID = :userID");
		$stmt->bindParam(":userID", $userID);
		
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$user->userID = $row["UserID"];
			$user->emailAdress = $row["Email_Adres"];
			$user->userType = $row["UserType"];
			$user->firstName = $row["FirstName"];
			$user->lastName = $row["LastName"];
		}
		
		return $user;
	}
	
	//Get all the users, and all user information
	public function GetAllUsers()
	{
		$users = array();
	
		$stmt = $this->DBH->prepare("SELECT UserID, UserType, Email_Adres, FirstName, LastName, Last_Loging, Active FROM users U 
		INNER JOIN usertypes Ut ON U.UserTypeID = Ut.UserTypeID AND Active = 1 ORDER BY UserID");
		
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$user = new User();
			$user->userID = $row["UserID"];
			$user->emailAdress = $row["Email_Adres"];
			$user->userType = $row["UserType"];
			$user->firstName = $row["FirstName"];
			$user->lastName = $row["LastName"];
			$user->memberName = $row["FirstName"];
			$user->lastLogin = $row["Last_Loging"];
			$user->active   = $row["Active"];
			array_push($users, $user);
		}
		
		return $users;
	}
	
	//Created on 2014-06-23 23:32:00 by Erwin Jansen. Returns object User with username and password
	//Select a user based on the username
	public function GetUserByUsername(&$userName, &$passWord)
	{
		$stmt = $this->DBH->prepare('SELECT * FROM users where Email_Adres = :userName and UserPassword = :passWord');
		$stmt->bindParam(":userName", $userName);
		$stmt->bindParam(":passWord", $passWord);
		
		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$user = new User();
			
			$user->userID = $row["UserID"];
			$user->emailAdress = $row["Email_Adres"];
			$user->userPassword = $row["UserPassword"];
			$user->userType = $row["UserTypeID"];
			$user->firstName = $row["FirstName"];
			$user->lastName = $row["LastName"];
			
			return $user;
		}
	}
	
	//Check if the Email already exists
	public function CheckExistingEmail($email) {
		$stmt = $this->DBH->prepare('SELECT * FROM users where Email_Adres = :email LIMIT 1');
		$stmt->bindParam(':email', $email);
		
		$stmt->execute();
		
		$count = 0;
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			++$count;
		}
		
		return $count;
	}
}

?>