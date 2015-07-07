<?php
/**
 * User Class
 * 
 */

Class User {
	public $id;
	public $username;
	public $email;
	
	public function __construct( $inId = null, $inUsername = null, $inEmail = null ) {
		if ( !empty( $inId ) ) {
			$this->id = $inId;
		}
		
		if ( !empty( $inUsername ) ) {
			$this->username = $inUsername;
		}
		
		if ( !empty( $inEmail ) ) {
			$this->email = $inEmail;
		}
	}
}
?>