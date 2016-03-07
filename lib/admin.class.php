<?php
class ArrowDB_Push_Admin extends ArrowDB_Push_Base_Class {
	
	public function __construct() {

		// -- Set Base Vars
		$this->key = get_option( 'arrowdb_key' );
		$this->username = get_option( 'arrowdb_un'  );
		$this->password = get_option( 'arrowdb_pw' );

		add_action('init', array($this, 'load_all_hooks'));
		parent::__construct();
	}

	/**
	* Add the Load All Wordpress Hooks
	*/
	public function load_all_hooks() {	
		add_action( 'admin_print_styles', array( $this, 'add_styles' ) );
		add_action( 'admin_print_scripts', array( $this, 'add_scripts' ) );
		add_action('admin_menu', array( $this, 'add_menu'));
		add_action('admin_notices', array( $this, 'arrow_notices'));
	}
	
	/**
	 * Add the styles
	 */
	public function add_styles() {

	}

	/**
	* Add the scripts
	*/
	public function add_scripts() {

	}

	/**
	* Add the menu
	*/
	public function add_menu() {
		add_menu_page( 'ArrowDB Push Notifications', 'ArrowDB Push', 'edit_theme_options', 'arrowdb-menu', array($this, 'adminUI'), '');
		add_submenu_page( 'arrowdb-menu', 'ArrowDB Push Notifications Settings', 'Settings', 'edit_theme_options', 'arrowdb-menu-settings', array($this, 'settings_page'));
	} 

	/**
	 * Add Notices
	 */
	public function arrow_notices() {
		if( !$this->key || !$this->username || !$this->password ) {
			?>
				<div class="error notice">
			       <strong>
			       		ArrowDB Configuration Issue:
			       </strong>
			       Your Key, Username and/or Password have not yet been configured you will be unable to send push notifcations via ArrowDB until these are resolved
			    </div>
			<?php
		}
	}

	/**
	* Send Push
	*/
	public function send() {

	}

	/**
	* Admin UI For Sending Push
	*/
	public function adminUI() {

		$error_title = null;
		$error_content = null;
		$bHasSentPush = false;
		
		 if ( isset( $_POST['_arrow_push_submit'] ) && check_admin_referer( 'arrow_db_push_action', 'arrow_db_settings_nonce' ) ) {	 		
		 	if($_POST['_arrowdb_title'] && $_POST['_arrowdb_msg']) {
		 		$response = $this->postPush($_POST['_arrowdb_title'], $_POST['_arrowdb_msg'], $_POST['_arrowdb_channel']);
		 		if($response->status == 'fail') {
		 			$error_tile = 'Push Error Code: ' . $response->code . ' : Failed to send Push Notification';
		 			$msg = json_decode(  $response->log );
		 			$error_content = $msg->meta->message;
		 		} else {
		 			$bHasSentPush = true;
		 		}
		 	}
		 }

		?>
			<?php if($error_tile) : ?>
				<div class="error notice">
			       <p><strong>
			       		<?php echo $error_tile; ?>
			       </strong></p>
			      <?php echo $error_content; ?>
			    </div>
			<?php endif; ?>
			<?php if($bHasSentPush) : ?>
				<div class="updated notice">
			       <p><strong>
			       		Push Sent
			       </strong></p>
			      Your notifcation has been sent
			    </div>
			<?php endif; ?>

			<form method="post" action="">
				<h3>Push Title</h3>
				<input type="text" class="widefat" name="_arrowdb_title" value="<?php echo @$_POST['_arrowdb_title']; ?>" />
				
				<h3>Push Message</h3>
				<textarea class="widefat" name="_arrowdb_msg" cols="50" rows="5"><?php echo @$_POST['_arrowdb_msg']; ?></textarea>
		
				<h3>Push Channel</h3>
				<input type="text" class="widefat" name="_arrowdb_channel" value="<?php echo @$_POST['_arrowdb_channel']; ?>" />

				<input type="submit" name="_arrow_push_submit" class="button-primary" value="Send Push" />
				<?php wp_nonce_field( 'arrow_db_push_action', 'arrow_db_settings_nonce' ); ?>
			</form>
		<?php
	}

	/**
	* Settings UI
	*/
	function  settings_page() {
		
		 if ( isset( $_POST['_arrow_settings_submit'] ) && check_admin_referer( 'arrow_db_settings_action', 'arrow_db_settings_nonce' ) ) {
		 	update_option( 'arrowdb_key', $_POST['_arrowdb_key'] );
			update_option( 'arrowdb_un', $_POST['_arrowdb_un'] );
			update_option( 'arrowdb_pw', $_POST['_arrowdb_pwd'] );
			$this->key = $_POST['_arrowdb_key'];
			$this->username = $_POST['_arrowdb_un'];
			$this->password = $_POST['_arrowdb_pwd'];
		 }

		?>
			<form method="post" action="">
				<h3>ArrowDB Key</h3>
				<input type="text" class="widefat" name="_arrowdb_key" value="<?php echo $this->key; ?>" />
				<h3>ArrowDB Username</h3>
				<input type="text" class="widefat" name="_arrowdb_un" value="<?php echo $this->username; ?>" />
				<h3>ArrowBD Password</h3>
				<input type="password" class="widefat" name="_arrowdb_pwd" value="<?php echo $this->password; ?>" />
				<input type="submit" name="_arrow_settings_submit" class="button-primary" value="Save Changes" />
				<?php wp_nonce_field( 'arrow_db_settings_action', 'arrow_db_settings_nonce' ); ?>
			</form>
		<?php
	}

	/**
	* Send the push to Appcelerator Arrow DB
	*/
	private function postPush( $message, $title, $channel = '' ) {


		$ACS = new Cloud(array(
			"key" 	=> $this->key
		));

		$login = $ACS->login($this->username,$this->password);
		
		if( $this->get_response_status($ACS->response()) == "fail") {
			return $ACS->response();
		} 


		$ACS->PushNotification->notify(array(
			"channel"   => $channel, 
			"alert"		=> $message,
			"title"		=> $title,
			"to_ids"	=> 'everyone',			// -- Need To Make Editable
			"sound"		=> 'none',				// -- Make Sound Editable
			"vibrate"	=> 'false'				// -- Make Vibrate Editable
			//"badge"			=> ''			// -- Make Badge Editable
		 )); 	

		return $ACS->response();


	}

	private function get_response_status( $r ) {
		$_ = json_decode( $r );
		return $_->meta->status;
	}


}


?>