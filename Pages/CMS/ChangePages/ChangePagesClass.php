<?php
//This function is needed the make use of the header() function
ob_start();
	class ChangePagesClass extends index
	{
		public function Start()
		{
			$this->GetParent("GUI")->CheckUser();
		}
		
		public function GetContent()
		{
			$content = '';
			$content .= $this->GetParent("GUI")->GetCMSMenu();
			$pages = $this->Datahandler->Pages->GetAllPages();

			if(isset($_GET['page']))
			{
				$pageID = 0;
				
				if (isset($_GET['id']))
				{
					$pageID = $_GET['id'];
				}
			
				$allContent = $this->Datahandler->Contents->GetAllContent();	
				
				$choosedPage = $_GET['page'];
				
				switch ($choosedPage) 
				{
					case 'editPage':																				//CASE EDITPAGE
					
					if (isset($_POST["removeContentPart"]))
					{
						
					}
					
					if (isset($_POST["changePageContent"]))
					{
						$b = true;
						$cIDs = array();
						$nIDs = array();
						$uIDs = array();
						
						if(!empty($_POST["newPageName"]) and !empty($_POST["publishDate"]))
						{
							for ($i=0; $i<100; $i++)
							{
								if (isset($_POST["selectedContents" . '_' . $i]))
								{
									$m = explode(".", $_POST["selectedContents" . '_' . $i]);
									array_push($m, $i);
								
									if (in_array($m[0], $cIDs))
									{
										$b = false;
									}
									else
									{
										array_push($cIDs, $m[0]);
										array_push($uIDs, $m);
									}
								}
								else if (isset($_POST["selectedNewContents" . '_' . $i]))
								{
									$m = explode(".", $_POST["selectedNewContents" . '_' . $i]);
									
									if ($_POST["selectedNewContents" . '_' . $i] == 0)
									{
										$b = false;
									}
									else if (in_array($m[0], $cIDs))
									{
										$b = false;
									}
									else
									{
										array_push($cIDs, $m[0]);
										array_push($nIDs, $m[0]);
									}
								}
							}
							if ($b)
							{
								foreach ($nIDs as $c)
								{
									
									$this->Datahandler->Contents->AddContentOrder($pageID, $c);								
								}
								
								foreach ($uIDs as $u)
								{
									
									$this->Datahandler->Contents->EditContentOrder($pageID, $u[0], $u[1]);		
								}
								
								$pageName 		= $_POST["newPageName"];
								$publishDate 	= $_POST["publishDate"];
								$memberID 		= $_POST["selectedMember"];
								
								$this->Datahandler->Pages->EditPage($pageID, $pageName, $publishDate, $memberID);
								
								header('Location: index.php?Page=CMS%2FChangePages&page=getPages');
							}
							else
							{
								echo "<div style='background: red; text-align: center; color: #fff;'>Cannot duplicate content parts</div>";
							}
						}
						else
						{
							//The frontend could add a class to style this
							//div, to output the error clearly to the user.
							echo "<div style='background: red; text-align: center; color: #fff;'>Please fill all fields in</div>";

						}
					}
						$pa = $this->Datahandler->Pages->GetPage($pageID);
					
						$pageContents = $this->Datahandler->Contents->GetAllPageContents($_GET['id']);
						$toAddContentLinks = 0;
						
						$newContentAmount = null;
						if (isset($_POST["selectContentAmount"]))
						{
							$a = count($pageContents);
							$newContentAmount = $_POST["selectContentAmount"];
										
							if ($a > $newContentAmount) //-
							{
								$c = $a - $newContentAmount;
								for ($i = 0; $i<$c; $i++)
								{
									$this->Datahandler->Contents->DeleteContentOrder($pageContents[count($pageContents) - 1]->contentID, $pageID);
									unset($pageContents[count($pageContents) - 1]);	
								}
							}
							else 						//+
							{
								$toAddContentLinks = $newContentAmount - $a;
							}
						}
						
						$boxContent = '
							<form name="changeContentAmount" method="post" action="index.php?Page=CMS%2FChangePages&page=editPage&id=' . $pageID . '" id="changeContentAmountForm">
								<label>Select amount of content parts</label><br>
								<select name="selectContentAmount">';
						for($i = 0; $i<100; $i++)
						{
							if ($i == count($pageContents))
							{
							$boxContent .= 
									'<option selected="selected" value="' . $i . '">' . $i . '</option>';
							}
							else
							{
							$boxContent .= 
									'<option value="' . $i . '">' . $i . '</option>';
							}
						}
						$boxContent .= '			
							</select>
							<input type="submit" value="Submit amount"><br><br>
						</form>
						<form name="editPageForm" method="post" action="index.php?Page=CMS%2FChangePages&page=editPage&id=' . $pageID . '">
						<label>Page ID</label><br>'; 				
						$boxContent .= '<input type="text" name="pageid" value="' . $pageID . '" readonly></br>
						<label>Page name:</label>
						<input type="text" value="' . $pa->name . '" $placeholder="Your page name" name="newPageName" autocomplete="off"><br><br>
						<label>Select member:</label>
						<select name="selectedMember">';
						$allMembers = $this->Datahandler->Members->GetAllMembers();
						foreach ($allMembers as $member)
						{	
							if ($pa->memberID == $member->memberID)
							{
								$boxContent .= '<option selected="selected" value="' . $member->memberID . '">' . $member->name . '</option>';
							}
							else
							{
								$boxContent .= '<option value="' . $member->memberID . '">' . $member->name . '</option>';
							}
						}
						$boxContent .= '</select>';
						$boxContent .= "<p>Page contents by description:</p>";
						$boxContent .= '<table>';
						$i = 1;
						foreach ($pageContents as $p)
						{
							$boxContent .= '<tr>
								<td>' . $i . '</td>
								<td><select name="selectedContents' . '.' . $i . '">';
								
							foreach ($allContent as $c)
							{
								if ($p->contentID == $c->contentID)
								{
									$boxContent .= '<option value="' . $c->contentID . '.' . $p->contentID . '" selected="selected">' . $c->desc . '</option>';
								}
								else
								{
									$boxContent .= '<option value="' . $c->contentID . '.' . $p->contentID . '">' . $c->desc . '</option>';
								}
								
							}
							$boxContent .= '</select></td></tr>	
								</select></td>
								</tr>';
							++$i;
						}
						for ($j = 0; $j < $toAddContentLinks; $j++)
						{
							$boxContent .= '<tr>
												<td>' . ($i + $j) . '</td>
												<td>
													<select name="selectedNewContents' . '.' . ($i + $j) . '">
														<option value="0">Select*</option>';
							foreach ($allContent as $c)
							{
								$boxContent .= 			'<option value="' . $c->contentID . '.' . ($i + $j) . '">' . $c->desc . '</option>';		
							}
							$boxContent .= 		    '</select>
												</td>
											</tr>';
						}
						$boxContent .= '</table><br>';
						$boxContent .= '
						When do you want to make your site public?<br>
						<input type="text" value="' . $pa->published . '" name="publishDate"></br>
						<input type="submit" value="Save changes" name="changePageContent">
						</form>
						';
						$content .= $this->GetParent("GUI")->CreateBox('Editing page', $boxContent);
						break;
					case 'deletePage':																				//CASE DELETEPAGE
						$this->Datahandler->Pages->deletePage($_GET['id']);
						header("Location:../index.php?Page=CMS%2FChangePages&page=getPages");
					case 'getPages':																				//CASE GETPAGES
						$boxContent = '
							<form name="getPageForm" method="post" action="index.php?Page=CMS%2FChangePages">
								<table>
								<tr>
									<td>Page ID</td>
									<td>Contents</td>
									<td>Pagename</td>
									<td>Member </td>
									<td>Menu</td>
									<td>Published</td>
								</tr>
								';

							foreach ($pages as $value) 
							{
								$member = $this->Datahandler->Members->GetMemberByID($value->memberID);
								$contents = $this->Datahandler->Contents->GetAllPageContents($value->pageID);
							
								$boxContent .= '<tr class="CMStr"> ';
								$boxContent .= "<td>";
								$boxContent .= $value->pageID;
								$boxContent .= "</td>";
								$boxContent .= "<td>";
								foreach ($contents as $c)
								{
									$boxContent .= $c->desc . "<br>";
								}
								$boxContent .= "</td>";
								$boxContent .= "<td>";
								$boxContent .= $value->name;	
								$boxContent .= "</td>";
								$boxContent .= "<td>";
								$boxContent .= $member->name;
								$boxContent .= "</td>";
								$boxContent .= "<td>";
								$boxContent .= "<a href='index.php?Page=CMS%2FChangeMenus&MenuItem=" . $value->menuID . "&EditType=Change'>Edit here</a> your menu";
								$boxContent .= "</td>";
								$boxContent .= "<td>";
								$boxContent .= $value->published;
								$boxContent .= "</td>";
								$boxContent .= "<td> <a href='index.php?Page=CMS%2FChangePages&page=addPage&id=" . $value->pageID . "' style='color: #ccc;'> Add </a> </td>";
								$boxContent .= "<td> <a href='index.php?Page=CMS%2FChangePages&page=editPage&id=" . $value->pageID  . '&content='  . $value->contentID . " ' style='color: #ccc;'> Edit </a> </td>";
								$boxContent .= "<td> <a href='index.php?Page=CMS%2FChangePages&page=deletePage&id=" . $value->pageID . "' style='color: #ccc;'> Delete </a> </td>";
								$boxContent .= "</tr>";
							}
									
						$boxContent .= '
							</tr>
							</table>
							</form>

							';

						
						$content .= $this->GetParent("GUI")->CreateBox('Overview of pages', $boxContent);
									break;

					case 'addPage':			
					//CASE ADDPAGE
					$allMembers = $this->Datahandler->Members->GetAllMembers();
						$boxContent = '
							<form name="addPageForm" method="post" action="index.php?Page=CMS%2FChangePages" id="addPageForm">'; 
							$boxContent .= '<br>
							
							<label>Description of your page*</label><br>						
							<input type="text" placeholder="Description" name="newDescription"> <br><br>
							
							<label>Page name*</label><br>
							<input type="text" placeholder="Your page name" name="newPageName" autocomplete="off"> <br><br>
							
							<label>For Member*</label><br>
							<select name="selectedMember">';
							
					foreach ($allMembers as $member)
					{
						$boxContent .= '
								<option value="' . $member->memberID . '">' . $member->name . '</option>';
					}
						$boxContent .= '
							</select><br><br>
							
							<label>Publish date*</label><br>
							<input type="date" name="publishDate"><br><br>
							
							<label>Do you want to make your site public?*</label><br>
							<input type="radio" name="activeState" value="1">Yes
							<input type="radio" name="activeState" value="2">No<br>
							
							<input type="submit" value="Save changes" name="addNewPage">
							</form>';

						$content .= $this->GetParent("GUI")->CreateBox('Add a new page', $boxContent);
						break;
					default:
						# code...
						break;
				}
			}//END OF SWITCH STATEMENT
			
			if (isset($_POST["addNewPage"])) 
			{	
				if( isset($_POST['newDescription']) and 
					isset($_POST['newPageName'])	and 
					isset($_POST['publishDate']) 	and 
					isset($_POST['activeState']) 	and 
					isset($_POST['selectedMember']))
				{	
					$newDescription = $_POST['newDescription'];
					$newPageName    = $_POST['newPageName'];
					$publishDate    = $_POST['publishDate'];
					$memberID		= $_POST['selectedMember'];
					$public			= $_POST['activeState'];

					$this->Datahandler->Pages->addPage($public, $publishDate, $newDescription, $newPageName, $memberID);					
					header("Location:../index.php?Page=CMS%2FChangePages&page=getPages");
				}else{
					//The frontend could add a class to style this
					//div, to output the error clearly to the user.
					echo "<div style='background: red; text-align: center; color: #fff;'>Please fill all fields in</div>";

				}
				
			}

			return $content;
		}	
	}
?>	