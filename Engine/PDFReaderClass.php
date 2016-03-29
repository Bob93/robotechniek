<?php

class PDFReader extends Index { 

	/* 
	* Function from DBH to get the file links.
	* All other files than .pdf should be filtered
	*/
	$this->Datahandler->Files->GetFileLink();
	
	//$file is the path to the file, $filename is the name that is displayed. PDF is opened on the same tab
	public function ReadPDF() {
		$file = '';
		$fileName = '';

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$fileName.'"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: '.filesize($file));
		header('Accept-Ranges: bytes');

		@readfile($file);
	}
	
}
?>