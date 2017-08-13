<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class SwapModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSwapInsByUserID($id) {
        $this->db
        	->select(
        		'beers.id AS beerID,
                beers.beerName,
                establishment.id AS establishmentID,
                establishment.name,
                establishment.url,
                swapins.insDate AS swapDate,
                users.username'
        	)
        	->from('users')
        	->join('swapins', 'swapins.userID = users.id', 'inner')
        	->join('beers', 'beers.id = swapins.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->where('users.id', $id)
        	->group_by('establishment.id')
        	->order_by('beers.beerName', 'asc');

        $rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
    }
	
	public function getSwapOutsByUserID($id) {
		$this->db
        	->select(
        		'beers.id AS beerID,
                beers.beerName,
                establishment.id AS establishmentID,
                establishment.name,
                establishment.url,
                swapouts.outsDate AS swapDate,
                users.username'
        	)
        	->from('users')
        	->join('swapouts', 'swapouts.userID = users.id', 'inner')
        	->join('beers', 'beers.id = swapouts.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->where('users.id', $id)
        	->group_by('establishment.id')
        	->order_by('beers.beerName', 'asc');

        $rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getSwapInsByBeerID($id) {
		$this->db
			->select(
				'beers.id AS beerID,
				beers.beerName,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.url,
				users.id AS userID,
				users.username,
				users.city,
				users.state,
				swapins.insDate AS swapDate',
				false
			)
			->from('swapins')
			->join('beers', 'beers.id = swapins.beerID', 'inner')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('users', 'users.id = swapins.userID', 'inner')
			->where('swapins.beerID', $id)
			->order_by('swapins.insDate', 'desc')
			->order_by('users.username', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getSwapOutsByBeerID($id) {
		$this->db
			->select(
				'beers.id AS beerID,
				beers.beerName,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.url,
				users.id AS userID,
				users.username,
				users.city,
				users.state,
				swapouts.outsDate AS swapDate',
				false
			)
			->from('swapouts')
			->join('beers', 'beers.id = swapouts.beerID', 'inner')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('users', 'users.id = swapouts.userID', 'inner')
			->where('swapouts.beerID', $id)
			->order_by('swapouts.outsDate', 'desc')
			->order_by('users.username', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getSwapFeedbackCountByFeedbackUserID($id) {
		$query = '
			SELECT
				COUNT(id) as feedbackCount
			FROM swapfeedback
			WHERE
				feedbackUserID = ' . $id . '
				AND active = "1"
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->row_array();
		}
		return $array;
	}
    
    public function getSwapFeedbackByFeedbackID($id) {
        $query = '
            SELECT
                swf.id
                , swf.writerUserID
                , swf.feedback
                , DATE_FORMAT(swf.feedbackDate, "%W, %M %d, %Y at %T") AS feedbackDate
                , u.username
            FROM swapfeedback swf
            INNER JOIN users u ON u.id = swf.writerUserID
            WHERE
                swf.id = ' . $id . '
                AND swf.active = "1"
            LIMIT 1
        ';
        
        $rs = $this->db->query($query);
        $array = array();
        if($rs->num_rows() > 0) {
            $array = $rs->row();
        }
        return $array;    
    }
	
	public function getSwapFeedbackByFeedbackUserID($id, $limit = 0) {
		$query = '
			SELECT
				swf.id
				, swf.writerUserID
				, swf.feedback
				, DATE_FORMAT(swf.feedbackDate, "%W, %M %d, %Y at %T") AS feedbackDate
				, u.username
				, u.city
				, u.state
				, u.avatar
				, u.avatarImage
				, DATE_FORMAT(u.joindate, "%M, %Y") AS joindate
				, u.active
			FROM swapfeedback swf
			INNER JOIN users u ON u.id = swf.writerUserID
			WHERE
				swf.feedbackUserID = ' . $id . '
				AND swf.active = "1"
			ORDER BY
				swf.feedbackDate DESC
            LIMIT ' . $limit . ', ' . PER_PAGE_SWAP_FEEDBACK
		;
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function numberSwapOutsByBeerID($beerID) {
		$query = '
			SELECT
				COUNT(beerID) as totalCount
			FROM swapouts
			WHERE
				beerID = ' . $beerID
		;
		
		$rs = $this->db->query($query);
		$row = $rs->row_array();
		return $row['totalCount'];
	}
	
	public function numberSwapInsByBeerID($beerID) {
		$query = '
			SELECT
				COUNT(beerID) as totalCount
			FROM swapins
			WHERE
				beerID = ' . $beerID
		;
		
		$rs = $this->db->query($query);
		$row = $rs->row_array();
		return $row['totalCount'];
	}
	
	public function insertSwapOut($userID, $beerID) {
		$this->db
			->set('userID', $userID)
			->set('beerID', $beerID)
			->set('outsDate','NOW()', false)
			->insert('swapouts');
	}
	
	public function insertSwapIn($userID, $beerID) {
		$this->db
			->set('userID', $userID)
			->set('beerID', $beerID)
			->set('insDate','NOW()', false)
			->insert('swapins');
	}
	
	public function removeSwapOut($userID, $beerID) {
		// create the query
		$query = '
			DELETE FROM swapouts 
			WHERE
				userID = ' . mysqli_real_escape_string($this->db->conn_id, $userID) . '
				AND beerID = ' . mysqli_real_escape_string($this->db->conn_id, $beerID) . '
			LIMIT 1
		';
		// run the query
		$rs = $this->db->query($query);
		// clean up
		$query = 'OPTIMIZE TABLE swapouts';
		// run the query
		$this->db->query($query);
	}
	
	public function removeSwapIn($userID, $beerID) {
		// create the query
		$query = '
			DELETE FROM swapins 
			WHERE
				userID = ' . mysqli_real_escape_string($this->db->conn_id, $userID) . '
				AND beerID = ' . mysqli_real_escape_string($this->db->conn_id, $beerID) . '
			LIMIT 1
		';
		// run the query
		$this->db->query($query);
		// clean up
		$query = 'OPTIMIZE TABLE swapins';
		// run the query
		$this->db->query($query);
	}
	
	public function saveFeedback($config) {
		// create the query
		$query = '
			INSERT INTO swapfeedback (
				id
				, writerUserID
				, feedbackUserID
				, feedback
				, feedbackDate
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $config['hdn_writerUserID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $config['hdn_feedbackUserID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $config['ttr_swapFeedback']) . '"
				, NOW()
				, "1"
			)
		';
		// run the query
		$this->db->query($query);
	}
	
	public function getInsAndOutsByBeerID($beerID) {
		$this->db
			->select('COUNT(beerID) as totalCount', false)
			->from('swapouts')
			->where('beerID', $beerID);

		$rs = $this->db->get();
		$row = $rs->row();
        $array['outs'] = $row->totalCount;

        $this->db
			->select('COUNT(beerID) as totalCount', false)
			->from('swapins')
			->where('beerID', $beerID);

		$rs = $this->db->get();
		$row = $rs->row();
        $array['ins'] = $row->totalCount;
		
		return $array;
	}
	
	public function determineInSwapOuts($beerID, $userID) {
		$query = '
			SELECT
				beerID
			FROM swapouts
			WHERE
				beerID = ' . $beerID . '
				AND userID = ' . $userID
		;
		$boolean = false;
		$rs = $this->db->query($query);
		if($rs->num_rows() > 0) {
			$boolean = true;
		}
		return $boolean;
	}
	
	public function determineInSwapIns($beerID, $userID) {
		$query = '
			SELECT
				beerID
			FROM swapins
			WHERE
				beerID = ' . $beerID . '
				AND userID = ' . $userID
		;
		$boolean = false;
		$rs = $this->db->query($query);
		if($rs->num_rows() > 0) {
			$boolean = true;
		}
		return $boolean;
	}
}
?>