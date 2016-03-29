<?php

class MenuClass
{
	private $DBH;

	public function __construct($dbh)
	{
		$this->DBH = $dbh;
	}

	/* Creates new Menu, auto increment a new MenuID in table "menu" and create description with given parameter. MenuItems have to be added with the function "addMenuItem" */
	/* Tested and accepted */
	/* Krijn van der Burg - 05-06-2014 */
	public function AddMenu($description)
	{
		$Stmt = $this->DBH->prepare('INSERT INTO menu (Description)
										VALUES (:description)');
		$Stmt->bindParam(':description', $description);
		$Stmt->execute();
	}
	
	/* Set Menu and all MenuItemIDs given through parameter menuID to Unactive / actice = 0 */
	/* Tested and accepted */
	/* Krijn van der Burg - 05-06-2014 */
	public function RemoveMenu($menuID)
	{
		$Stmt = $this->DBH->prepare('UPDATE menuitems SET Active = :active
									WHERE menuItemID = :menuItemID');
		$Stmt->bindParam(':menuID', $menuID);
		$Stmt->execute();
	}

	/* Add a new MenuItem to the given MenuID. Title, Label and link can all be 0 / NULL this will leave the field inside the database empty with no errors. */
	/* Tested and accepted */
	/* Krijn van der Burg - 05-06-2014 */
	public function AddMenuItem($MenuID, $newLabel, $newTitle, $newLink)
	{
		$Stmt = $this->DBH->prepare('INSERT INTO menuitems (MenuID, MenuLabel, MenuTitle, MenuLink, Active) 
									VALUES (:MenuID, :newLabel, :newTitle, :newLink, 1)');
		$Stmt->bindParam(':MenuID', $MenuID);
		$Stmt->bindParam(':newLabel', $newLabel);
		$Stmt->bindParam(':newTitle', $newTitle);
		$Stmt->bindParam(':newLink', $newLink);
		$Stmt->execute();
	}
	
	/* Edit and update the MenuItemLabel with the given parameter */
	/* Tested and accepted */
	/* Krijn van der Burg - 04-06-2014 */
	public function EditMenuItemLabel($menuID, $menuItemID, $newLabel)
	{
		$Stmt = $this->DBH->prepare('UPDATE menuitems SET MenuLabel = :newLabel
									WHERE  menuID = :menuID AND menuItemID = :menuItemID');
		$Stmt->bindParam(':newLabel', $newLabel);
		$Stmt->bindParam(':menuID', $menuID);
		$Stmt->bindParam(':menuItemID', $menuItemID);
		$Stmt->execute();
	}

	/* Edit and update the MenuItemTitle with the given parameter */
	/* Tested and accepted */
	/* Krijn van der Burg - 04-06-2014 */
	public function EditMenuItemTitle($menuID, $menuItemID, $newTitle)
	{
		$Stmt = $this->DBH->prepare('UPDATE menuitems SET MenuTitle = :newTitle
									WHERE  menuID = :menuID AND menuItemID = :menuItemID');
		$Stmt->bindParam(':newTitle', $newTitle);
		$Stmt->bindParam(':menuID', $menuID);
		$Stmt->bindParam(':menuItemID', $menuItemID);
		$Stmt->execute();
	}

	/* Edit and update the MenuItemLink with the given parameter */
	/* Tested and accepted */
	/* Krijn van der Burg - 04-06-2014 */
	public function EditMenuItemLink($menuID, $menuItemID, $newLink)
	{	
		$Stmt = $this->DBH->prepare('UPDATE menuitems SET MenuLink = :newLink
									WHERE  menuID = :menuID AND menuItemID = :menuItemID');
		$Stmt->bindParam(':newLink', $newLink);
		$Stmt->bindParam(':menuID', $menuID);
		$Stmt->bindParam(':menuItemID', $menuItemID);
		$Stmt->execute();
	}
	
	/* Set menuItem given through parameter to Unactive / actice = 0 */
	/* Tested and accepted */
	/* Krijn van der Burg - 12-6-2014 */
	public function RemoveMenuItem($menuItemID)
	{
		$Stmt = $this->DBH->prepare('UPDATE menuitems SET Active = 0
									WHERE menuItemID = :menuItemID');
		$Stmt->bindParam(':menuItemID', $menuItemID);
		$Stmt->execute();
	}

	/* Returns whole menu with its menuitems */
	/* Tested and accepted */
	/* Person - Date */
	public function GetMenu($menuID)
	{
		$menu = new Menu();

		$Stmt = $this->DBH->prepare('SELECT * FROM menuitems
									 WHERE  MenuID = :menuID');
		$Stmt->bindParam(':menuID', $menuID);
		$Stmt->execute();

		while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			$menuItem = new MenuItem();

			$menuItem->menuItemID = $row['MenuItemID'];
			$menuItem->menuLabel = $row['MenuLabel'];
			$menuItem->menuTitle = $row['MenuTitle'];
			$menuItem->menuLink = $row['MenuLink'];
			$menuItem->menuID = $row['MenuID'];

			array_push($menu->menuItems, $menuItem);
		}

		return $menu;
	}

	public function GetMenuByMenuItemID($menuItemID)
	{
		$menu = new Menu();

		$Stmt = $this->DBH->prepare('SELECT * FROM menuitems
									 WHERE  MenuItemID = :menuItemID');
		$Stmt->bindParam(':menuItemID', $menuItemID);
		$Stmt->execute();

		while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			$menuItem = new MenuItem();

			$menuItem->menuItemID = $row['MenuItemID'];
			$menuItem->menuLabel = $row['MenuLabel'];
			$menuItem->menuTitle = $row['MenuTitle'];
			$menuItem->menuLink = $row['MenuLink'];
			$menuItem->menuID = $row['MenuID'];

			array_push($menu->menuItems, $menuItem);
		}

		return $menu;
	}

	public function GetAllMenuItems($menuID)
	{
		$menuItems = array();

		$stmt = $this->DBH->prepare('SELECT * FROM menuitems where MenuID = :menuID and active = 1');
		$stmt->bindParam(":menuID", $menuID);

		$stmt->execute();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$menuItem = new MenuItem();

			$menuItem->menuItemID = $row['MenuItemID'];
			$menuItem->menuLabel = $row['MenuLabel'];
			$menuItem->menuTitle = $row['MenuTitle'];
			$menuItem->menuLink = $row['MenuLink'];
			$menuItem->menuID = $row['MenuID'];

			array_push($menuItems, $menuItem);
		}

		return $menuItems;
	}
}

class Menu
{
	public $menuItems = Array();
}

class MenuItem
{
	public $menuItemID;
	public $menuLabel;
	public $menuTitle;
	public $menuLink;
	public $menuID;
}

?>