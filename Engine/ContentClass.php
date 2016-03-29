<?php


class ContentClass
{

	private $DBH;

	public function __construct($dbh)
	{
		$this->DBH = $dbh;
	}
	
	public function GetLogo($userID)
	{
		/*
		return value of logo table when $userID == logo.userID
		
		Use PDO object $this->$DBH
		*/
	}
	
	public function AddContent($description, $content, $published, $active)
	{
        $Stmt = $this->DBH->prepare('INSERT INTO content (Description, Content, Published, Active) VALUES( :description, :content, :published, :active)');
        $Stmt->bindParam(':description',$description);
        $Stmt->bindParam(':content',$content);
        $Stmt->bindParam(':published',$published);
        $Stmt->bindParam(':active',$active);
        $Stmt->execute();




        
		/*
		If $userID is empowered to create new Content
			Add content as $content with $userID to the content table
			return TRUE
		Else
			return FALSE
			
		Use PDO object $this->$DBH
		*/
	}

	public function EditContent($contentID, $description, $content, $published, $active)
	{

        $Stmt = $this->DBH->prepare('UPDATE content SET Description = :description, Content = :content, Published = :published, Active = :active WHERE ContentID = :contentID');
        $Stmt->bindParam(':contentID', $contentID);
        $Stmt->bindParam(':description', $description);
        $Stmt->bindParam(':content', $content);
        $Stmt->bindParam(':published', $published);
        $Stmt->bindParam(':active', $active);
        $Stmt->execute();
		/*
		If $userID is empowered to edit Content
			Update content as $content with $userID in the content table
			return TRUE
		Else
			return FALSE
			
		Use PDO object $this->$DBH
		*/
	}
	
	public function DeleteContent($contentID)
	{
        $act = 0;
        $Stmt = $this->DBH->prepare('UPDATE content SET  Active = :active WHERE ContentID = :contentID');
        $Stmt->bindParam(':contentID', $contentID);
        $Stmt->bindParam(':active', $act);
        $Stmt->execute();
		/*
		If $userID is empowered to delete Content
			Delete content as $content with $userID in the content table
			return TRUE
		Else
			return FALSE
			
		Use PDO object $this->$DBH
		*/
	}
	
	public function DeleteContentOrder($contentID, $pageID)
	{
		$stmt = $this->DBH->prepare('DELETE FROM pagecontentorder WHERE ContentID = :contentID and PageID = :pageID');
		$stmt->bindParam(':contentID', $contentID);
		$stmt->bindParam(':pageID', $pageID);
		
		$stmt->execute();
	}
	
	public function AddContentOrder($pageID, $contentID)
	{
		$stmt = $this->DBH->prepare('INSERT INTO pagecontentorder VALUES (:pageID, :contentID, 1)');
		$stmt->bindParam(':pageID', $pageID);
		$stmt->bindParam(':contentID', $contentID);
		
		$stmt->execute();
	}
	
	public function EditContentOrder($pageID, $contentID, $oldContentID)
	{
		$stmt = $this->DBH->prepare('UPDATE pagecontentorder SET ContentID = :contentID WHERE PageID = :pageID and ContentID = :oldContentID');
		$stmt->bindParam(':contentID', $contentID);
		$stmt->bindParam(':pageID', $pageID);
		$stmt->bindParam(':oldContentID', $oldContentID);
		
		$stmt->execute();	
	}
	
		/*Return string Content*/
	//Updated on 2014-06-16 21:27:00 by Erwin Jansen. Now returns object Content.
	public function GetContent($contentID)
	{
		$content = new Content();
	
		$Stmt = $this->DBH->prepare('SELECT * FROM content
									 WHERE  ContentID = :ContentID
									 AND Active = 1');
		$Stmt->bindParam(':ContentID', $contentID);

		$Stmt->execute();

		while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			$content->contentID = $row['ContentID'];
			$content->desc = $row['Description'];
			$content->content = $row['Content'];
		}
		
		return $content;
	}
	
    //Damir Dzilic
    public  function GetAllDataFromContent($contentId)
    {
        $stmt = $this->DBH->prepare('SELECT * FROM content
                                     WHERE ContentID = :ContentID');
        $stmt->bindParam('ContentID',$contentId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //Damir Dzilic - Date unknown
    public function GetAllPageContents($pageID)
    {
		$contents = array();
	
        $stmt = $this->DBH->prepare('SELECT *
                                     FROM content
                                     JOIN pagecontentorder
                                     ON content.contentID=pagecontentorder.ContentID
                                     WHERE pagecontentorder.pageID = :pageID
									 AND Active = 1;');
        $stmt->bindParam(':pageID', $pageID);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$content = new Content();
		   
			$content->contentID = $row["ContentID"];
			$content->content = $row["Content"];
			$content->desc = $row["Description"];
            $content->active = $row['Active'];
			
			array_push($contents, $content);
        }
		
		return $contents;
    }

    //Damir Dzilic - Date unknown
    public function ChangeContentOrder($pageID, $ContentID, $newOrder){
        $oldOrderID = 0;
        $oldContentID = 0;

        $stmt = $this->DBH->prepare('SELECT ContentID,OrderID FROM pagecontentorder  WHERE ContentID = :ContentID AND pageID = :pageID');
        $stmt->bindParam(':ContentID', $ContentID);
        $stmt->bindParam(':pageID', $pageID);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $oldOrderID = $row['OrderID'];
        }

        $stmt = $this->DBH->prepare('SELECT ContentID FROM pagecontentorder  WHERE OrderID = :newOrder AND pageID = :pageID');
        $stmt->bindParam(':newOrder', $newOrder);
        $stmt->bindParam(':pageID', $pageID);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $oldContentID = $row['ContentID'];
        }

            $stmt = $this->DBH->prepare("UPDATE pagecontentorder SET OrderID = :newOrder WHERE ContentID = :ContentID AND PageID = :pageID");
            $stmt->bindParam(':ContentID', $ContentID);
            $stmt->bindParam(':newOrder', $newOrder);
            $stmt->bindParam(':pageID', $pageID);
            $stmt->execute();

            $stmt = $this->DBH->prepare("UPDATE pagecontentorder SET OrderID = :oldOrderID WHERE ContentID = :oldContentID AND PageID = :pageID");
            $stmt->bindParam(':oldOrderID', $oldOrderID);
            $stmt->bindParam(':oldContentID', $oldContentID);
            $stmt->bindParam(':pageID', $pageID);
            $stmt->execute();

    }

    //Damir Dzilic - 13-6-2014
	public function GetContentID($pageID)
	{
        $contentIDs = array();
		$Stmt = $this->DBH->prepare('SELECT ContentID FROM pagecontentorder
                                     JOIN pages
                                     ON pagecontentorder.PageID = pages.PageID
									 WHERE  pages.PageID = :PageID');
		$Stmt->bindParam(':PageID', $pageID);
		$Stmt->execute();

		while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			array_push($contentIDs, $row["ContentID"]);
		}

        return $contentIDs;
	}
	
	//Created on 2014-06-16 13:17:00 By Erwin Jansen
	public function GetAllContent()
	{
		$contents = array();
		
		$stmt = $this->DBH->prepare("SELECT * FROM content ORDER BY ContentID DESC");
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$content = new Content();
			
			$content->contentID = $row["ContentID"];
			$content->content = $row["Content"];
			$content->desc = $row["Description"];
            $content->active = $row['Active'];
			
			array_push($contents, $content);
		}
		
		return $contents;
	}
	
	//Created on 2014-06-16 00:00:00 by Erwin Jansen
	public function GetContentIDByFileID($fileID)
	{
		$contentID = "";
		
		$stmt = $this->DBH->prepare("SELECT * FROM contentfiles WHERE FileID = :fileID");
		$stmt->bindParam(":fileID", $fileID);
		
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$contentID = $row["ContentID"];
		}
		
		return $contentID;
	}
}

class Content
{
	public $contentID;
	public $content;
	public $orderID;
	public $desc;
    public $active;
}