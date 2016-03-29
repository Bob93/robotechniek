<?php

class PageClass
{
	private $DBH;

	public function __construct($dbh) {
		$this->DBH = $dbh;
	}
				
	public function addPage($public, $publishDate, $description, $pageName, $memberID) {	
		//Setting default values to the following variables
		//Afterwards it is possible to change it

		$stmntAddPage = $this->DBH->prepare("INSERT INTO pages (HeaderID, FooterID, MenuID, MemberID, Published, Active, PageName) 
											VALUES(1, 1, :menuid, :memberid, :published, 1, :pagename)");	
		$stmntAddPage->bindParam(":menuid", $public);
		$stmntAddPage->bindParam(":memberid", $memberID);
		$stmntAddPage->bindParam(":published", $publishDate);
		$stmntAddPage->bindParam(":pagename", $pageName);
		$stmntAddPage->execute();	
	}
	
	public function deletePage($pageID) {
		$notActive = 0;
		$Stmt = $this->DBH->prepare("UPDATE pages SET Active=:notActive WHERE PageID=:pageID");
		$Stmt->bindParam(":notActive", $notActive);
		$Stmt->bindParam(":pageID", $pageID);
		$Stmt->execute();
	}
	
	public function EditPage($pageID, $pageName, $published, $memberID)
	{
		$stmt = $this->DBH->prepare("UPDATE pages SET MemberID = :memberID, PageName = :pageName, published = :published WHERE PageID = :pageID");
		$stmt->bindParam(':memberID', $memberID);
		$stmt->bindParam(':pageName', $pageName);
		$stmt->bindParam(':published', $published);
		$stmt->bindParam(':pageID', $pageID);
		
		$stmt->execute();
	}
	
	public function GetPage($pageID) {
		$page = new Page();

		$stmt = $this->DBH->prepare("SELECT * FROM pages WHERE PageID = :pageID");
		
		$stmt->bindParam(':pageID', $pageID);
		$stmt->execute();	

	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		
			$page->pageID    = $row["PageID"];
			$page->memberID  = $row["MemberID"];
			$page->menuID    = $row["MenuID"];
			$page->published = $row["Published"];
			$page->name 	 = $row["PageName"];
	    }	
	    return $page;

	}
	
	//Maurice
	public function GetPageID($MemberID)
	{
		$pageID = "";
		
		$SQL_Statement = $this->DBH->prepare("SELECT P.PageID FROM pages P
												INNER JOIN members M ON P.MemberID = M.MemberID WHERE P.MemberID = :id");
		$SQL_Statement->bindParam(':id', $MemberID);
		$SQL_Statement->execute();
		while ($row = $SQL_Statement->fetch(PDO::FETCH_ASSOC)){
		    $PageID = $row['PageID'];
	    }	
	    return $PageID;
	}
	
	//Maurice
	public function GetPageLink($pageID)
	{
		$PageID = "";
		
		$SQL_Statement = $this->DBH->prepare("SELECT M.MenuLink FROM menuitems M
												INNER JOIN pages P ON M.pageID=P.pageID WHERE P.pageID=:id");
		$SQL_Statement->bindParam(':id', $pageID);
		$SQL_Statement->execute();
		while ($row = $SQL_Statement->fetch(PDO::FETCH_ASSOC)){
		    $PageID = $row['MenuLink'];
	    }	
	    return $PageID;
	}
	
	public function GetAllPages()
	{
		$pages = array();
		
		$stmt = $this->DBH->prepare("SELECT * FROM pages WHERE active = 1");
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
		{
			$page = new Page();
			
			$page->pageID    = $row["PageID"];
			$page->memberID  = $row["MemberID"];
			$page->menuID    = $row["MenuID"];
			$page->published = $row["Published"];
			$page->name 	 = $row["PageName"];
			// $page->memberID = $row["memberID"];
			
			array_push($pages, $page);
		}
		
		return $pages;
	}
}

class Page
{
	public $pageID;
	public $content;
	public $description;
	public $contentID;
	public $orderID;
	public $headerID;
	public $footerID;
	public $menuID;
	public $name;
	public $memberID;
	//Added active by Osman
	public $published;
}


?>