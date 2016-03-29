<?php

class ChangeContentsClass extends index
	{

		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
		}
		
		public function GetContent()
		{
            $content = '';

        if(isset($_POST['content1'])){

        }
        if(isset($_GET['content'])){

            $contentSite = $_GET['content'];

            // first page
            if($contentSite == "load"){
                $dataFromDatabase = $this->Datahandler->Contents->GetAllContent();

                /*$content .= $this->GetParent("GUI")->CreateBox('Change Contents', '
                    <form name="testForm" method="post" action="index.php?Page=CMS%2FChangeContents">

                        Description: <input type="text" name="description"><br>
                        published: <input type="radio" name="published"><br>
                        Active: <input type="radio" name="active"><br>
                        Name: <input type="text" name="input1"><br>
                        <input type="submit" value="Save">
                        <textarea id="test"></textarea>
                        <script>
                            CKEDITOR.replace("test");
                        </script>
                    </form>*/
                $contentFromForEach = '<tr>
                                            <th>Id</th>
                                            <th>Description</th>
                                            <th>Edit</th>
                                            <th>Remove</th>
                                            <th>Active</th>
                                       </tr>';

                foreach($dataFromDatabase as $data){
                    $active = '';
                    if($data->active == '1')
                        $active = 'Yes';
                    else
                        $active = 'No';
                    $contentFromForEach .=
                    '<tr>
                        <td>'.$data->contentID.'</td>
                        <td>'.$data->desc.'</td>
                        <td><a href="?Page=CMS%2FChangeContents&content=edit&id='.$data->contentID.'">Edit</a></td>
                        <td><a href="?Page=CMS%2FChangeContents&content=deactivate&id='.$data->contentID.'">Remove</a></td>
                        <td>'.$active.'</td>
                    </tr>';
                }
                $contentFromForEach .= '</table></div>';


                $content .= $this->GetParent("GUI")->GetCMSMenu();
                $content .= $this->GetParent("GUI")->CreateBox('Change Contents', '
                    <div id="addNewButton">
                        <a href="?Page=CMS%2FChangeContents&content=Add"><label id="Add">Add New Content</label></a>
                    </div>
                    <div id="tableHolder">
                        <table id="contentFromDatabase" border="1">'.$contentFromForEach
                );
                 return $content;

	    	}
            // add new content
            elseif($contentSite == "Add")
            {
                $content = '';
                $content .= $this->GetParent("GUI")->GetCMSMenu();
                $content .= $this->GetParent("GUI")->CreateBox('Change Contents','
                   <form name="testForm" method="post" action="index.php?Page=CMS%2FChangeContents&content=save">
                    <br>Description: <input type="text" name="description"><br>
        <table>
           <tr>
            <td>Published: <input type="date" name="published"></td><br>
            <td>Active: <input type="radio" name="active"></td><br>
                    </tr>
               </table>
                        <input type="submit" value="Save">
                            <textarea id="content" name="content1"></textarea>
                        </form>
                ');

                return $content;
            }
            elseif($contentSite == "save"){
                if(isset($_POST['content1']) && !isset($_GET['id']))
                {
                    $description = $_POST['description'];
                    $content = $_POST['content1'];
                    $published = $_POST['published'];
                    $active = $_POST['active'];
                    if($active == 'on')
                        $active = 1;
                    else
                        $active=0;
                    if($description != '' && $content != "" && $published != ""){
                        $this->Datahandler->Contents->AddContent($description,$content,$published,$active);
                        header('Location: '.$_SERVER['PHP_SELF'].'?Page=CMS%2FChangeContents&content=load');
                    }

                }
                elseif(isset($_POST['content1']) && isset($_GET['id']))
                {
                    $id = $_GET['id'];
                    $description = $_POST['description'];
                    $content = $_POST['content1'];
                    $published = $_POST['published'];
                    $active = $_POST['active'];
                    if($active == 'on')
                        $active = 1;
                    else
                        $active=0;
                    if($description != '' && $content != "" && $published != ""){
                        $this->Datahandler->Contents->EditContent($id,$description,$content,$published,$active);
                        header('Location: '.$_SERVER['PHP_SELF'].'?Page=CMS%2FChangeContents&content=load');
                    }
                }

            }
            elseif($contentSite == "edit")
            {
                $id = $_GET['id'];
                $active = '';
                $dataFromDatabase = $this->Datahandler->Contents->GetAllDataFromContent($id);
                if($dataFromDatabase['Active'] == '1')
                    $active = 'checked';
                else
                    $active = "";

                $content = '';
                $content .= $this->GetParent("GUI")->GetCMSMenu();
                $content .= $this->GetParent("GUI")->CreateBox('Change Contents','
                   <form name="testForm" method="post" action="index.php?Page=CMS%2FChangeContents&content=save&id='.$id.'">
                    <br>Description: <input type="text" name="description" value="'.$dataFromDatabase["Description"].'"><br>
                    <table>
                       <tr>
                        <td>Published: <input type="date" name="published" value="'.$dataFromDatabase["Published"].'"></td><br>
                        <td>Active: <input type="radio" name="active" '.$active.'></td><br>
                                </tr>
                           </table>
                                    <input type="submit" value="Save">
                                        <textarea id="content" name="content1">'.$dataFromDatabase["Content"].'</textarea>
                                    </form>
                ');

                

            }
            elseif($contentSite == 'deactivate')
            {
                $id = $_GET['id'];

                $this->Datahandler->Contents->DeleteContent($id);
                header('Location: '.$_SERVER['PHP_SELF'].'?Page=CMS%2FChangeContents&content=load');

            }
        }
		
		$content .= $this->GetSearchByImagesFolderBox();
		
		return $content;
		}
		
		private function GetSearchByImagesFolderBox()
		{
			$GUI = $this->GetParent("GUI");
			$mID = 0;
			$cID = 0;
			$c = $_GET["id"];
			
			if (isset($_FILES["imageInput"]))
			{
				$fs = new Filestream();
				$fs->SaveImage($_FILES["imageInput"], array("jpg", "png", "jpeg", "img"), 2);
			}
			
			$sMC = '<form name="uploadFileForm" method="post" action="index.php?Page=CMS%2FChangeContents&content=edit&id=' . $c . '" enctype="multipart/form-data">
							<label>Upload new image:</label>
							<input type="file" name="imageInput" id="imageInput">
							<input type="submit" value="Save">
					</form>';
			
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
			
			$sMC .= '<table>
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
				<td><a href="index.php?Page=CMS%2FChangeContents&content=edit&id=' . $c . '&RemoveImageFile=' . $file_parts['dirname'] . '/' . $file_parts['basename'] . '"><button>Remove</button></a></td>
						</tr>';
			}
			$sMC .='</table>';
					
			$content = $GUI->CreateBox("List of Images from Content", $sMC);
			
			return $content;
		}
	}
?>