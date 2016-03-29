<?php
	class ChangeFilesClass extends index
	{
		private $allMembers = array();
		private $allContent = array();
		private $foundFiles;
		
		private $extensions = array("txt",
									"doc",		//Microsoft Word File Extensions
									"dot",
									"docx",
									"docm",
									"dotx",
									"dotm",
									"xls",		//Microsoft Excell File Extensions
									"xlt",
									"xlsx",
									"xlsm",
									"xltx",
									"xltm",
									"xlsb",
									"xla",
									"xlam",
									"xll",
									"xlw",
									"ppt",		//Microsoft Powerpoint File Extensions
									"pot",
									"pps",
									"pptx",
									"pptm",
									"potx",
									"potm",
									"ppam",
									"ppsx",
									"ppsm",
									"sldx",
									"sldm",
									"bmp",		//Raster Image File Extensions																		"jpg",
									"jpeg",
									"gif",
									"png", 
									"dds",
									"tga",
									"thm",
									"ai",		//Vector Image File Extensions
									"eps",
									"ps",
									"svg",
									"pdf",		//Page Layout File Extensions
									"xml",
									);
		
		private $inputMemberSearch;
		private $inputContentSearch;
	
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
			$this->allMembers = $this->Datahandler->Members->GetAllMembers();
			$this->allContent = $this->Datahandler->Contents->GetAllContent();
			//$this->foundFiles = $this->Datahandler->Files->GetAllFileLinksFromContentID(0);		
		}
		
		public function GetContent()
		{
			$GUI = $this->GetParent("GUI");
			
			$this->CheckDownloadFile();
		
			$content = '';
			
			$content .= $GUI->GetCMSMenu();
			
			$content .= $this->AddNewFiles();
			$content .= $this->GetSearchByMembersBox();
			$content .= $this->GetNewFileBox();
			$content .= $this->GetSearchByImagesFolderBox();
			
			return $content;
		}
		
		public function GetSearchByMembersBox()
		{
			$GUI = $this->GetParent("GUI");
			$mID = 0;
			$cID = 0;
			$foundFiles = array();
			$foundMID = array();
			
			if (isset($_GET["RemoveFile"]))
			{
				$this->Datahandler->Files->ForceRemoveFile($_GET["RemoveFile"]);
			}
			
			$sMC = '<table>
						<tr>
							<form name="searchByMemberForm" method="post", action="index.php?Page=CMS%2FChangeFiles">
								<td>
								</td>
								<td>
									<select name="selectedMember" onchange="this.form.submit()">
										<option value="0">Select All</option>';
			
			foreach ($this->allMembers as $member)
			{
				$sel = '';
				if (isset($_POST["selectedMember"]))
				{
					if ($_POST["selectedMember"] == $member->memberID)
						$sel = 'selected="selected"';
						
					$mID = $_POST["selectedMember"];
				}
				
				$sMC .= '   <option value="' . $member->memberID . '"' . $sel . '>' . $member->name . '</option>';
			}
			
			$sMC .= '				</select>
								</td>
								<td>
									<select name="selectedContent" onchange="this.form.submit()">
										<option value="0">Select All</option>';
						
			foreach ($this->allContent as $c)
			{
				$sel = '';
				if (isset($_POST["selectedContent"]))
				{
					if ($_POST["selectedContent"] == $c->contentID)
						$sel = 'selected="selected"';
						
					$cID = $_POST["selectedContent"];
				}
				
				$sMC .= '  				<option value="' . $c->contentID . '"' . $sel . '>' . $c->desc . '</option>';
			}
			
			$sMC .='				</select>
								</td>
							</form>
						</tr>';
			
			if ($cID != 0 and $mID != 0)
			{
				$foundFiles = $this->Datahandler->Files->GetAllFileLinksFromContentIDAndMemberID($cID, $mID);
			}
			else if ($cID != 0 and $mID == 0)
			{
				$foundFiles = $this->Datahandler->Files->GetAllFileLinksFromContentID($cID);
			}
			else if ($mID != 0 and $cID == 0)
			{
				$foundFiles = $this->Datahandler->Files->GetAllFileLinksByMemberID($mID);
			}
			else
			{
				$foundFiles = $this->Datahandler->Files->GetAlLFileLinks();
			}
					
			$sMC .='	<tr>
							<td>Filename</td>
							<td>Member</td>
							<td>Content</td>
						</tr>';
			
			foreach ($foundFiles as $fileLink)
			{	
				$member = $this->Datahandler->Members->GetMemberByID($fileLink->memberID);
				$contentID = $this->Datahandler->Contents->GetContentIDByFileID($fileLink->fileID);
				$content = $this->Datahandler->Contents->GetContent($contentID);
			
				$sMC .='<tr class="CMStr">
							<td>' . $fileLink->fileName . '.' . $fileLink->fileExt .'</td>';
				$sMC .='
				<td>' . $member->name . '</td>
				<td>' . $content->desc . '</td>
				<td><a href="Engine/DownloadPage.php?File=' . $fileLink->filePath . '"><button>Download</button></a></td>
				<td><a href="index.php?Page=CMS%2FChangeFiles&RemoveFile=' . $fileLink->filePath . '"><button>Remove</button></a></td>
						</tr>';
			}
			$sMC .='</table>';
					
			$content = $GUI->CreateBox("List of User Files", $sMC);
			
			return $content;
		}
		
		public function GetSearchByImagesFolderBox()
		{
			$GUI = $this->GetParent("GUI");
			$mID = 0;
			$cID = 0;
			
			if (isset($_GET["RemoveImageFile"]))
			{
				if (file_exists($_GET["RemoveImageFile"]))
				{
					unlink($_GET["RemoveImageFile"]);
				}
			}
			
			$ds = new DirectoryScan();
			$ds->ScanDirectory("Images/", array("png", "jpg", "jpeg", "img"), 10);
			$foundFiles = $ds->foundFiles;
			
			$sMC = '<table>
						<tr><br>
						</tr>
						<tr>
							<td>Path to file</td>
						</tr>';
			
			foreach ($foundFiles as $file)
			{	
				$file_parts = pathinfo($file);
				
				$sMC .='<tr class="CMStr">
							<td class="CMStd">' . $file_parts['dirname'] . '/' . $file_parts['basename'] . '</td>';
				$sMC .='
				<td><button class="PreviewButton" value="' . $file_parts['dirname'] . '/' . $file_parts['basename'] . '">Preview</button></td>
				<td><a href="index.php?Page=CMS%2FChangeFiles&RemoveImageFile=' . $file_parts['dirname'] . '/' . $file_parts['basename'] . '"><button>Remove</button></a></td>
						</tr>';
			}
			$sMC .='</table>';
					
			$content = $GUI->CreateBox("List of Images from Content", $sMC);
			
			return $content;
		}
		
		public function GetNewFileBox()
		{
			$GUI = $this->GetParent("GUI");
		
			$content = '<br>Uploads for members<br>(Private files)';
			
			$content.='
				<form name="uploadFileForm" method="post" action="index.php?Page=CMS%2FChangeFiles" enctype="multipart/form-data">
					<input type="file" name="fileInput" id="fileInput"><br><br>
					<label>Choose Member*</label><br>
					<select name="chooseForMember">
						<option value="0">Unset</option>';
						foreach ($this->allMembers as $member)
						{
			$content .='<option value="' . $member->memberID . '">' . $member->name . '</option>';
						}
					
			$content .='</select>
					<br><br>
					<label>Choose Content</label><br>
					<select name="chooseForContent">
						<option value="0">Unset</option>';
						foreach ($this->allContent as $c)
						{
			$content .='<option value="' . $c->contentID . '">' . $c->desc . '</option>';
						}
					
			$content .='</select>
					<br>
					<input type="submit" value="Save">
				</form>
			';
			
			$content .='<br>Uploads for content<br>(Public images only)';
			$content .='<form name="uploadFileForm" method="post" action="index.php?Page=CMS%2FChangeFiles" enctype="multipart/form-data">
							<input type="file" name="imageInput" id="imageInput"><br>
							<input type="submit" value="Save">
						</form>';
			
			
			return $GUI->CreateBox("Upload new file", $content);
		}
		
		public function AddNewFiles()
		{
			if (isset($_FILES["fileInput"]))
			{
				$fs = new Filestream();	
				$fs->SaveFile($_FILES["fileInput"], $this->extensions, 2, $_POST["chooseForMember"], $_POST["chooseForContent"]);
			}
			if (isset($_FILES["imageInput"]))
			{
				$fs = new Filestream();
				$fs->SaveImage($_FILES["imageInput"], array("jpg", "png", "jpeg", "img"), 2);
			}
		}
		
		public function CheckDownloadFile()
		{
			if (isset($_GET["DownloadPreview"]))
			{
				$path = $_GET["DownloadPreview"];
				
				$fs = new FileStream();
				$fs->DownloadFile($path);
			}
		}
	}
?>