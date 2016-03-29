<?php
//Made HeaderClass 16-06-2014 13:14 Muhammed
class HeaderClass
{
    private $DBH;

    public function __construct($dbh) {
        $this->DBH = $dbh;
    }

    //Here you can edit the header. 16-06-2014 13:14 Muhammed
    public function editHeader($headerID, $headerText) {
        $stmtEditHeader = $this->DBH->prepare("UPDATE header SET HeaderText = :headerText WHERE HeaderID = :headerID");
        $stmtEditHeader->bindParam(":headerID", $headerID);
        $stmtEditHeader->bindParam(":headerText", $headerText);
        $stmtEditHeader->execute();
    }

    //Here you can get the header. 16-06-2014 13:14 Muhammed
    public function getHeader($pageID) {
        $stmt = $this->DBH->prepare("SELECT header.HeaderText FROM header
									JOIN pages 
									ON pages.HeaderID = header.HeaderID
									WHERE pages.PageID = :pageID");
        $stmt->bindParam(':pageID', $pageID);
        $stmt->execute();
        $result;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $result = $row["HeaderText"];
        }
        return $result;
    }
}

?>