<?php
//Made ChangeFooterClass.php 16-06-2014 21:56 Muhammed
	class ChangeFooterClass extends index
	{
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
		}
		
		//With this you can now change the footer in de cms page.
		public function GetContent()
		{
			$content = '';
			$pageID = 1;

			if (isset($_POST["FooterInput"])){
				$this->Datahandler->Footer->editFooter($this->SEC->Secure("xss", $pageID, "num", ""), $this->SEC->Secure("xss", $_POST["FooterInput"], "admin", ""));
			}

			$content .= $this->GetParent("GUI")->GetCMSMenu();

			$content .= $this->GetParent("GUI")->CreateBox('Change Footer', '
				<form name="testForm" method="post" action="index.php?Page=CMS%2FChangeFooter">
					Footer: <br><textarea id="ckedit" name="FooterInput" rows="4" cols="50">'.$this->Datahandler->Footer->GetFooter($pageID).'</textarea><br>
				
					<input type="submit" value="Save">
				</form>
				<script>
					CKEDITOR.replace( "ckedit" );
				</script>
			');
			return $content;
		}
	}
?>