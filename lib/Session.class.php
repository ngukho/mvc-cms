<?php
/**
 * Session Class
 *
 * Class for adding extra session security protection as well as new ways to
 * store sessions (such as databases).
 *
  	CREATE TABLE IF NOT EXISTS `sessions` 
	(
   		`session_id` VARCHAR(40) NOT NULL,
   		`last_activity` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   		`data` text NOT NULL,
   		PRIMARY KEY (`session_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
 * 
 */

final class Session 
{
	public $match_ip			= FALSE;			//Require user IP to match?
	public $match_fingerprint	= TRUE;				//Require user agent fingerprint to match?
	public $match_token			= FALSE;			//Require this token to match?
	public $session_name		= 'site_session';	//What should the session be called?
	public $session_id			= NULL;				//Specify a custom ID to use instead of default cookie ID
	private $encryption_key		= '~!@#$%^&*()1234567890';
	
	public $session_database	= FALSE;			
	private $_pdo = NULL;
	public $table_name	= 'sessions';
	public $primary_key	= 'session_id';
	
	public $cookie_path			= NULL;				//Path to set in session_cookie
	public $cookie_domain		= NULL;				//The domain to set in session_cookie
	public $cookie_secure		= NULL;				//Should cookies only be sent over secure connections?
	public $cookie_httponly		= NULL;				//Only accessible through the HTTP protocol?

	public $regenerate			= 300;				//Update the session every five minutes
	public $expiration			= 7200;				//The session expires after 2 hours of non-use
	public $gc_probability		= 100;				//Chance (in 100) that old sessions will be removed

	// Store $_SESSION
	public $userdata = array();
	
	/**
	 * Configure some default session setting and then start the session.
	 * @param	array	$config
	 * @return	void
	 */
	public function __construct($params = array()) 
	{
		//Set the params
		$config = Config::getInstance();
		
		foreach (array('match_ip', 'match_fingerprint', 'match_token', 'session_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'cookie_httponly', 'regenerate', 'expiration', 'session_database', 'table_name', 'primary_key') as $key)
		{
			$this->$key = (isset($params[$key])) ? $params[$key] : $config->config_values['session'][$key];
		}		

		// Configure garbage collection
		ini_set('session.gc_probability', $this->gc_probability);
		ini_set('session.gc_divisor', 100);
		ini_set('session.gc_maxlifetime', $this->expiration);
		ini_set('session.use_cookies', 'On');
		ini_set('session.use_trans_sid', 'Off');

		// Set the session cookie parameters
		session_set_cookie_params(
			$this->expiration + time(),
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure,
			$this->cookie_httponly
		);

		// Name the session, this will also be the name of the cookie
		session_name($this->session_name);

		//If we were told to use a specific ID instead of what PHP might find
		if($this->session_id) {
			session_id($this->session_id);
		}

		//Create a session (or get existing session)
		$this->create();

		$this->userdata =& $_SESSION;
	}

    /**
     * Destructor
     *
     * @access public 
     * @return void
     */
    public function __destruct()
    {
        // Close session
        session_write_close();
    }	

	/**
	 * Start the current session, if already started - then destroy and create a new session!
	 * @return void
	 */
	function create() 
	{
		//If this was called to destroy a session (only works after session started)
		$this->clear();

		//If there is a class to handle CRUD of the sessions
		if($this->session_database) 
		{
			// Register non-native driver as the session handler
	        session_set_save_handler( 
	            array( &$this, "open" ), 
	            array( &$this, "close" ),
	            array( &$this, "read" ),
	            array( &$this, "write"),
	            array( &$this, "destroy"),
	            array( &$this, "gc" )
	        );
	        
			// Create connect to database	        
	        $this->_pdo = DbConnection::getInstance();
		}
		
		// Start the session!
		session_start();

		//Check the session to make sure it is valid
		if( ! $this->check()) 
		{
			//Destroy invalid session and create a new one
			return $this->create();
		}
	}


	/**
	 * Check the current session to make sure the user is the same (or else create a new session)
	 * @return unknown_type
	 */
	function check() 
	{
		//On creation store the useragent fingerprint
		if(empty($_SESSION['fingerprint'])) 
		{
			$_SESSION['fingerprint'] = $this->generate_fingerprint();
		} 
		//If we should verify user agent fingerprints (and this one doesn't match!)
		elseif($this->match_fingerprint && $_SESSION['fingerprint'] != $this->generate_fingerprint()) 
		{
			return FALSE;
		}

		//If an IP address is present and we should check to see if it matches
		if(isset($_SESSION['ip_address']) && $this->match_ip) 
		{
			//If the IP does NOT match
			if($_SESSION['ip_address'] != ip_address()) 
			{
				return FALSE;
			}
		}

		//Set the users IP Address
		$_SESSION['ip_address'] = ip_address();


		//If a token was given for this session to match
		if($this->match_token) 
		{
			if(empty($_SESSION['token']) OR $_SESSION['token'] != $this->match_token) 
			{
				//Remove token check
				$this->match_token = FALSE;
				return FALSE;
			}
		}

		//Set the session start time so we can track when to regenerate the session
		if(empty($_SESSION['last_activity'])) 
		{
			$_SESSION['last_activity'] = time();
		} 
		//Check to see if the session needs to be regenerated
		elseif($_SESSION['last_activity'] + $this->expiration < time()) 
		{
			//Generate a new session id and a new cookie with the updated id
			session_regenerate_id();

			//Store new time that the session was generated
			$_SESSION['last_activity'] = time();

		}
		return TRUE;
	}


	/**
	 * Destroys the current session and user agent cookie
	 * @return  void
	 */
	function clear() 
	{
		//If there is no session to delete (not started)
		if (session_id() === '') return;

		// Get the session name
		$name = session_name();

		// Destroy the session
		session_destroy();

		// Delete the session cookie (if exists)
		if (isset($_COOKIE[$name])) 
		{
			//Get the current cookie config
			$params = session_get_cookie_params();

			// Delete the cookie from globals
			unset($_COOKIE[$name]);

			//Delete the cookie on the user_agent
			setcookie($name, '', time()-43200, $params['path'], $params['domain'], $params['secure']);
		}
	}


	/**
	 * Generates key as protection against Session Hijacking & Fixation. This
	 * works better than IP based checking for most sites due to constant user
	 * IP changes (although this method is not as secure as IP checks).
	 * @return string
	 */
	function generate_fingerprint()  
	{
		//We don't use the ip-adress, because it is subject to change in most cases
		foreach(array('ACCEPT_CHARSET', 'ACCEPT_ENCODING', 'ACCEPT_LANGUAGE', 'USER_AGENT') as $name) {
			$key[] = empty($_SERVER['HTTP_'. $name]) ? NULL : $_SERVER['HTTP_'. $name];
		}
		//Create an MD5 has and return it
		return md5(implode("\0", $key));
	}


	/**
 	* Default session handler for storing sessions in the database.
 	**/ 
	
	/**
	 * Record the current sesion_id for later
	 * @return boolean
	 */
	public function open() 
	{
		//Store the current ID so if it is changed we will know!
		$this->session_id = session_id();
		return TRUE;
	}


	/**
	 * Superfluous close function
	 * @return boolean
	 */
	public function close() 
	{
		return TRUE;
	}


	/**
	 * Attempt to read a session from the database.
	 * @param	string	$id
	 */
	public function read($id = NULL) 
	{
		$time = date('Y-m-d H:i:s', time() - $this->expiration);
		
		//Select the session
		$row = $this->_pdo->query("SELECT * FROM {$this->table_name} WHERE {$this->primary_key} = '{$id}' AND last_activity > '{$time}' ")->fetch();		
		
		return (!empty($row)) ? $row['data'] : '';
	}

	/**
	 * Attempt to create or update a session in the database.
	 * The $data is already serialized by PHP.
	 *
	 * @param	string	$id
	 * @param	string 	$data
	 */
	public function write($id = NULL, $data = '') 
	{

		/*
		 * Case 1: The session we are now being told to write does not match
		 * the session we were given at the start. This means that the ID was
		 * regenerated sometime durring the script and we need to update that
		 * old session id to this new value. The other choice is to delete
		 * the old session first - but that wastes resources.
		 */

		//If the session was not empty at start && regenerated sometime durring the page
//		if($this->session_id && $this->session_id != $id) 
//		{
//	    	$this->_pdo->query("UPDATE {$this->table_name} SET data = '{$data}' WHERE {$this->primary_key} = '{$id}'");
//			return;
//		}
//
//		/*
//		 * Case 2: We check to see if the session already exists. If it does
//		 * then we need to update it. If not, then we create a new entry.
//		 */
//		$row = $this->read($id);
//		if(!empty($row)) 
//		{
//	    	$this->_pdo->query("UPDATE {$this->table_name} SET data = '{$data}' WHERE {$this->primary_key} = '{$id}'");			
//		} 
//		else 
//		{
//			$this->_pdo->query("INSERT INTO {$this->table_name}({$this->primary_key},data) VALUES('{$id}','{$data}')");
//		}
		
		$time = date('Y-m-d H:i:s', time());
		$this->_pdo->query("REPLACE `{$this->table_name}` (`{$this->primary_key}`,`last_activity`,`data`) VALUES('{$id}','{$time}','{$data}')");
	}

	/**
	 * Delete a session from the database
	 * @param	string	$id
	 * @return	boolean
	 */
	public function destroy($id) 
	{
		$this->_pdo->query("DELETE FROM {$this->table_name} WHERE {$this->primary_key} = '{$id}'");
		return TRUE;
	}

	/**
	 * Garbage collector method to remove old sessions
	 */
	public function gc() 
	{
		//The max age of a session
		$time = date('Y-m-d H:i:s', time() - $this->expiration);
		
		//Remove all old sessions
		$this->_pdo->query("DELETE FROM {$this->table_name} WHERE last_activity < '{$time}'");
		return TRUE;
	}	
	
	private function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		// Serialize the userdata for the cookie
		$cookie_data = serialize($cookie_data);

		// if encryption is not used, we provide an md5 hash to prevent userside tampering
		$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);

		// Set the cookie
		setcookie(
					$this->session_name,
					$cookie_data,
					$this->expiration + time(),
					$this->cookie_path,
					$this->cookie_domain,
					0
				);
	}
	
}