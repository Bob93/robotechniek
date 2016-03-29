<?php
require_once("EngineClass.php");
require_once("DataHandlerClass.php");

$DownloadPage = new DownloadPage();

class DownloadPage {
	private static $dbh;
	
	public function __construct() {
		$dh = new DataHandler();
		$this->Download($dh);
	}
	
	public static function SetDBH($DBH) {
		$dbh = $DBH;
	}	

	//if user is the same as the file uploader
	private function Download($dh) {
		if (isset($_GET["File"])) {
				
			$fileLink = $dh->Files->ForceGetFileLink($_GET["File"]);	
			
			if (file_exists("../" . $fileLink->filePath)) {
				if ($fileLink->filePath != null)
				{
					header("Content-Type: application/octet-stream");
					header("Content-Disposition: attachment; filename=\"" . $fileLink->fileName . "." . $fileLink->fileExt . "\"");
					header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
					header("Cache-Control: no-cache");
					header("Pragma: no-cache");
					readfile("../" . $fileLink->filePath);
				}
				else
				{
					$this->OnError();
				}
			}
			else {
				$this->OnError();
			}
		}
		else if (isset($_GET["Path"])){
			$path = $_GET["Path"];
			$pathArr = $this->multiexplode(array("/", "."), $path);
			$fileExt = $pathArr[count($pathArr) - 1];
			$fileName = $pathArr[count($pathArr) - 2];
		
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=\"" . $fileName . "." . $fileExt . "\"");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Cache-Control: no-cache");
			header("Pragma: no-cache");
			readfile("../" . $path);
		}
	}
	
	private function OnError()
	{
		echo 'File not found!';
	}
	
	private function multiexplode ($delimiters, $string) {
    
		$ready = str_replace($delimiters, $delimiters[0], $string);
		$launch = explode($delimiters[0], $ready);
		return  $launch;
	}
}

?>