<?php

class ChangeMenusClass extends Index {
	private $allMembers;
	private $allPages;

	public function Start() {
		$this->GetParent("GUI")->CheckUser();
		$this->allDBPages = $this->Datahandler->Pages->GetAllPages();
		$this->allPHPPages = array();
	}

	public function GetMenuBox() {
		$selectedMenuItem = 0;
		$selectedMenu = 1;
		$editType = 0;
		$selectedPageID = 1;

		$ds = new DirectoryScan();
		$ds->ScanDirectory("Pages", "php", 1);
		$this->allPHPPages = $ds->foundFolders;

		if (isset($_SESSION["selectMenu"])) {
			$selectedMenu = $_SESSION["selectMenu"];
		}

		if (isset($_POST["selectMenu"])) {
			$_SESSION["selectMenu"] = $_POST["selectMenu"];
			$selectedMenu = $_POST["selectMenu"];
		}
		
		if (isset($_POST["newMenuItemLabel"]) and isset($_POST["newMenuItemTitle"]) and isset($_POST["selectedLink"])) {
			$this->Datahandler->Menus->AddMenuItem($selectedMenu, 
				$_POST["newMenuItemLabel"], 
				$_POST["newMenuItemTitle"], 
				$_POST["selectedLink"]);
		}
		
		if (isset($_GET["EditType"])) {
			if ($_GET["EditType"] == "Edit") {	
				$this->Datahandler->Menus->EditMenuItemLabel($selectedMenu, $_GET["MenuItem"], $_POST["menuLabelInput"]);
				$this->Datahandler->Menus->EditMenuItemTitle($selectedMenu, $_GET["MenuItem"], $_POST["menuTitleInput"]);
				$this->Datahandler->Menus->EditMenuItemLink( $selectedMenu, $_GET["MenuItem"], $_POST["selectedLink"]);
				unset($_GET["MenuItem"]);
			}
		}

		if (isset($_GET["MenuItem"]) and isset($_GET["EditType"])) {
			$selectedMenuItem = $_GET["MenuItem"];
			
			if ($_GET["EditType"] == "Delete") {
				$this->Datahandler->Menus->RemoveMenuItem($selectedMenuItem);
			}
		}
		
		$menuItems = $this->Datahandler->Menus->GetAllMenuItems($selectedMenu);

		/* Create HTML Form */
		$value = '<form method="POST" action="index.php?Page=CMS%2FChangeMenus">
				<select name="selectMenu" onchange="this.form.submit()">';
		if ($selectedMenu == 1) {
			$value .= ' <option value="1" selected="selected">Public</option>';
		}
		
		else {
			$value .= ' <option value="1">Public</option>';
		}
				
		if ($selectedMenu == 2) {
			$value .= ' <option value="2" selected="selected">Private</option>';
		}
		
		else {
			$value .= ' <option value="2">Private</option>';
		}

		$value .= '</select></form>
				<table>
					<tr>
						<td>Label</td>
						<td>Title</td>
						<td>Link to Page</td>
					</tr>';

		foreach ($menuItems as $menuItem) {
			if ($selectedMenuItem != $menuItem->menuItemID) {
				$value .='<tr>
						<td>' . $menuItem->menuLabel . '</td>
						<td>' . $menuItem->menuTitle . '</td>
						<td>' . $menuItem->menuLink . '</td>';

				$value .= '
						<td><a href="index.php?Page=CMS%2FChangeMenus&MenuItem=' .  $menuItem->menuItemID . '&EditType=Change"><button>Change</button></a></td>
						<td><a href="index.php?Page=CMS%2FChangeMenus&MenuItem=' .  $menuItem->menuItemID . '&EditType=Delete"><button>Delete</button></a></td>
					</tr>';
			}

			else {
				$value .= '	
					<tr>
						<form method="POST" action="index.php?Page=CMS%2FChangeMenus&MenuItem=' . $menuItem->menuItemID . '&EditType=Edit">
							<td><input name="menuLabelInput" type="text" value="' . $menuItem->menuLabel . '"</td>
							<td><input name="menuTitleInput" type="text" value="' . $menuItem->menuTitle . '"</td>
							<td><select name="selectedLink">';

				foreach ($this->allDBPages as $page) {
					$url = "index.php?Page=MemberPage&PageID=" . $page->pageID;
					$value .= '	<option value="' . $url . '">' . $page->name . '</option>';
				}

				foreach ($this->allPHPPages as $page) {
					$sPage = explode("/", $page);
					unset($sPage[0]);
					$link = "";				
					$i = 0;

					foreach ($sPage as $s) {
						$link .= $s;
						
						if ($i != count($sPage) - 1) {
							$link .= '%2F';
						}
						++$i;
					}
					
					$url = "index.php?Page=" . $link;
					$value .= '<option value="' . $url . '">' . $sPage[count($sPage)] . '</option>';
				}

				$value .= '</select></td>
							<td><button type="submit">Save</button></td>	
						</form>
						<td><a href="index.php?Page=CMS%2FChangeMenus"><button>Cancel</button></a></td>
					</tr>';
			}
		}

		$value .= '</table>';

		return $value;
	}
	
	public function GetNewMenuItemBox() {
		$value = '		
			<table>
				<form method="post" action="index.php?Page=CMS%2FChangeMenus">
					<tr>
						<td>Label</td>
						<td>Title</td>
						<td>Link to Page</td>
					</tr>
					<tr>
						<td><input type="text" name="newMenuItemLabel"></td>
						<td><input type="text" name="newMenuItemTitle"></td>
						<td>
						<select name="selectedLink">';

							foreach ($this->allDBPages as $page) {
								$url = "index.php?Page=MemberPage&PageID=" . $page->pageID;
								$value .= '			<option value="' . $url . '">' . $page->name . '</option>';
							}

							foreach ($this->allPHPPages as $page) {
								$sPage = explode("/", $page);
								unset($sPage[0]);
								$link = "";		
								$i = 0;
								
								foreach ($sPage as $s) {
									$link .= $s;
									if ($i != count($sPage) - 1) {
										$link .= '%2F';
									}
									++$i;
								}
											
								$url = "index.php?Page=" . $link;
								$value .= '<option value="' . $url . '">' . $sPage[count($sPage)] . '</option>';
							}

							$value .= '</select>
						</td>
					</tr>
					<tr>
						<td><input type="Submit" value="Save"></td>
					</tr>
				</form>
				<tr>
					<td><a href="index.php?Page=CMS%2FChangeMenus&selectedMenu=0"><button>Cancel</button></a></td>
				</tr>
			</table>
		</form>';

		return $value;
	}
	
	public function GetContent() {
		$content = '';
		$content .= $this->GetParent("GUI")->GetCMSMenu();
		$content .= $this->GetParent("GUI")->CreateBox("Menu Selection", $this->GetMenuBox());
		$content .= $this->GetParent("GUI")->CreateBox("New Menu Item", $this->GetNewMenuItemBox());
		return $content;
	}
}
?>