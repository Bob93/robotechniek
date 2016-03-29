<?php

class MemberClass
{
	private $DBH;

	public function __construct($dbh)
	{
		$this->DBH = $dbh;
	}

	/* New member info must be given through parameters and then inserts them into the database through a query */
	/* Not tested or accepted yet */
	/* Krijn van der Burg - 06-06-2014 */
	public function AddMember($newName, $newStreetNr, $newZipCode, $newPhoneNr)
	{
			$Stmt = $this->DBH->prepare('INSERT INTO members (Name, StreetNr, ZipCode, PhoneNr, Active)
										VALUES (:newName, :newStreetNr, :newZipCode, :newPhoneNr, "1")');
			$Stmt->bindParam(':newName', $newName);
			$Stmt->bindParam(':newStreetNr', $newStreetNr);
			$Stmt->bindParam(':newZipCode', $newZipCode);
			$Stmt->bindParam(':newPhoneNr', $newPhoneNr);
			$Stmt->execute();
	}
		
	/* MemberID of the member that is supposed to be deleted must be given through parameter and then put as unactive, the member will not be deleted only put on unactive / active = 0 */
	/* Not tested or accepted yet */
	/* Krijn van der Burg - 06-06-2014 */
	public function RemoveMember($MemberID){
			$Stmt = $this->DBH->prepare('UPDATE menuitems SET Active = "0"
										WHERE  MemberID = :MemberID');
			$Stmt->bindParam(':MemberID', $MemberID);
			$Stmt->execute();
	}
	/* Louis Verbeet - 13-06-2014  TESTED  */
	/*Here you can edit a member*/
	public function EditMember($memberID, $newName, $newStreetNr, $newZIP, $newPhoneNr)
	{
		/*Prepares statment to Update rows from table 'Members' where $memberID is the same*/
		$Stmt = $this->DBH->prepare('UPDATE members M 
									 SET M.Name = :newName,
									 M.StreetNr = :newStreetNr,
									 M.ZipCode = :newZIP,
									 M.PhoneNr = :newPhoneNr
									 WHERE  M.MemberID = :MemberID');
		/*
			Binds params $memberID, $newName, $newStreetNr, $newZip, $newPhoneNr
		*/
		$Stmt->bindParam(':MemberID', $memberID);
		$Stmt->bindParam(':newName', $newName);
		$Stmt->bindParam(':newStreetNr', $newStreetNr);
		$Stmt->bindParam(':newZIP', $newZIP);
		$Stmt->bindParam(':newPhoneNr', $newPhoneNr);

		$Stmt->execute(); //Executes prepared statement
		
	}
	/* Louis Verbeet - 13-06-2014 TESTED */
	/*Here you can select a member*/
	public function GetMemberByID($memberID)
	{
		$member = new Member();

		$Stmt = $this->DBH->prepare(
		"SELECT * FROM members
		 WHERE MemberID = :MemberID"
		);

		$Stmt->bindParam(':MemberID', $memberID);
		
		$Stmt->execute();
		// Set values in array
		while ($value = $Stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$member->memberID = $value['MemberID'];
			$member->name     = $value['Name'];
			$member->streetNr = $value['StreetNr'];
			$member->ZIP      = $value['ZipCode'];
			$member->phoneNr  = $value['PhoneNr'];
		}

		return $member;
	}

	//Osman Safa Kaya - 11 June 2014 created
	//Louis Verbeet - 12 june 2014 Fixed and tested.
	//Get all users by memberID and return all
	public function GetAllUsersByMemberID($memberID)
	{
		$users = array();
	$Stmt = $this->DBH->prepare(
	'SELECT * FROM users U'.
	' JOIN representatives R ON U.UserID = R.UserID'.
	' WHERE R.MemberID = :MemberID');

	$Stmt->bindParam(':MemberID', $memberID);

	while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($users, $row['UserID']);
		}
		return $users;

		/*
			Selects all userID`s at table users from a membergroup (MemberID).
			Creates an array from all the UserID`s.				
			returns array.
		*/
	}

	//Osman Safa Kaya - 11 June 2014
	//Updated on 2014-06-15 19:27:00 by Erwin Jansen
	public function GetAllMembers()
	{
		$members = array();

		$stmt = $this->DBH->prepare("SELECT * FROM members");
		$stmt->execute();
		//Based on the amount of members, we create an object in the loop below, and afterwards we push them in the 'members' array.
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$member = new Member();

			$member->memberID = $row['MemberID'];
			$member->name     = $row['Name'];
			$member->streetNr = $row['StreetNr'];
			$member->ZIP      = $row['ZipCode'];
			$member->phoneNr  = $row['PhoneNr'];

			//Adding each object to the array 'members'.
			array_push($members, $member);
		}
		//Returning the array
		return $members;
	}
	
	//Created by Joost Houtbeckers 20-06-2014
	public function GetLastInsertedID()
	{
		$member = new Member();

		$Stmt = $this->DBH->prepare("SELECT MAX(MemberID) as LastID FROM members");
		
		$Stmt->execute();
		// Set values in array
		while ($value = $Stmt->fetch(PDO::FETCH_ASSOC)) 
		{
			$member->LastID = $value['LastID'];
		}

		return $member;
	}
	
	public function GetMemberByUserID($userID)
	{
		$stmt = $this->DBH->prepare("SELECT * FROM representatives WHERE UserID = :userID");
		$stmt->bindParam(':userID', $userID);
		
		$stmt->execute();
		
		$memberID = 0;
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$memberID = $row["MemberID"];
			break;
		}
		
		return $memberID;
	}
}

class Member
{
	public $memberID;
	public $name;
	public $streetNr;
	public $ZIP;
	public $phoneNr;
}

?>