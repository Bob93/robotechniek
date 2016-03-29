<?php
require_once("EngineClass.php");
require_once("ContentClass.php");
require_once("EventClass.php");
require_once("FooterClass.php");
require_once("MenuClass.php");
require_once("PageClass.php");
require_once("FilestreamClass.php");
require_once("FilesClass.php");
require_once("MemberClass.php");
require_once("HeaderClass.php");
require_once("UserClass.php");
require_once("DownloadPage.php");

class Datahandler
{
	public $Contents;
	public $Events;
	public $Footer;
	public $Menus;
	public $Pages;
	public $Files;
	public $Members;
	public $Header;
	public $Users;
	
	public function __construct() {
		$xml = new XMLLoader();
		$xml->LoadXML("dbConn");

		try {		
			$DBH = new PDO ('mysql:host=' . $xml->host[0] . ';
						dbname=' . $xml->databaseName[0] . ';
						charset=' . $xml->charset[0], 
						$xml->username[0],
						$xml->password[0]);
			
			$this->Contents = new ContentClass($DBH);
			$this->Events = new EventClass($DBH);
			$this->Footer = new FooterClass($DBH);
			$this->Menus = new MenuClass($DBH);
			$this->Pages = new PageClass($DBH);
			$this->Files = new FilesClass($DBH);
			$this->Members = new MemberClass($DBH);
			$this->Header = new HeaderClass($DBH);
			$this->Users = new UserClass($DBH);
			
			DownloadPage::SetDBH($DBH);
		}
		catch(Exception $e)	{
			print ("Error loading the database!<br>");
		}
	}
}
?>