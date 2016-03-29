<?php

class ColofonPageClass extends Index
{
	public function Start()
	{		
		Index::$title = "Colofon";
	}

	public function GetContent()
	{//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
//Now, the headercontent, content and footer is retrieved from the database. Sjoerd Hageman 18-06-2014
			$menu=$this->Datahandler->Menus->GetMenu(1);
			//$c=$this->Datahandler->Contents->getContent(8);
			$value='';
			$content = '
		<div id="scrollbox" class="mCustomScrollbar">
		<div class="col-md-12">   
			<div class="c-container">		
			<div class="content">                   
					<div class="contenttext">
					<div class="row">
							<div class="col-md-12">
																	<div class="row">
									<p><b>Design:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/KellyVanVerseveld.jpg" alt="Kelly Van Verseveld" width="50" height="75"></a> Kelly van Verseveld.</br>
									</div>								
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MikeKersten.jpg" alt="Mike Kersten" width="50" height="75"></a> Mike Kersten.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Gerardo" width="50" height="75"></a> Gerardo.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MikanSimonis.jpg" alt="Mikan Simonis" width="50" height="75"></a> Mikan Simonis.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Database:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Sean Arntz" width="50" height="75"></a> Sean Arntz.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/LouisVerbeet.jpg" alt="Louis Verbeet" width="50" height="75"></a> Louis Verbeet.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/KrijnVanDerBurg.jpg" alt="Krijn Van Der Burg" width="50" height="75"></a> Krijn van der Burg.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Engine:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/ErwinJansen.jpg" alt="Erwin Jansen" width="50" height="75"></a> Erwin Jansen.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Beveiliging:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/JoostHoutbeckers.jpg" alt="Joost Houtbeckers" width="50" height="75"></a> Joost Houtbeckers.</br>
									</div>									
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/JorritOvereem.jpg" alt="Jorrit Overeem" width="50" height="75"></a> Jorrit Overeem.</br>
									</div>
								</div>								
								<div class="row">
									<p><b>Front-end public:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Jorian Van Der Kolk" width="50" height="75"></a> Jorian van der Kolk.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MauriceBijron.jpg" alt="Maurice Bijron" width="50" height="75"></a> Maurice Bijron.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/SjoerdHageman.jpg" alt="Sjoerd Hageman" width="50" height="75"></a> Sjoerd Hageman.</br>
									</div>							
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MikanSimonis.jpg" alt="Mikan Simonis" width="50" height="75"></a> Mikan Simonis.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Front-end private:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/PieterDouma.jpg" alt="Pieter Douma" width="50" height="75"></a> Pieter Douma.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/AymoTimmerman.jpg" alt="Aymo Timmerman" width="50" height="75"></a> Aymo Timmerman.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MartijnVanIersel.jpg" alt="Martijn Van Iersel" width="50" height="75"></a> Martijn van Iersel.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Back-end:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/MuhammedHabibullahBudak.jpg" alt="Muhammed Habibullah Budak" width="50" height="75"></a> Muhammed H. Budak.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/Damir.jpg" alt="Damir Dzilic" width="50" height="75"></a> Damir Dzilic.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/OsmanSafaKaya.jpg" alt="Osman Safa Kaya" width="50" height="75"></a> Osman Safa Kaya.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/Abdussamed.jpg" alt="Abdussamed Ferhat Adiguzel" width="50" height="75"></a> Abdussamed F. Adigüzel.</br>
									</div>										
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/ErwinJansen.jpg" alt="Erwin Jansen" width="50" height="75"></a> Erwin Jansen.</br>
									</div>			
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/JorritOvereem.jpg" alt="Jorrit Overeem" width="50" height="75"></a> Jorrit Overeem.</br>
									</div>		
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/JoostHoutbeckers.jpg" alt="Joost Houtbeckers" width="50" height="75"></a> Joost Houtbeckers.</br>
									</div>
								</div>
								<div class="row">
									<p><b>Management:</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Michels Lorenz" width="50" height="75"></a> Michels Lorenz.</br>
									</div>
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Meno But" width="50" height="75"></a> Menno But.</br>
									</div>
									<div class="col- md-4 col-xs-4">
										<img class="photo" src="Images/personen/GeenFoto.jpg" alt="Dick Sondaar" width="50" height="75"></a> Dick Sondaar.</br>
									</div>									
								</div>
								<div class="row">
									<p><b>Hulp en Assistentie</b></p>
									<div class="col-md-4 col-xs-4">
										<img class="photo"  src="Images/personen/SanderNijhuis.jpg" alt="Sander Nijhuis Sondaar" width="50" height="75"></a> Sander Nijhuis.</br>
									</div>								
									<div class="col-md-4 col-xs-4">
										<img class="photo" src="Images/personen/YsbrandVanEijck.jpg" alt="Ysbrand Van Eijck" width="50" height="75"></a> Ysbrand van Eijck.</br>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<p>
										</br>
										</br>
										</br>
										</p>
									</div>
								</div>
							
								</div>
						</div>
						</div>
				</div>
			</div>
		</div>
		</div>
		</div>
	</div>
	';
			$value .= $this->GetParent("Standard")->GetStandardPage($content);
			return $value;
	}
	
}

?>