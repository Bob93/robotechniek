<?php

class FooterClass
{
	private $DBH;

	public function __construct($dbh) {
		$this->DBH = $dbh;
	}
	
	//Fixed multiple errors on editFooter. 16-06-2014 21:56 Muhammed
	public function editFooter($footerID, $footerText) {
		$stmtEditFooter = $this->DBH->prepare("UPDATE footer SET FooterText = :footerText WHERE FooterID = :footerID");
		$stmtEditFooter->bindParam(":footerID", $footerID);
		$stmtEditFooter->bindParam(":footerText", $footerText);
		$stmtEditFooter->execute();
	}
	
	//Fixed multiple PHP and MySQL errors. 2014-06-15 21:33:00 by Erwin Jansen
	public function getFooter($pageID) {
		$stmt = $this->DBH->prepare("SELECT footer.FooterText FROM footer
									JOIN pages 
									ON pages.FooterID = footer.FooterID 
									WHERE pages.PageID = :pageID");
		$stmt->bindParam(':pageID', $pageID);
		$stmt->execute();
		$result;
		
	    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$result = $row["FooterText"];
	    }	
		return $result;
	}
}

?>