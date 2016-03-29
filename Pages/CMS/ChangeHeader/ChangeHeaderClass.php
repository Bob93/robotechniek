<?php
class ChangeHeaderClass extends index
{
    public function Start()
    {
		$this->GetParent("GUI")->CheckUser();
    }

    public function GetContent()
    {
        $content = '';
        $pageID = 1;

        if (isset($_POST["HeaderInput"])){
            $this->Datahandler->Header->editHeader($this->SEC->Secure("xss", $pageID, "num", ""), $this->SEC->Secure("xss", $_POST["HeaderInput"], "string", ""));
        }

        $content .= $this->GetParent("GUI")->GetCMSMenu();

        $content .= $this->GetParent("GUI")->CreateBox('Change Header', '
				<form name="testForm" method="post" action="index.php?Page=CMS%2FChangeHeader">
					Header: <br><textarea id="ckedit" name="HeaderInput" rows="4" cols="50">'.$this->Datahandler->Header->GetHeader($pageID).'</textarea><br>
					
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
