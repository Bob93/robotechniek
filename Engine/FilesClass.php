<?php

class FilesClass
{
	private $DBH;

	public function __construct($dbh) {
		$this->DBH = $dbh;
	}
	
	public function AddFileLink($memberID, $contentID, $fileName, $fileExt, $filePath) {
	
		$typeID = $this->GetFileTypeIDByType($fileExt);
		if ($typeID == null)
		{
			$this->AddFileType($fileExt);
			$typeID = $this->GetFileTypeIDByType($fileExt);
		}		
		
		$stmt = $this->DBH->prepare('INSERT INTO files(FileTypeID, FileLink, FileName, MemberID, Active)
									 VALUES (:fileTypeID, :fileLink, :fileName, :memberID, 1)');
		$stmt->bindParam(':fileTypeID', $typeID);
		$stmt->bindParam(':fileLink', $filePath);
		$stmt->bindParam(':fileName', $fileName);
		$stmt->bindParam(':memberID', $memberID);
		$stmt->execute();
		
		if ($contentID != 0)
		{
			$newFileID = 0;
			
			$stmt = $this->DBH->prepare('SELECT FileID FROM files
										 WHERE FileLink = :fileLink');
			$stmt->bindParam(':fileLink', $filePath);
			
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$newFileID = $row["FileID"];
			}
		
			$stmt = $this->DBH->prepare('INSERT INTO contentfiles(ContentID, FileID)
										 VALUES (:contentID, :FileID)');
			$stmt->bindParam(':contentID', $contentID);
			$stmt->bindParam(':FileID', $newFileID);
			
			$stmt->execute();
		}
	}
	
	public function AddFileType($fileType)
	{
		$stmt = $this->DBH->prepare('INSERT INTO filetype(Type) VALUES (:fileType)');
		$stmt->bindParam(':fileType', $fileType);
		
		$stmt->execute();
	}
	
	public function RemoveFile($memberID, $filePath) {
		if (RemoveFileLink($memberID, $filePath)) {
			print ("Deleted!");
		}
		else {
			print ("Error!");
		}	
	}
	
	public function ForceRemoveFile($filePath) {
		if ($this->ForceRemoveFileLink($filePath)) {
			return true;
		}
		else {
			return false;
		}	
	}
	
	private function ForceRemoveFileLink($filePath) {
		//Remove row where MemberID = $MemberID and filePath = $filePath
		$stmt = $this->DBH->prepare('UPDATE files SET Active = 0 WHERE FileLink = :filePath;');
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		//Check if the row still exists
		$stmt = $this->DBH->prepare('SELECT Active FROM files WHERE FileLink = :filePath;');		
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		$active = 1;
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$active = $row["Active"];
		}
		if($active == 0){
			return true;
		}else{
			return false;
		}	
	}
	
	private function RemoveFileLink($memberID, $filePath) {
		//Remove row where MemberID = $MemberID and filePath = $filePath
		$stmt = $this->DBH->prepare('UPDATE files SET Active = 0 WHERE MemberID = :memberID AND filePath = :filePath;');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		//Check if the row still exists
		$stmt = $this->DBH->prepare('SELECT Active FROM files WHERE MemberID = :memberID AND filePath = :filePath;');		
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		$count = $stmt->fetchColumn();
		
		if($count > 0){
			return true;
		}else{
			return false;
		}	
	}
	
	public function GetFileLink($memberID, $filePath) {	
		$fL = new FileLink();
		
		$stmt = $this->DBH->prepare('SELECT * FROM files WHERE MemberID = :memberID AND FileLink = :filePath');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fL->fileID = $row["FileID"];
			$fL->memberID = $row["MemberID"];
			$fL->fileName = $row["FileName"];
			$fL->fileTypeID = $row["FileTypeID"];
			$fL->fileExt = $this->GetFileTypeByID($fL->fileTypeID);
			$fL->filePath = $row["FileLink"];
		}
		return $fL;
	}
	
	public function ForceGetFileLink($filePath) {	
		$fL = new FileLink();
		
		$stmt = $this->DBH->prepare('SELECT * FROM files WHERE Active = 1 AND FileLink = :filePath');
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fL->fileID = $row["FileID"];
			$fL->memberID = $row["MemberID"];
			$fL->fileName = $row["FileName"];
			$fL->fileTypeID = $row["FileTypeID"];
			$fL->fileExt = $this->GetFileTypeByID($fL->fileTypeID);
			$fL->filePath = $row["FileLink"];
		}
		return $fL;
	}
	
	public function EditFileName($memberID, $filePath, $newFileName) {
		//Update FileName to $newFileName where MemberID is the same as $memberID and filePath is the same as $filePath
		$stmt = $this->DBH->prepare('UPDATE files SET FileName = :newFileName WHERE MemberID = :memberID AND filePath = :filePath;');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->bindParam(':newFileName', $newFileName);
		$stmt->execute();
		
		//Check if the filename is changed
		$stmt = $this->DBH->prepare('SELECT FileName FROM files WHERE MemberID = :memberID AND filePath = :filePath;');		
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);		
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$fileNameTest = $row["FileName"];
		}
		if($newFileName != $fileNameTest){
			return false;
		}else{
			return true;
		}
		
	}
	
		public function EditFileExt($memberID, $filePath, $newFileExt) {		
		//select TypeID
		$typeID = $this->GetFileTypeIDByType($fileExt);
		
		//update value of FileTypeID
		$stmt = $this->DBH->prepare('UPDATE files SET FileTypeID=:typeID WHERE MemberID=:memberID AND filePath=:filePath');
		$stmt->bindParam(':typeID', $typeID);
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		//test if FileTypeID did not change
		$fileTypeID = 0;
		
		$stmt = $this->DBH->prepare('SELECT FileTypeID FROM files WHERE MemberID=:memberID AND filePath=:filePath');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fileTypeID = $row["FileTypeID"];
		}
		if($fileTypeID == $typeID) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function EditFileNameAndExt($memberID, $filePath, $newFileName, $newFileExt) {
		//select TypeID
		$typeID = $this->GetFileTypeIDByType($fileExt);
		
		// update values of FileTypeID and FileName
		$stmt = $this->DBH->prepare('UPDATE files SET FileTypeID=:typeID, FileName=:newFileName WHERE MemberID=:memberID AND filePath=:filePath');
		$stmt->bindParam(':typeID', $typeID);
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->bindParam(':newFileName', $newFileName);
		$stmt->execute();
		
		// select FileTypeID and FileName
		
		$stmt = $this->DBH->prepare('SELECT FileTypeID, FileName FROM files WHERE MemberID=:memberID AND filePath=:filePath');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':filePath', $filePath);
		$stmt->execute();
		
		// test if FileTypeID or FileName did not change
		$fileTypeID = 0;
		$fileName = 0;
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fileTypeID = $row["FileTypeID"];
			$fileName = $row["FileName"];
		}
		
		if($fileTypeID == $typeID || $fileName == $newFileName) {
			return false;
		}
		else {
			return true;
		}
	}
	
	public function GetAllFileLinksByMemberID($memberID) {
		$fileLinks = Array();
	
		$stmt = $this->DBH->prepare('SELECT * FROM files WHERE MemberID = :memberID and Active = 1');
		$stmt->bindParam(':memberID', $memberID);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fL = new FileLink();
			
			$fL->fileID = $row["FileID"];
			$fL->memberID = $row["MemberID"];
			$fL->fileName = $row["FileName"];
			$fL->fileTypeID = $row["FileTypeID"];
			$fL->fileExt = $this->GetFileTypeByID($fL->fileTypeID);
			$fL->filePath = $row["FileLink"];
			
			array_push($fileLinks, $fL);
		}
		return $fileLinks;
	}
	
	public function GetAllFileLinks() {
		$fileLinks = Array();
	
		$stmt = $this->DBH->prepare('SELECT * FROM files WHERE Active = 1');
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$fL = new FileLink();
			
			$fL->fileID = $row["FileID"];
			$fL->memberID = $row["MemberID"];
			$fL->fileName = $row["FileName"];
			$fL->fileTypeID = $row["FileTypeID"];
			$fL->fileExt = $this->GetFileTypeByID($fL->fileTypeID);
			$fL->filePath = $row["FileLink"];
			
			array_push($fileLinks, $fL);
		}
		return $fileLinks;
	}
	
	public function GetFileTypeByID($fileTypeID) {
		$stmt = $this->DBH->prepare('SELECT Type FROM filetype WHERE TypeID = :typeID');
		$stmt->bindParam(':typeID', $fileTypeID);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $row["Type"];
		}	
	}
	
	public function GetFileTypeIDByType($type) {
		$stmt = $this->DBH->prepare('SELECT TypeID FROM filetype WHERE Type = :type');
		$stmt->bindParam(':type', $type);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			return $row["TypeID"];
		}	
	}
	
	//Returns array with type Object (FileType) Last Update: 2014-06-15 19:10:00 by Erwin Jansen
	public function GetAllFileLinksFromContentID($contentID) {
		$fileLinks = array();
		
		$stmt = $this->DBH->prepare("SELECT * FROM files JOIN contentfiles on files.fileid = contentfiles.FileID WHERE contentfiles.ContentID = :contentID and Active = 1");
		$stmt->bindParam(":contentID", $contentID);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$fileLink = new FileLink();
			
			$fileLink->fileID = $row["FileID"];
			$fileLink->memberID = $row["MemberID"];
			$fileLink->fileName = $row["FileName"];
			$fileLink->fileTypeID = $row["FileTypeID"];
			$fileLink->fileExt = $this->GetFileTypeByID($fileLink->fileTypeID);
			$fileLink->filePath = $row["FileLink"];
			
			array_push($fileLinks, $fileLink);
		}
		
		return $fileLinks;
	}
	
	public function GetAllFileLinksFromContentIDAndMemberID($contentID, $memberID)
	{
		$fileLinks = array();
	
		$stmt = $this->DBH->prepare(   "SELECT * FROM files
										JOIN contentfiles
										ON files.FileID = contentfiles.FileID
										WHERE contentfiles.ContentID = :contentID
										AND files.MemberID = :memberID"
									);
									
		$stmt->bindParam(":contentID", $contentID);
		$stmt->bindParam(":memberID", $memberID);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$fileLink = new FileLink();
			
			$fileLink->fileID = $row["FileID"];
			$fileLink->memberID = $row["MemberID"];
			$fileLink->fileName = $row["FileName"];
			$fileLink->fileTypeID = $row["FileTypeID"];
			$fileLink->fileExt = $this->GetFileTypeByID($fileLink->fileTypeID);
			$fileLink->filePath = $row["FileLink"];
			$fileLink->contentID = $row["ContentID"];
			
			array_push($fileLinks, $fileLink);
		}	
			
		return $fileLinks;
	}
	
	public function GetAllFileLinkIDsFromContentID($contentID)
	{
        $FileLinkIDs = array();

        $stmt = $this->DBH->prepare("SELECT FileID FROM contentfiles WHERE ContentID = :ContentID");
        $stmt->BindParam(':ContentID', $contentID);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($FileLinkIDs, $row["FileID"]);
        }
        return $FileLinkIDs;

		/*
			Create new array
		
			SELECT FileID from table 'ContentFiles' WHERE ContentID = $contentID
			Foreach row
			{
				push row["FileID"] into array
			}
			
			return array
			
			use PDO object $this->DBH
		*/
	}
}

class FileLink
{
	public $fileID;
	public $memberID;
	public $fileName;
	public $fileExt;
	public $fileTypeID;
	public $filePath;
	public $contentID;
}
?>