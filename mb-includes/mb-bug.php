<?php
/**
 * Bug Class
 * 
 */

Class Bug {
	public $id;
	public $title;
	public $description;
	public $email;
	public $IP;
	public $date;
	public $name;
	public $votes;
	public $current;
	
	public function __construct( $inId = null, $inTitle = null, $inDesc = null, $inEmail = null, $inIP = null, $inDate = null, $inVotes = null, $inCurrent = null ) {
		if ( !empty( $inId ) ) {
			$this->id = $inId;
		}
		
		if ( !empty( $inTitle ) ) {
			$this->title = $inTitle;
		}
		
		if ( !empty( $inDesc ) ) {
			$this->description = $inDesc;
		}
		
		if ( !empty( $inEmail ) ) {
			$this->email = $inEmail;
			
			$this->name = explode( '@', $inEmail);
			$this->name = $this->name[0];
		}
		
		if ( !empty( $inIP ) ) {
			$this->IP = $inIP;
		}
		
		if ( !empty( $inDate ) ) {
			// MySQL format: 2011-11-30-November-Wednesday
			$this->date = explode( '-', $inDate);
			
			$year = $this->date[0];
			$monthNum = $this->date[1];
			$dayNum = $this->date[2];
			$monthStr = $this->date[3];
			$dayStr = $this->date[4];
			
			$this->date = $dayStr . ', ' . $monthStr . ' ' . $dayNum . ', ' . $year;
		}
		
		if ( !empty( $inVotes ) ) {
			$this->votes = $inVotes;
		}
		
		if ( !empty( $inCurrent ) ) {
			$this->current = $inCurrent;
		}
	}
}
?>