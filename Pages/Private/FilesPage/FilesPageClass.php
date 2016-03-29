<?php

class FilesPageClass extends Index {

	public function Start() {		
		Index::$title = "ROBO Bestanden";
	}
			
	public function GetContent() {
		$foundFiles = $this->Datahandler->Files->GetAllFileLinks();
		$pdfReader = new PDFReader();
		
		if (isset($_GET['MemberID'])) {
			$memberID = $_GET['MemberID'];
		} else {
			$memberID = 0;
		}
		
		$menu = $this->Datahandler->Menus->GetMenu(2);
		$value= '';
		$content1='
			<div class="col-md-9">
				<div class="c-container">
					<div class="content">
						<div class="contenttext">
							<div class="row">
								<form method="POST">
									<div class = "col-md-6">
										<select name="file">';
											foreach ($foundFiles as $fileLink) {	
												$member = $this->Datahandler->Members->GetMemberByID($fileLink->memberID);
												$contentID = $this->Datahandler->Contents->GetContentIDByFileID($fileLink->fileID);
												$content = $this->Datahandler->Contents->GetContent($contentID);
												$content1 .= '<option value="'.$fileLink->filePath.'">' 
												.$fileLink->fileName . '.' . $fileLink->fileExt;
												$content1 .='</option>';
											}
		$content1 .= '					</select>
									</div>
									<div class = "col-md-6">
										<input type="submit" value="Submit" name="submit">';
										if(isset($_POST['submit'])) {
											$fileData = $this->Datahandler->Files->ForceGetFileLink($_POST['file']);
											if($fileData->fileExt == 'pdf' ){
												$pdfReader->ReadPDF($file->filePath, $fileName->fileName); 
											} else {
												$pdfReader->Download($fileData);
											}
										}
		$content1 .='				</div>
								</form>
							</div>
						</div>
					</div>
				</div> 
			</div>
		</div>
	</div>';
		$value .= $this->GetParent("Standard")->GetStandardPage($content1);	
		return $value;
	}
}

?>