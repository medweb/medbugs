<?php
/**
 * 
 * Database abstraction layer
 * 
 * No database queries are made independently, or
 * on-the-fly within application files.
 * They must be called from functions within this file,
 * or the necessary function should be added.
 * 
 * @PARAM Bug table
 * bug_id: int
 * title: text
 * description: longtext
 * email: varchar
 * date: varchar
 * votes: smallint
 * status: boolean (default 1 = active, 0 = resolved)
 * current: boolean (default 0 = not currently being worked on, 1 = currently being worked on)
 * 
 * @PARAM User table
 * user_id: int
 * username: varchar
 * password: varchar
 * email: varchar
 * session_id: varchar
 * session_start: timestamp
 * 
 * @PARAM Options table
 * sidebar_content: longtext
 * 
 */

Class Database_Connection {
	private $connect;
	private $pass_salt = '6a5e81779579';
	private $session_salt = 'Qecehab2tHaz';
	
	public function __construct() {}
	
	/** 
	 * Open database connection
	 * 
	 */
	public function open() {
		global $HOST;
		global $USER;
		global $PASSWORD;
		global $DATABASE;
		global $connect;
		
		$connect = mysql_connect( $HOST, $USER, $PASSWORD );
		if ( !$connect ) die( 'Could not connect: ' . mysql_error() );
		mysql_select_db( $DATABASE, $connect );
	}
	
	/**
	 * Close database connection
	 * 
	 */
	private function close() {
		global $connect;
		
		mysql_close( $connect );
	}
	
	/** 
	 * Sanitize data
	 * 
	 */
	public function sanitize( $data ) {
		$data = htmlentities( trim( $data ), ENT_NOQUOTES );
		$data = mysql_real_escape_string( $data );
		
		return $data;
	}
	
	/** 
	 * Verify login credentials
	 * 
	 */
	public function is_user( $user, $pass ) {
		$pass = $this->pass_salt . sha1( $pass );
		
		$query = "SELECT user_id FROM user WHERE username='$user' AND password='$pass'";
		
		if ( mysql_num_rows( mysql_query( $query ) ) ) {
			return true;
		}
		
		return false;
	}
	
	/** 
	 * Start user session
	 * 
	 */
	public function start_session( $username ) {
		session_start();
		
		$query = mysql_query( "SELECT * FROM user WHERE username='$username'" );
		$result = mysql_fetch_array( $query );
		
		$user_id = $result['user_id'];
		$session_id = $this->session_salt . sha1( rand( 1111111, 9999999 ) );
		$session_start = time();
		
		$_SESSION['MedBugs_user_id'] = $user_id;
		$_SESSION['MedBugs_session_id'] = $session_id;
		
		$query = mysql_query( "UPDATE user SET session_id='$session_id', session_start='$session_start' WHERE user_id='$user_id'" );
	}
	
	/** 
	 * Verify user session and refresh the cookie and session_start
	 * 
	 * This system utilizes timed sessions. If the user is inactive
	 * for 30 or more minutes, their session will expire.
	 * 
	 */
	public function verify_session( $user_id = null, $session_id = null ) {
		if ( !empty( $user_id ) && !empty( $session_id ) ) {
			$user_id = $this->sanitize( $user_id );
			$session_id = $this->sanitize( $session_id );
			
			$query = mysql_query( "SELECT session_start FROM user WHERE user_id='$user_id' AND session_id='$session_id'" );
			$result = mysql_fetch_array( $query );
			
			if ( 0 != $result[0] && time() < $result[0] + TIMEOUT ) {
				$session_start = time();
				$query = mysql_query( "UPDATE user SET session_start='$session_start' WHERE user_id='$user_id' AND session_id='$session_id'" );
				
				$_SESSION['MedBugs_user_id'] = $user_id;
				$_SESSION['MedBugs_session_id'] = $session_id;
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * End session
	 * 
	 * Destroy the session and close the DB connection
	 * 
	 */
	public function end_session( $user_id, $session_id ) {
		$query = mysql_query( "UPDATE user SET session_id='', session_start='' WHERE user_id='$user_id' AND session_id='$session_id'" );
		
		session_destroy();
		
		$this->close();
	}
	
	/** 
	 * Create new bug
	 * 
	 */
	public function create_bug( $title, $description, $email, $IP, $date ) {
		$title = $this->sanitize( $title );
		$description = $this->sanitize( $description );
		$email = $this->sanitize( $email );
		$IP = $this->sanitize( $IP );
		$timestamp = $this->sanitize( $date );
		
		mysql_query( "INSERT INTO bug (title, description, email, submit_ip, date) VALUES ('$title', '$description', '$email', '$IP', '$date')" );
	}
	
	/**
	 * Vote bug up
	 * 
	 */
	public function vote_bug_up( $bug_id ) {
		$bug_id = $this->sanitize( $bug_id );
		
		$query = mysql_query( "SELECT votes FROM bug WHERE bug_id='$bug_id'" );
		$result = mysql_fetch_array( $query );
		$currentVotes = $result[0];
		++$currentVotes;
		
		mysql_query( "UPDATE bug SET votes='$currentVotes' WHERE bug_id='$bug_id'" );
		
		return $currentVotes;
	}
	
	/** 
	 * Resolve bug
	 * 
	 */
	public function resolve_bug( $bug_id ) {
		$bug_id = $this->sanitize( $bug_id );
		
		mysql_query( "UPDATE bug SET status='0', current='0' WHERE bug_id='$bug_id'" );
	}
	
	/**
	 * Set bug as currently being worked on
	 * 
	 */
	public function set_bug_as_current( $bug_id ) {
		$bug_id = $this->sanitize( $bug_id );
		
		mysql_query( "UPDATE bug SET current='1' WHERE bug_id='$bug_id'" );
	}
	
	/** 
	 * List current bugs 
	 * 
	 */
	public function get_bugs( $args = null ) {
		if ( !null == $args ) {
			// defaults
			$order = 'DESC';
			$order_by = "ORDER BY bug_id $order";
			$status = "status='1'";
			$current = '';
			
			foreach ( $args as $key => $value ) {
				// order
				if ( 'order' == $key ) {
					if ( 'ASC' == $value || 'DESC' == $value ) {
						$order = $value;
					} else {
						$order = 'DESC';
					}
				}
				
				// order by
				if ( 'order_by' == $key ) {
					if ( is_array( $value ) ) {
						$primary = $value['primary'];
						$secondary = $value['secondary'];
						
						$order_by = "ORDER BY $primary $order, $secondary $order";
					} else if ( 'email' == $value || 'date' == $value || 'votes' == $value || 'status' == $value || 'bug_id' == $value || 'title' == $value || 'current' == $value ) {
						$order_by = 'ORDER BY ' . $value . ' ' . $order;
					} else {
						$order_by = "ORDER BY bug_id $order";
					}
				}
				
				// status
				if ( 'status' == $key ) {
					if ( 'active' == $value || 'resolved' == $value ) {
						if ( 'active' == $value ) {
							$status = "status='1'";
						} else {
							$status = "status='0'";
						}
					} else {
						$status = "status='1'";
					}
				}
				
				// current
				if ( 'current' == $key ) {
					if ( true == $value || false == $value ) {
						if ( true == $value ) {
							$current = 1;
							$current = "AND current='1'";
						} else {
							$current = 0;
							$current = "AND current='0'";
						}
					} else {
						$current = "AND current='0'";
					}
				}
			}
			
			$query = mysql_query( "SELECT * FROM bug WHERE $status $current $order_by" );
		} else {
			$query = mysql_query( "SELECT * FROM bug ORDER BY bug_id DESC" );
		}
		
		$bugArray = array();
		
		while ( $row = mysql_fetch_assoc( $query ) )  {  
			$bug = new Bug( $row['bug_id'], $row['title'], $row['description'], $row['email'], $row['submit_ip'], $row['date'], $row['votes'], $row['current'] );  
			array_push( $bugArray, $bug );  
		}  
		
		return $bugArray;
	}

	/**
	 * Display sidebar content
	 * 
	 */
	public function the_sidebar_content() {
		$query = mysql_query( "SELECT sidebar_content FROM options" );
		$result = mysql_fetch_array( $query );
		
		return $result[0];
	}
	
	/**
	 * Update sidebar content
	 * 
	 */
	public function update_sidebar( $html_string ) {
		// FIXME allow single and double quotes
		
		$query = mysql_query( "UPDATE options SET sidebar_content='$html_string'" );
	}
	
	/**
	 * Create new user
	 * 
	 */
	public function create_user( $username, $email, $password ) {
		$username = $this->sanitize( $username );
		$email = $this->sanitize( $email );
		$password = $this->sanitize( $password );
		$password = $this->pass_salt . sha1( $password );
		
		mysql_query( "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email')" );
	}

	/** 
	 * Get user password
	 * 
	 */
	private function get_user_password( $user_id ) {
		$query = mysql_query( "SELECT password FROM user WHERE user_id='$user_id'" );
		$result = mysql_fetch_array( $query );
		
		return $result[0];
	}
	
	/**
	 * Change user password
	 * 
	 */
	public function change_user_password( $user_id, $oldPassword, $newPassword ) {
		$user_id = $this->sanitize( $user_id );
		
		$oldPassword = $this->sanitize( $oldPassword );
		$oldPassword = $this->pass_salt . sha1( $oldPassword );
		
		$newPassword = $this->sanitize( $newPassword );
		$newPassword = $this->pass_salt . sha1( $newPassword );
		
		$currentPassword = $this->get_user_password( $user_id );
		
		if ( $oldPassword == $currentPassword ) {
			mysql_query( "UPDATE user SET password='$newPassword' WHERE user_id='$user_id'" );
			
			return 'password updated';
		} else {
			return 'passwords didn\'t match';
		}
		
		return 'unknown error';
	}
	
	/**
	 * Delete user
	 * 
	 */
	public function delete_user( $user_id ) {
		$user_id = $this->sanitize( $user_id );
		
		mysql_query( "DELETE FROM user WHERE user_id='$user_id'" );
	}
	
	/**
	 * List users
	 * 
	 */
	public function get_users( $args = null ) {
		if ( !null == $args ) {
			// defaults
			$order = 'DESC';
			$order_by = 'user_id';
			
			foreach ( $args as $key => $value ) {
				// order
				if ( 'order' == $key ) {
					if ( 'ASC' == $value || 'DESC' == $value ) {
						$order = $value;
					} else {
						$order = 'DESC';
					}
				}
				
				// order by
				if ( 'order_by' == $key ) {
					if ( 'username' == $value || 'user_id' == $value || 'email' == $value ) {
						$order_by = $value;
					} else {
						$order_by = 'user_id';
					}
				}
			}
			
			$query = mysql_query( "SELECT * FROM user ORDER BY $order_by $order" );
		} else {
			$query = mysql_query( "SELECT * FROM user ORDER BY user_id DESC" );
		}
		
		$userArray = array();
		
		while ( $row = mysql_fetch_assoc( $query ) )  {  
			$user = new User( $row['user_id'], $row['username'], $row['email'] );  
			array_push( $userArray, $user );  
		}  
		
		return $userArray;
	}

	/**
	 * Get current user
	 * 
	 */
	public function current_user() {
		$user_id = $_SESSION['MedBugs_user_id'];
		
		if ( empty( $user_id ) ) {
			return false;
		}
		
		$query = mysql_query( "SELECT * FROM user WHERE user_id='$user_id'" );
		
		$userArray = array();
		
		while ( $row = mysql_fetch_assoc( $query ) )  {  
			$user = new User( $row['user_id'], $row['username'], $row['email'] );  
			array_push( $userArray, $user );  
		}  
		
		return $userArray[0];
	}
}

$db = new Database_Connection();
$db->open();
?>