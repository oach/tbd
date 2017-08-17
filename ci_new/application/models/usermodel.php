<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class UserModel extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function login($config)
	{
		$this->db
			->select(
	            'users.id,
	            users.usertype_id,
	            usertype.usertype,
	            users.username,
	            users.firstname,
	            users.lastname,
	            users.email,
	            users.birthdate,
	            users.city,
	            users.state,
	            users.notes,
	            users.avatar,
	            users.avatarImage,
	            users.lastlogin,
	            users.joindate,
	            users.uploadImage,
	            state.id AS stateID,
				DATE_FORMAT(users.lastlogin, "%a, %b %d, %Y at %T") AS formatLastLogin', false)
        	->from('users')
        	->join('usertype', 'usertype.id = users.usertype_id', 'inner')
        	->join('state', 'state.stateAbbr = users.state', 'left')
        	->where('users.email', $config['email'])
        	->where('users.password', 'SHA1("' . $config['password'] . '")', false)
        	->where('users.active', '1')
        	->where('users.banned', '0');

        if (key_exists('type', $config) && $config['type'] == 'admin')
        {
			$this->db->where('usertype.usertype', 'admin');
		}

        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return false;
	}
	
	public function getUserProfile($id, $return = 'array') {
		$this->db
			->select(
				'users.id,
				users.username,
				users.firstname,
				users.lastname,
				users.email,
				users.birthdate,
				users.city,
				users.state,
				users.notes,
				users.avatar,
				users.avatarImage,
				users.lastlogin,
				DATE_FORMAT(users.joindate, "%a, %b %d, %Y at %T") AS joinDate,
				DATE_FORMAT(users.lastlogin, "%a, %b %d, %Y at %T") AS formatLastLogin,
				TIME_TO_SEC(TIMEDIFF(NOW(), users.lastlogin)) AS secondsLastLogin,
				users.uploadImage,
				state.id AS stateID',
				false
			)
			->from('users')
			->join('state', 'state.stateAbbr = users.state', 'left outer')
			->where('users.active', '1')
			->where('users.banned', '0')
			->where('users.id', $id);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	if ($return == 'obj') {
        		return $rs->row();
        	}
        	else {
        		return $rs->row_array();
        	}
		}
		return [];
	}
	
	public function updateProfileByID($id, $data) {
		// create the query
		$query = '
			UPDATE users
			SET
				username = "' . mysqli_real_escape_string($this->db->conn_id, $data['username']) . '"
				, firstname = "' . mysqli_real_escape_string($this->db->conn_id, $data['firstname']) . '"
				, lastname = "' . mysqli_real_escape_string($this->db->conn_id, $data['lastname']) . '"
				, email = "' . mysqli_real_escape_string($this->db->conn_id, $data['email']) . '"
				, birthdate = "' . mysqli_real_escape_string($this->db->conn_id, $data['birthdate']) . '"
				, city = "' . mysqli_real_escape_string($this->db->conn_id, $data['city']) . '"
				, state = "' . mysqli_real_escape_string($this->db->conn_id, $data['state']) . '"
				, notes = "' . mysqli_real_escape_string($this->db->conn_id, $data['notes']) . '"
			WHERE
				active = "1"
				AND banned = "0"
				AND id = ' . mysqli_real_escape_string($this->db->conn_id, $id) . '
		';
		// run the query
		$this->db->query($query);
	}
	
	public function updateLastLogin($id)
	{
		$this->db
			->set('lastlogin', date('Y-m-d H:i:s'))
			->where('id', $id)
			->update('users');
	}
	
	public function createAccount($config)
	{
		$this->db
			->set('id', 'null')
			->set('usertype_id', $config['usertype'])
			->set('username', $config['username'])
			->set('password', 'SHA1("' . $config['password1'] . '")', false)
			->set('email', $config['email'])
			->set('city', $config['city'])
			->set('state', $config['stateAbbr'])
			->set('ip', $config['ip'])
			->set('activationdate', 'NOW()', false)
			->set('activationcode', 'SHA1(NOW())', false)
			->set('active', '0')
			->insert('users');

		return $this->db->insert_id();

		// create the query
		$query = '
			INSERT INTO users (
				id
				, usertype_id
				, username
				, password
				, email
				, city
				, state
				, ip
				, activationdate
				, activationcode
				, active
			) VALUES (
				NULL
				, ' . $config['usertype'] . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['username']) . '"
				, SHA1("' . mysqli_real_escape_string($this->db->conn_id, $config['password1']) . '")
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['email']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['city']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['stateAbbr']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['ip']) . '"
				, NOW()
				, SHA1(NOW())
				, "0"
			)
		';
		// run the query
		$this->db->query($query);
		// return the inserted id
		return $this->db->insert_id();
	}
	
	public function getActivationCode($userID) {
		// create the query
		$query = '
			SELECT
				CONCAT(id, "_", activationcode) AS aCode
			FROM users
			WHERE
				id = ' . $userID . '
			LIMIT 1
		';
		// create the record set
		$rs = $this->db->query($query);
		$result = false;
		if($rs->num_rows() == 1) {
			$row = $rs->row_array();
			$result = $row['aCode'];
		}
		return $result;
	}

	public function activateAccount($id, $activation_code)
	{
		$this->db
			->set('joindate', date('Y-m-d H:i:s'))
			->set('active', '1')
			->where('activationdate > DATE_SUB(NOW(), INTERVAL 48 HOUR)')
			->where('id', $id)
			->where('activationcode', $activation_code)
			->where('active', '0')
			->update('users');

		return $this->db->affected_rows();
	}

	public function idCheck($id)
	{
		$this->db
			->select('id')
			->from('users')
			->where('id', $id)
			->where('active', '1')
			->where('banned', '0');

		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return false;
	}
	
	public function emailCheck($email) {
		// create the query
		$query = '
			SELECT
				email
			FROM users
			WHERE
				email = "' . mysqli_real_escape_string($this->db->conn_id, $email) . '"
		';
		// create the record set
		$rs = $this->db->query($query);
		$result = true;
		if($rs->num_rows() > 0) {
			$result = false;
		}
		return $result;
	}
	
	public function emailCheckMatch($email) {
		$this->db
			->select(
	            'id,
				username,
				email'
	        )
        	->from('users')
        	->where('email', $email)
        	->where('active', '1')
        	->where('banned', '0');
		
		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
			return true;
        }
		return false;
	}
	
	public function getInfoIfEmailExists($email)
	{
		$this->db
			->select(
	            'id,
				username,
				email'
	        )
        	->from('users')
        	->where('email', $email)
        	->where('active', '1')
        	->where('banned', '0');
        $rs = $this->db->get();

        if ($rs->num_rows() == 1) {
        	$array['datetime'] = date('Y-m-d H:i:s');
        	$array['validationKey'] = sha1($array['datetime']);
			$array['email'] = $email;

			$this->_setPasswordResetValidationValues($array);
			//$tmp = array_merge($rs->row_array(), $array);
			//echo '<pre>' . print_r(array_merge($rs->row_array(), $array), true); exit;
			return array_merge($rs->row_array(), $array);
		}
		return [];
	}
	
	/**
	 * Set the password reset fields with appropriate values
	 * @param array $config
	 *        	string validationKey
	 *        	string datetime
	 * @return void
	 */
	private function _setPasswordResetValidationValues($config) {
		$this->db
			->set('passwordresetcode', $config['validationKey'])
			->set('passwordresetdate', $config['datetime'])
			->where('email', $config['email'])
			->update('users');
	}
	
	/**
	 * Validated that password reset code for a user
	 * @param  array $config
	 *         	string activationCode
	 *         	integer userID
	 * @return boolean match or not
	 */
	public function validatePasswordCode($config) {
		$this->db
			->select(
	            'id,
				username,
				email'
	        )
        	->from('users')
        	->where('id', $config['userID'])
        	->where('passwordresetcode', $config['activationCode'])
        	->where('passwordresetdate > DATE_SUB(NOW(), INTERVAL 4 HOUR)')
        	->where('active', '1')
        	->where('banned', '0');

        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row_array();
		}
		return false;
	}
	
	/**
	 * Set a new password
	 * 
	 * @param array $config 
	 *        	string newPassword
	 *        	integer userID
	 * @return integer represents the number of records updated
	 */
	public function setPassword($config)
	{
		$this->db
			->set('password', 'SHA1("' . $config['newPassword'] . '")', false)
			->where('id', $config['userID'])
			->update('users');

		return $this->db->affected_rows();
	}
	
	public function usernameCheck($username)
	{
		$this->db
			->select('id, username')
			->from('users')
			->where('username', $username);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return true;
		}
		return false;
	}
	
	public function usernameCheckCreateAccount($username) {
		// create the query
		$query = '
			SELECT
				id
				, username
			FROM users
			WHERE
				username = "' . mysqli_real_escape_string($this->db->conn_id, $username) . '"
		';
		// create the record set
		$rs = $this->db->query($query);
		// holder for the boolean
		$result = true;
		// check if there were any results
		if($rs->num_rows() > 0) {
			// there was a result
			$result = false;
		}
		// return the boolean
		return $result;
	}
	
	public function getUsernameByID($id)
	{
		$this->db
			->select('username')
			->from('users')
			->where('id', $id)
        	->where('active', '1')
        	->where('banned', '0');

        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	$row = $rs->row();
        	return $row->username;
		}
		return false;
	}
	
	public function getIDByUsername($username) {
		$this->db
			->select('id')
			->from('users')
			->where('username', $username)
			->where('active', '1')
			->where('banned', '0');

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	$row = $rs->row();
        	return $row->id;
		}
		return false;
	}
	
	public function checkBlockUsername($username, $blockID)
	{
		$this->db
			->select('users.id, pms_blocked.block_userID, pms_blocked.userID')
			->from('users')
			->join('pms_blocked', 'pms_blocked.userID = users.id', 'inner')
			->where('users.username', $username)
			->where('pms_blocked.block_userID', $blockID);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return false;
		}
		return true;
	}
	
	public function getPMSByUserID($id)
	{
		$this->db
			->select(
				'pms.id,
				pms.from_userID,
				pms.subject,
				pms.message,
				DATE_FORMAT(pms.timeSent, "%W, %M %d, %Y<br />%T") AS timesent,
				pms.timeRead,
				pms.forwardID,
				pms.replyID,
				users.username', false
			)
			->from('pms')
			->join('users', 'users.id = pms.from_userID', 'inner')
			->where('pms.to_userID', $id)
			->order_by('pms.timeSent', 'desc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
		// create the query
		$query = '
			SELECT
				p.id
				, p.from_userID
				, p.subject
				, p.message
				, DATE_FORMAT(p.timeSent, "%W, %M %d, %Y<br />%T") AS timesent
				, p.timeRead
				, p.forwardID
				, p.replyID
				, u.username
			FROM pms p
			INNER JOIN users u ON u.id = p.from_userID
			WHERE
				p.to_userID = ' . $id . '
			ORDER BY
				p.timeSent DESC
		';
		// create the record set
		$rs = $this->db->query($query);
		$array = false;
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getPMByMessageID($messageID, $userID)
	{
		$this->db
			->select(
				'pms.id
				, pms.from_userID
				, pms.subject
				, pms.message
				, DATE_FORMAT(pms.timeSent, "%W, %M %d, %Y, %T") AS timesent
				, pms.timeRead
				, pms.forwardID
				, pms.replyID
				, users.username
				, users.city
				, users.state
				, users.avatar
				, users.avatarImage
				, DATE_FORMAT(users.joindate, "%M, %Y") AS joindate', false
			)
			->from('pms')
			->join('users', 'users.id = pms.from_userID', 'inner')
        	->where('pms.to_userID', $userID)
        	->where('pms.id', $messageID)
        	->order_by('pms.timeSent', 'desc');

        $rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
		// create the query
		$query = '
			SELECT
				p.id
				, p.from_userID
				, p.subject
				, p.message
				, DATE_FORMAT(p.timeSent, "%W, %M %d, %Y, %T") AS timesent
				, p.timeRead
				, p.forwardID
				, p.replyID
				, u.username
				, u.city
				, u.state
				, u.avatar
				, u.avatarImage
				, DATE_FORMAT(u.joindate, "%M, %Y") AS joindate
			FROM pms p
			INNER JOIN users u ON u.id = p.from_userID
			WHERE
				p.to_userID = ' . $userID . '
				AND p.id = ' . $messageID . '
			ORDER BY
				p.timeSent DESC
		';
		// create the record set
		$rs = $this->db->query($query);
		$array = false;
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function insertPM($config) {
		// array of tables to run query on
		//$arr_tables = array('pms', 'pms_sent');
		$arr_tables = array('pms');
		// create the query text
		$query = '
				id
				, from_userID
				, to_userID
				, subject
				, message
				, timeSent
		';
		$query .= key_exists('forwardID', $config) ? ', forwardID' : '';
		$query .= key_exists('replyID', $config) ? ', replyID' : '';
		$query .= '
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $config['fromID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $config['toID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['subject']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['message']) . '"
				, NOW()
		';
		$query .= key_exists('forwardID', $config) ? mysqli_real_escape_string($this->db->conn_id, $config['forwardID']) : '';
		$query .= key_exists('replyID', $config) ? mysqli_real_escape_string($this->db->conn_id, $config['replyIDID']) : '';
		$query .= '
			)
		';
		// iterate through the table array
		foreach($arr_tables as $table) {
			$query = 'INSERT INTO ' . $table . ' (' . $query;
			// create the record set
			$this->db->query($query);
		}
	}
	
	public function removePM($messageID, $userID)
	{
		$this->db
			->where('id', $messageID)
			->where('to_userID', $userID)
			->limit(1)
			->delete('pms');

		$query = 'OPTIMIZE TABLE pms';
		$rs = $this->db->query($query);
	}
	
	public function num_new_messages($user_id)
    {
		if ($user_id)
        {
            // create the query
    		$query = '
    			SELECT
    				COUNT(p.id) AS pmCount
    			FROM pms p
    			INNER JOIN users u ON u.id = p.from_userID
    			WHERE
    				p.to_userID = ' . $user_id . '
    				AND timeRead IS NULL
    		';
    		// create the record set
    		$rs = $this->db->query($query);
    		$row = $rs->row_array();
            return $row['pmCount'];            
        }
        return 0;		
	}
	
	public function updateTimeRead($messageID, $userID) {
		// create the query
		$query = '
			UPDATE pms SET
				timeRead = NOW()
			WHERE
				to_userID = ' . $userID . '
				AND id = ' . $messageID . '
			LIMIT 1
		';
		// create the record set
		$rs = $this->db->query($query);
	}
	
	public function getMessageInfoByMessageID($messageID)
	{
		$this->db
			->select(
				'pms.id,
				pms.from_userID,
				pms.subject,
				pms.message,
				DATE_FORMAT(pms.timeSent, "%W, %M %d, %Y, %T") AS timesent,
				pms.timeRead,
				pms.forwardID,
				pms.replyID,
				users.username', false
			)
			->from('pms')
			->join('users', 'users.id = pms.from_userID', 'inner')
			->where('pms.id', $messageID);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->row();
        }
		return false;
	}
	
	public function updateAvatar($id, $image) {
		// create the query
		$query = '
			UPDATE users SET
				avatar = "1"
				, avatarImage = "' . $image . '"
			WHERE
				id = ' . mysqli_real_escape_string($this->db->conn_id, $id)
		;
		// run the query
		$this->db->query($query);
		// return the number of affected rows (should be one)
		return $this->db->affected_rows();
	}
    
    public function addToDudeList($id, $dudeID) {
        // create the query to check if they are already on the list
        $query = '
            SELECT
                userID
            FROM dudes
            WHERE
                userID = ' . $id . '
                AND dudeID = ' . $dudeID . '
            LIMIT 1
        ';
        // run the query
        $rs = $this->db->query($query);
        // holder array 
        $array = false;
        // see if there was a row returned
        if($rs->num_rows() > 0) {
            $array = $rs->row_array();
        }
        // check that we dont' have results
        if($array === false) {
            // create the insert query
            $query = '
                INSERT INTO dudes (
                    userID
                    , dudeID
                ) VALUES (
                    ' . $id . '
                    , ' . $dudeID . '
                )
            ';
            // run the query
            $this->db->query($query);    
        }
        return $array;
    }
    
    public function removeFromDudeList($id, $dudeID) {
        // create the query
        $query = '
            DELETE FROM dudes
            WHERE
                userID = ' . $id . '
                AND dudeID = ' . $dudeID . '
            LIMIT 1
        ';
        // run the query
        $rs = $this->db->query($query);
		// clean up
		$query = 'OPTIMIZE TABLE dudes';
		// run the query
		$this->db->query($query);
    }
    
    public function selectDudeList($id) {
        // create the query
        $query = '
            SELECT
                u.id
                , u.username    
            FROM dudes AS d
            INNER JOIN users AS u
                ON u.id = d.dudeID
            WHERE
                d.userID = ' . $id . '
            ORDER BY 
                u.username ASC
        ';
        // create the record set
        $rs = $this->db->query($query);
        // holder for the passback
        $array = false;
        // check if there were results
        if($rs->num_rows() > 0) {
            // store the results
            $array = $rs->result_array();
        }
        // return the results
        return $array;
        
    }

	/**
	 * getAll()
	 * retrieves all records from the database
	 *
	 * @return associative array 
	 * 'total' => number of records
	 * 'records' => associative array of all users found
	 */
	public function getAll() {
		$query = $this->db->query('SELECT count(*) as total FROM users ');
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$total = $row['total'];
			$query = $this->db->query('SELECT * FROM users ');
			return array('total' => $total, 'records' => $query->result_array());
		} else {
			return array('total' => 0, 'records' => array());
		}
	}

	/**
	 * getActiveUsers()
	 * retrieves all active records from the database
	 *
	 * @return associative array 
	 * 'total' => number of records
	 * 'records' => associative array of all records found
	 */	
	public function getActiveUsers() {
		$query = $this->db->query('SELECT count(*) as total FROM users WHERE active=1 ');
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$total = $row['total'];
			$query = $this->db->query('SELECT * FROM users WHERE active=1 ');
			return array('total' => $total, 'records' => $query->result_array());
		} else {
			return array('total' => 0, 'records' => array());
		}
	}
	
	public function getAuthorizedUsers() {
		$site = resolve_server_url();
		$sql = "
		SELECT count(*) AS total 
		FROM users u 
		JOIN user_sites us ON u.id=us.user_id 
		JOIN sites s ON s.id=us.site_id  
		WHERE (u.role_id>1) AND (s.url='$site')
		";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {
			$row = $query->row_array();
			$total = $row['total'];
			$sql = "
			SELECT DISTINCT u.name, u.email, u.phone, u.login, r.display, us.user_id 
			FROM users u 
			JOIN user_sites us ON u.id=us.user_id 
			JOIN sites s ON s.id=us.site_id 
			JOIN roles r ON r.id=u.role_id 
			WHERE (u.role_id>1) AND (s.url='$site') AND (u.active=1)
			";
			$query = $this->db->query($sql);
			return array('total' => $total, 'records' => $query->result_array());
		} else {
			return array('total' => 0, 'records' => array());
		}

	}
	public function checkSiteAccess() {
		$user_id = $this->_id;
		$url = resolve_server_url();
		$sql = "SELECT * FROM user_sites us JOIN sites s ON us.site_id=s.id WHERE s.url='".$url."' and us.user_id=".$user_id." ";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0) {	
			return true;
		} else {
			return false;
		}
	}
    
    public function autoCompleteSearch($term) {
        $this->db
    		->select(
    			'users.id,
    			users.username AS name,
    			users.avatarImage AS picture'
    		)
            ->from('users')
            ->like('users.username', $term, 'after')
            ->where('users.active', '1')
        	->where('users.banned', '0')
            ->order_by('users.username', 'desc')
    		->limit(8);

    	$rs = $this->db->get();
    	if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
    }    
}
?>