<?php

class DirectoryScan
{
	public $fileExists = false;
	public $folderExists = false;
	
	public $foundFiles = Array();
	public $foundFolders = Array();
	
	public $enginePages = Array();
	
	/*Scans whole Directory*/
	public function ScanDirectory($dir, $ext, $depth) {	
		$d = 0;
		if ($depth == "infinite")
		{
			$depth = 1;
			$d = "infinite";
		}
		
		if ($depth > 0)
		{
			foreach(glob($dir . '/*') as $file) {	
				if (filetype($file) == "dir") {

					if ($d != "infinite")
					{
						$this->ScanDirectory($file, $ext, $depth - 1);
					}
					else
					{	
						$this->ScanDirectory($file, $ext, $d);
					}
						
					Array_push($this->foundFolders, $file);
				}
				else if(filetype($file) == "file") {
					$file_parts = pathinfo($file);
					
					if (is_array($ext))
					{
						$s = strtolower($file_parts['extension']);
						if (in_array($s, $ext))
						{
							array_push($this->foundFiles, $file);
						}
					}
					else
					{
						if ($file_parts['extension'] == $ext)
						{
							array_push($this->foundFiles, $file);
						}
					}
				}
			}
		}
		
		if (sizeof($this->foundFolders) > 0)
			$this->filesExists = true;
			
		if (sizeof($this->foundFiles) > 0)
			$this->folderExists = true;
	}
}

/*Loads XML documents*/
class XMLLoader
{
	public function LoadXML($fileName) {

		$exists = false;

		$path = "Configs/" . $fileName . ".xml";
		if (file_exists($path))	{
			$this->Load($path);
		}
		else if(file_exists("../" . $path))	{
			$this->Load("../" . $path);
		}
		else {
			print ("Failed to load the XML file!");
		}
	}

	private function Load($path) {
		$xml = simplexml_load_file($path);

		foreach ($xml->children() as $child) {
			$pew = $child->getName();
			
			if (!isset($this->$pew)) {
				$this->$pew = array();
				array_push($this->$pew, $child);
			}
			else {
				array_push($this->$pew, $child);
			}
		}
	}
}

class PDFReader { 

	//Opens .PDF files in the same tab. $file is the filepath from the database, $fileName is the displayed name, $filePath is the path to the map
	public function ReadPDF($file, $fileName) {
		$file = '';
		$fileName = '';
		$filePath = '../Uploads/'.$file.'';

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$fileName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($filePath));
		header('Accept-Ranges: bytes');

		@readfile($file);
	}
	
	//Downloads files. $fileLink is needed for the path to the file
	public function Download($fileLink) {
		header('Content-Type: application/octet-stream');
		header("Content-Disposition: attachment; filename=\"" . $fileLink->fileName . "." . $fileLink->fileExt . "\"");
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
		readfile('../' . $fileLink->filePath);
	}
}

?>