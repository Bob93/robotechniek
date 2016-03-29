<?php
	class GUI extends index
	{
		public function Start()
		{	
			$this->AddStyle("CMS/CMSstyle");
			$this->AddScript("jQuery1_11");
		}
		
		public function CheckUser()
		{
			$ifAdmin = false;
			
			if (isset($_SESSION["loggedUser"]))
			{
				if ($_SESSION["loggedUser"]->userType == 2)
				{
					$ifAdmin = true;
				}
			}
			
			if (!$ifAdmin)
			{
				header('Location: /index.php?Page=CMS%2FCMSLogin');
			}
			else
			{
				$this->AddScript("jquery-ui-1.10.4.custom");
				$this->AddScript("CMS/CMSmenuscript");
				$this->AddScript("ckeditor/ckeditor");
				$this->AddStyle("jquery-ui-1.10.4.custom");
			}
		}
		
		public function GetCMSMenu()
		{
			return '
			<div id="navMainMenu">
				<a class="navMenuItem" style="display:block;" href="Engine/LogoutPage.php">
					<div>
						<label class="navMenuLabel">Logout</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeContents&content=load">
					<div>
						<label class="navMenuLabel">Contents</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeEvents">
					<div>
						<label class="navMenuLabel">Events</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeFiles">
					<div>
						<label class="navMenuLabel">Files</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeFooter">
					<div>
						<label class="navMenuLabel">Footer</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeHeader">
					<div>
						<label class="navMenuLabel">Header</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeMembers">
					<div>
						<label class="navMenuLabel">Members</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeUsers">
					<div>
						<label class="navMenuLabel">Users</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangeMenus">
					<div>
						<label class="navMenuLabel">Menus</label>
					</div>
				</a>
				<a class="navMenuItem" style="display:block;" href="index.php?Page=CMS%2FChangePages&page=getPages">
					<div>
						<label class="navMenuLabel">Pages</label>
					</div>
				</a>
			</div>
			<style>
				a{
					color: #4189ce;
				}
				a:hover{
					color: #fff;
				}
			</style>
			';
		}
		
		public function CreateBox($title, $content)
		{
			$box =  '<div class="CMSBoxNoRes">';
				$box .= '<div class="CMSTitleNoRes">';
					$box .= $title;
				$box .= '</div>';
				$box .= '<div class="CMSBoxContentNoRes">';
					$box .= $content;
				$box .= '</div>';
			$box .= '</div>';
			
			return $box;
		}
		
		public function CreateBoxWithSize($title, $content, $height, $width)
		{
			$box =  '<div class="CMSBoxNoRes" style="height:' . $height . '%; width:' . $width . '%">';
				$box .= '<div class="CMSTitleNoRes">';
					$box .= $title;
				$box .= '</div>';
				$box .= '<div class="CMSBoxContentNoRes">';
					$box .= $content;
				$box .= '</div>';
			$box .= '</div>';
			
			return $box;
		}
	}	
?>