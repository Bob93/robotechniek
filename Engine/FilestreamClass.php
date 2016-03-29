<?php

require_once("DataHandlerClass.php");

class Filestream
{
	public $ext;
	public $size;
	public $errorText;
	public $savedPath;
	public $name;
	
	public $isAllowedSize;
	public $isAllowedExt;
	
	private $Datahandler;
	
	public function __construct()
	{
		$this->Datahandler = new Datahandler();
	}
	
	private function Reset()
	{
		$this->ext = null;
		$this->size = null;
		$this->errorText = null;
		$this->savedPath = null;
		$this->name = null;
	}
	
	private function Check(&$file, &$allowedExts, &$maxSize)
	{
		$maxSize *= 1048576;
	
		$this->ext = pathinfo($file["name"], PATHINFO_EXTENSION);
		
		$dest = "";
		
		//if memberID == admin
		//$dest = "Uploads/Admin/";
		//else
			
	
		$err = 0;
		
		$this->size = $file["size"];	
		
		$tempName = explode(".", $file["name"]);
		unset($tempName[count($tempName)]);
		
		$this->name = $tempName[0];
		
		if ($file["size"] <= $maxSize)
		{
			$this->isAllowedSize = true;
		}
		else
		{
			$err++;
			$this->isAllowedSize = false;
		}
		if (in_array($this->ext, $allowedExts))
		{
			$this->isAllowedExt = true;
		}
		else
		{
			$this->isAllowedExt = false;
			$err++;
		}
		if ($file["error"] > 0)
		{
			$err++;
			$this->errorText = "Error: " . $file["error"] . "<br>";
		}
		
		return $err;
	}

	public function SaveFile(&$file, &$allowedExts, &$maxSize, &$memberID, &$contentID)
	{
		$this->Reset();
		$err = $this->Check($file, $allowedExts, $maxSize);
		
		$dest = "Uploads/Members/";
		
		if ($err == 0)
		{
			$newFilename = sha1(mt_rand(1,9999) . $dest . uniqid()) . time();
			if (!move_uploaded_file($file["tmp_name"], $dest . $newFilename))
				$err++;
			else
			{
				$this->savedPath = $dest . $newFilename;
				$this->Datahandler->Files->AddFileLink($memberID, $contentID, $this->name, $this->ext, $this->savedPath);
			}
		}
	}
	
	public function SaveImage($file, $allowedExts, $maxSize)
	{
		$this->Reset();
		
		$dest = "Images/";
		
		$err = $this->Check($file, $allowedExts, $maxSize);
		
		$f = ($dest . $this->name . '.' . $this->ext);
		
		if ($err == 0)
		{
			if (!move_uploaded_file($file["tmp_name"], $f))
				$err++;
			else
			{
			}
		}		
	}
}
?>