<?php

class EventClass 
{

	private $DBH;

	public function __construct($dbh) {
		$this->DBH = $dbh;
	}
	
	/*Creates events for members, you can also choose when to show your event*/
	public function AddEvent($memberID, $startDate, $endDate, $showDate, $eventName) {

		$Stmt = $this->DBH->prepare('INSERT INTO events (Eventname, MemberID, StartDate, EndDate, ShowDate, Active) 
												 VALUES (:eventName, :memberID, :startDate, :endDate, :showDate, 1)');
		$Stmt->bindParam(':eventName', $eventName);
		$Stmt->bindParam(':memberID', $memberID);
		$Stmt->bindParam(':startDate', $startDate);
		$Stmt->bindParam(':endDate', $endDate);
		$Stmt->bindParam(':showDate', $showDate);
		$Stmt->execute();
	}
	
	/*Here you can edit an event*/
	public function EditEvent($eventID, $newStartDate, $newEndDate, $newShowDate, $newEventName, $active) {
        $Stmt = $this->DBH->prepare('UPDATE events SET EventName = :eventName, StartDate = :startDate, 
        EndDate = :endDate, ShowDate = :showDate, Active = :active
									 WHERE EventID = :eventID');
														
        $Stmt->bindParam(':eventName', $newEventName);
        $Stmt->bindParam(':startDate', $newStartDate);
        $Stmt->bindParam(':endDate', $newEndDate);
        $Stmt->bindParam(':showDate', $newShowDate);
		$Stmt->bindParam(':eventID', $eventID);
		$Stmt->bindParam(':active', $active);
        $Stmt->execute(); 
    }

	/*Here you deactived the event*/
	public function RemoveEvent($memberID, $eventID) {
        try{
            $Stmt = $this->DBH->prepare('UPDATE events SET Active = 0 WHERE eventID=:eventID');
            $Stmt->bindParam(':eventID', $eventID);
            $Stmt->execute();
            return true;
        }catch(PDOException $e){
            return false;
        }
	}

	/*Get Event*/
	public function GetEvent($eventID) {
		$event = new Event ();

		$Stmt = $this->DBH->prepare('SELECT * FROM events
									 WHERE  EventID = :eventID');
		$Stmt->bindParam(':eventID', $eventID);
		$Stmt->execute();

		while ($row = $Stmt->fetch(PDO::FETCH_ASSOC)) {
			$event->memberID = $row['MemberID'];
			$event->eventID = $row['EventID'];
			$event->startDate = $row['StartDate'];
			$event->endDate = $row['EndDate'];
			$event->showDate = $row['ShowDate'];
			$event->eventName = $row['EventName'];
			$event->active = $row['Active'];
		}
		return $event;
	}

	/*This gets all events*/
	//GetAllActiveEvents created By Jorrit Overeem
	public function GetAllActiveEvents() {
		$events = Array();

		$stmt = $this->DBH->prepare('SELECT * FROM events WHERE Active = 1');
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$event = new Event();

			$event->memberID = $row['MemberID'];
			$event->eventID = $row['EventID'];
			$event->startDate = $row['StartDate'];
			$event->endDate = $row['EndDate'];
			$event->showDate = $row['ShowDate'];
			$event->eventName = $row['EventName'];
			
			array_push($events, $event);
		}
		return $events;
	}
	
		public function GetAllEvents() {
		$events = Array();

		$stmt = $this->DBH->prepare('SELECT * FROM events');
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$event = new Event();

			$event->memberID = $row['MemberID'];
			$event->eventID = $row['EventID'];
			$event->startDate = $row['StartDate'];
			$event->endDate = $row['EndDate'];
			$event->showDate = $row['ShowDate'];
			$event->eventName = $row['EventName'];
			$event->active = $row['Active'];
			
			array_push($events, $event);
		}
		return $events;
	}
	
	/*This gets all events by member ID's*/
	public function GetAllEventsByMemberID($memberID) {
		$events = Array();
		
		$stmt = $this->DBH->prepare('SELECT * FROM events WHERE MemberID = :mID and Active = 1');
		$stmt->bindParam(':mID', $memberID);
		$stmt->execute();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$event = new Event();
			
			$event->memberID = $row['MemberID'];
			$event->eventID = $row['EventID'];
			$event->startDate = $row['StartDate'];
			$event->endDate = $row['EndDate'];
			$event->showDate = $row['ShowDate'];
			$event->eventName = $row['EventName'];
			
			array_push($events, $event);
		}
		return $events;
	}
}

class Event
{
	public $memberID;
	public $eventID;
	public $startDate;
	public $endDate;
	public $showDate;
	public $eventName;
	public $active;
}

?>