<?php

class CompanyPageClass extends Index {

	public function Start()	{

		$this->MetaTags->setDescription("Hier kunt u alle bedrijven die staan aangemeld bij robo vinden en verdere informatie vinden");
        $this->MetaTags->addKeyWords(array("Bedrijf", "Bedrijven", "Aangemeld", "Informatie", "Info", "Organisatie", "lid", "leden"));
        $this->MetaTags->setAuthor("ROBO");

        Index::$title = "ROBO Bedrijven";
	}

	public function GetContent() {
		$members = $this->Datahandler->Members->GetAllMembers();
		if (isset($_GET['MemberID'])) {
			$memberID = $_GET['MemberID'];
		} else {
			$memberID = 0;
		}
		$users = $this->Datahandler->Users->GetAllUserIDsByMemberID($memberID);
		$menu = $this->Datahandler->Menus->GetMenu(2);
		$value='';
		$content='	
			<div class="col-md-9">
				<div class="c-container">
					<div class="content">
						<div class="contenttext">
							<div class="row">
								<div class = "col-md-6">
									<p> ';	
									foreach($members as $member){
										$content .= '
										<ul class="companylist">
											<li >
											<a href="index.php?Page=Private%2FCompanyPage&MemberID='.$member->memberID.'">'.$member->name.'</a>
											</li>
										</ul>';
									}
		$content .= '				</p>
								</div>
								<div class = "col-md-6">';
								if (isset($_GET['MemberID'])){
									$content .= '<div class="ComMembers">';
									foreach ($users as $userID){
										$user = $this->Datahandler->Users->GetUserByID($userID);
										$content .= '<li>' . $user->firstName . ' ' .$user->lastName. '</li><br>';
									}
									$content .= '</div>';
								}
		$content .= '			</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			</div>';

		$value .= $this->GetParent("Standard")->GetStandardPage($content);
		return $value;
	}
	
}

?>