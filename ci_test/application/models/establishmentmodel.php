<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class EstablishmentModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}
	
	public function getEstablishmentByID($id) {
		$this->db
			->select(
				'establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.latitude,
				establishment.longitude,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateFull,
				state.stateAbbr'
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.id', $id);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function checkForRatingByUserIDEstablishmentID($userID, $establishmentID)
	{
		$this->db
			->select('id, dateVisited, drink, service, atmosphere, pricing, accessibility, comments, visitAgain, price')
			->from('rating_establishment')
			->where('userID', $userID)
			->where('establishmentID', $establishmentID);
		
		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return [];
	}
	
	public function getRecentReviews() {
		$this->db
			->select(
				'establishment.id,
				establishment.name,
				establishment.city,
				establishment.stateID,
				establishment.slug AS slug_establishment,
				state.stateAbbr,
				state.stateFull,
				ROUND((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100)), 1) AS rating,
				rating_establishment.comments',
				false
			)
			->from('rating_establishment')
			->join('establishment', 'establishment.id = rating_establishment.establishmentID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.active', '1')
			->where('rating_establishment.active', '1')
			->order_by('rating_establishment.dateAdded', 'desc')
			->limit(5);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getNewestAdditions() {
		$this->db
			->select(
				'establishment.id,
				establishment.name,
				establishment.city,
				establishment.stateID,
				establishment.slug AS slug_establishment,
				state.stateFull'
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.active', '1')
			->order_by('establishment.dateAdded', 'desc')
			->limit(5);
		
		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getEstablishmentTypes() {
		// create the query
		$query = '
			SELECT
				name
				, description
			FROM establishment_categories
			ORDER BY 
				id ASC
		';
		// get the record set
		$rs = $this->db->query($query);
		// temporary holder for results
		$array = array();
		// check if there are any results in the record set
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		// return results
		return $array;
	}
	
	public function getHighestRatedEstablishments() {
		$this->db
			->select(
				'establishment.id,
				establishment.name,
				establishment.city,
				establishment.stateID,
				establishment.slug AS slug_establishment,
				state.stateAbbr,
				state.stateFull,
				COUNT(establishment.id) AS totalRatings,
				(SUM((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) / COUNT(establishment.id)) AS avgRating',
				false
			)
			->from('establishment')
			->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
			->join('establishment_categories', 'establishment_categories.id = establishment.categoryID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.active', '1')
			->where('rating_establishment.active', '1')
			->group_by('establishment.id')
			->having('COUNT(establishment.id) >', TOP_RATED_ESTABLISHMENTS_LIMIT, false)
			->order_by('avgRating', 'desc')
			->limit(TOP_RATED_ESTABLISHMENTS);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getEstablishmentsByCategoryState($stateID, $categoryID) {
		$this->db
			->select(
				'establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				state.id AS stateID,
				state.stateAbbr,
				state.stateFull,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				establishment_categories.name AS category,
				establishment.categoryID'
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('establishment_categories', 'establishment_categories.id = establishment.categoryID', 'inner')
			->where('establishment.stateID' , $stateID)
			->where('establishment_categories.id', $categoryID)
			->where('establishment.active', 1)
			->order_by('establishment.name', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getEstablishmentsByState($state) {
		$this->db
			->select(
				'establishment.city,
				state.id AS stateID,
				state.stateFull,
				COUNT(DISTINCT establishment.id) as totalPerCity',
				false
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'left outer')
			->where('establishment.stateID', $state)
			->where('establishment.active', 1)
			->group_by('state.id')
			->group_by('establishment.city')
			->order_by('establishment.city', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}

	public function getEstablishmentsByCity($state, $city) {
		$this->db
			->select(
				'establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				state.id AS stateID,
				state.stateAbbr,
				state.stateFull,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				establishment_categories.name AS category'
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID')
			->join('establishment_categories', 'establishment_categories.id = establishment.categoryID')
			->where('establishment.stateID', $state)
			->where('establishment.city', $city)
			->where('establishment.active', 1)
			->order_by('establishment.name', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getEstablishmentRating($establishmentID) {
		$query = '
			SELECT
				ROUND(AVG((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))), 1) AS averageRating,
				COUNT(rating_establishment.id) as totalRatings
			FROM establishment
			LEFT OUTER 
				JOIN rating_establishment 
				ON rating_establishment.establishmentID = establishment.id
				AND establishment.id = ' . $establishmentID . '
				AND establishment.active = 1
		';

		$rs = $this->db->query($query);
		if ($rs->num_rows() == 1) {
			return $rs->row();
		}
		return [];
	}
	
	public function getEstablishmentsByCategory($stateID) {
		$query = '
			SELECT
				establishment_categories.id,
				establishment_categories.name,
				COUNT(establishment.categoryID) AS totalPerCategory
			FROM establishment_categories
			LEFT OUTER 
				JOIN establishment 
					ON establishment.categoryID = establishment_categories.id
					AND establishment.stateID = ' . mysqli_real_escape_string($this->db->conn_id, $stateID) . '
					AND establishment.active = 1
			GROUP BY
				establishment_categories.id
				, establishment_categories.name
			ORDER BY
				IF(COUNT(establishment.categoryID) > 0, 1, 0) DESC
				, establishment_categories.id ASC
		';
		
		$rs = $this->db->query($query);
		if($rs->num_rows() > 0) {
			return $rs->result();
		}
		return [];
	}
	
	public function getEstablishmentInfoByID($establishmentID) {
		$this->db
			->select(
				'establishment.id,
				establishment.categoryID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.picture,
				establishment.pictureApproval,
                establishment.twitter,
                establishment.closed,
                establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateFull,
				state.stateAbbr,
				breweryhops.id AS breweryhopsID'
			)
			->from('establishment')
			->join('breweryhops', 'breweryhops.establishmentID = establishment.id', 'left outer')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.id', $establishmentID)
			->where('establishment.active', 1);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getEstblishmentRatingsByID($establishmentID) {
		$this->db
			->select(
				'establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateFull,
				state.stateAbbr,
				rating_establishment.id,
				DATE_FORMAT(rating_establishment.dateVisited, "%M %d, %Y") AS formatDateVisited,
				DATE_FORMAT(rating_establishment.dateAdded, "%W, %M %d, %Y at %T") AS formatDateAdded,
				ROUND((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100)), 1) AS rating,
				rating_establishment.comments,
				rating_establishment.price,
                rating_establishment.drink,
                rating_establishment.service,
                rating_establishment.atmosphere,
                rating_establishment.pricing,
                rating_establishment.accessibility,
                rating_establishment.visitAgain,
				rating_establishment.active,
				users.id AS userID,
				users.username,
				users.firstName,
				DATE_FORMAT(users.joindate, "%W, %M %d, %Y at %T") AS formatJoinDate,
				users.city AS userCity,
				users.state AS userState,
				users.avatar,
				users.avatarImage,
				breweryhops.id AS breweryhopsID',
				false
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
			->join('users', 'users.id = rating_establishment.userID', 'left outer')
			->join('breweryhops', 'breweryhops.establishmentID = establishment.id', 'left outer')
			->where('establishment.id', $establishmentID)
			->where('rating_establishment.active', '1')
			->order_by('rating_establishment.dateAdded', 'desc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
    
    public function getEstblishmentRatingsByRatingsID($ratingID) {
    	$this->db
    		->select(
    			'establishment.id AS establishmentID,
                establishment.name,
                establishment.address,
                establishment.city,
                establishment.zip,
                establishment.phone,
                establishment.url,
                establishment.slug AS slug_establishment,
                state.id AS stateID,
                state.stateFull,
                state.stateAbbr,
                rating_establishment.id,
                DATE_FORMAT(rating_establishment.dateVisited, "%M %d, %Y") AS formatDateVisited,
                DATE_FORMAT(rating_establishment.dateAdded, "%W, %M %d, %Y at %T") AS formatDateAdded,
                (rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100)) AS rating,
                rating_establishment.comments,
                rating_establishment.price,
                rating_establishment.active,
                users.id AS userID,
                users.username,
                users.firstName,
                DATE_FORMAT(users.joindate, "%W, %M %d, %Y at %T") AS formatJoinDate,
                users.city AS userCity,
                users.state AS userState,
                users.avatar,
                users.avatarImage,
                breweryhops.id AS breweryhopsID',
                false
    		)
    		->from('establishment')
    		->join('state', 'state.id = establishment.stateID', 'inner')
    		->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
    		->join('users', 'users.id = rating_establishment.userID', 'left outer')
    		->join('breweryhops', 'breweryhops.establishmentID = establishment.id', 'left outer')
    		->where('rating_establishment.id', $ratingID)
    		->where('rating_establishment.active', '1')
    		->order_by('rating_establishment.dateAdded', 'desc');

        $rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row_array();
		}
		return [];
    }
	
	public function getNumEstablishmentsAndAverageByUserID($userID) {
		$this->db
			->select(
				'COUNT(DISTINCT establishment.id) AS totalRatings,
				ROUND(AVG((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))), 1) as avergeRating',
				false
			)
			->from('establishment')
			->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
			->join('users', 'users.id = rating_establishment.userID', 'inner')
			->where('users.id', $userID);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getEstablishmentExistAndHasBeer($establishmentID) {
		$this->db
			->select('
				id,
				name,
				categoryID'
			)
			->from('establishment')
			->where('active', 1)
			->where('id', $establishmentID);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	return $rs->row();
        }
		return [];
	}
	
	public function getEstablishmentReviewCount($userID)
	{
		$this->db
			->select('COUNT(DISTINCT rating_establishment.id) AS establishmentsReviewed', false)
			->from('rating_establishment')
			->join('establishment', 'establishment.id = rating_establishment.establishmentID', 'inner')
			->join('users', 'users.id = rating_establishment.userID', 'inner')
			->where('users.id', $userID);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	$row = $rs->row();
        	return $row->establishmentsReviewed;
        }
		return 0;
	}
	
	/**
	 * Stores the data for a new Rating
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createRating($data) {
		// create the query for creating a new record
		$query = '
			INSERT INTO rating_establishment (
				id
				, establishmentID
				, userID
				, dateVisited
				, dateAdded
				, drink
                , service
                , atmosphere
                , pricing
                , accessibility
				, comments
				, visitAgain
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['userID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['dateVisited']) . '"
				, NOW()
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['drink']) . '
                , ' . mysqli_real_escape_string($this->db->conn_id, $data['service']) . '
                , ' . mysqli_real_escape_string($this->db->conn_id, $data['atmosphere']) . '
                , ' . mysqli_real_escape_string($this->db->conn_id, $data['pricing']) . '
                , ' . mysqli_real_escape_string($this->db->conn_id, $data['accessibility']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['visitAgain']) . '"
				, "1"
			)
		';
		// run the query
		$this->db->query($query);
		// return the id of the data that was just inserted
		return $this->db->conn_id->insert_id;
	}
	
	public function updateRatingByID($data) {
		$query = '
			UPDATE rating_establishment SET
				dateVisited = "' . mysqli_real_escape_string($this->db->conn_id, $data['dateVisited']) . '"
				, dateEdit = NOW()
				, drink = ' . mysqli_real_escape_string($this->db->conn_id, $data['drink']) . '
                , service = ' . mysqli_real_escape_string($this->db->conn_id, $data['service']) . '
                , atmosphere = ' . mysqli_real_escape_string($this->db->conn_id, $data['atmosphere']) . '
                , pricing = ' . mysqli_real_escape_string($this->db->conn_id, $data['pricing']) . '
                , accessibility = ' . mysqli_real_escape_string($this->db->conn_id, $data['accessibility']) . '
				, comments = "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"	
				, visitAgain = "' . mysqli_real_escape_string($this->db->conn_id, $data['visitAgain']) . '"			
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';
		$rs = $this->db->query($query);
	}
	
	public function updateImageByID($id, $picture) {
		// create the query
		$query = '
			UPDATE establishment
			SET
				picture = "' . mysqli_real_escape_string($this->db->conn_id, $picture) . '"
			WHERE
				id = ' . mysqli_real_escape_string($this->db->conn_id, $id)
		;
		// run the query 
		$this->db->query($query);
	}
	
	public function getRatingsForEstablishmentByID($id) {
		$this->db
			->select(
				'AVG((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) AS averagereview,
				COUNT(DISTINCT rating_establishment.id) AS reviews,
				AVG(CASE rating_establishment.pricing WHEN 10 THEN 1 WHEN 9 THEN 1 WHEN 8 THEN 2 WHEN 7 THEN 2 WHEN 6 THEN 3 WHEN 5 THEN 3 WHEN 4 THEN 4 WHEN 3 THEN 4 WHEN 2 THEN 5 WHEN 1 THEN 5 ELSE 1 END) AS averageprice,
				AVG(CASE rating_establishment.visitAgain WHEN 2 THEN 1 ELSE 0 END) AS averagevisitagain',
				false
			)
			->from('establishment')
			->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
			->where('establishment.id', $id)
			->where('establishment.active', '1')
			->where('rating_establishment.active', '1');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getRatingsForEstablishmentByIDTwoBeerDudes($id) {
		$this->db
			->select(
				'AVG((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) AS averagereview',
				false
			)
			->from('establishment')
			->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
			->join('users', 'users.id = rating_establishment.userID', 'inner')
			->where('establishment.id', $id)
			->where('establishment.active', '1')
			->where('rating_establishment.active', '1')
			->where_in('users.id', [1, 2]);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function setLatitudeAndLongitude($config = array()) {
		// create the query
		$query = '
			UPDATE establishment SET
				latitude = ' . $config['lat'] . '
				, longitude = ' . $config['long'] . '
			WHERE
				id = ' . $config['id']
		;
		// run the query
		$this->db->query($query);
	}
	
	public function determineDistance(StdClass $establishment) {
		$lat = $establishment->latitude;
		$lon = $establishment->longitude;
		
		$lon1 = $lon - (RADIUS_SEARCH / (cos(deg2rad($lon)) * 69));
		$lon2 = $lon + (RADIUS_SEARCH / (cos(deg2rad($lon)) * 69));
		$lat1 = $lat - (RADIUS_SEARCH / 69);
		$lat2 = $lat + (RADIUS_SEARCH / 69);
        
        if ($lon1 > $lon2) {
            $tmp = $lon1;
            $lon1 = $lon2;
            $lon2 = $tmp;
        }
        
        if ($lat1 > $lat2) {
            $tmp = $lat1;
            $lat1 = $lat2;
            $lat2 = $tmp;
        }

        $this->db
        	->select(
        		'establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
                establishment.latitude,
                establishment.longitude,
                establishment.url,
				establishment.stateID,
				establishment.slug AS slug_establishment,
				ROUND(3956 * 2 * ASIN(SQRT(POWER(SIN((' . $lat . ' - establishment.latitude) * PI()/180/2), 2) + COS(' . $lat . ' * PI()/180) * COS(establishment.latitude * PI()/180) * POWER(SIN((' . $lon . ' - establishment.longitude) * PI()/180/2), 2))), 2) AS distance,
				state.stateFull,
				state.stateAbbr',
				false
        	)
        	->from('establishment')
        	->join('state', 'state.id = establishment.stateID', 'inner')
        	->where('establishment.latitude IS NOT NULL', null, false)
        	->where('establishment.latitude >=', $lat1)
        	->where('establishment.latitude <=', $lat2)
        	->where('establishment.longitude IS NOT NULL', null, false)
        	->where('establishment.longitude <=', $lon2)
        	->where('establishment.longitude >=', $lon1)
        	->where('establishment.id !=', $establishment->establishmentID)
        	->having('distance <=', RADIUS_SEARCH)
        	->order_by('distance', 'asc')
        	->limit(10);

        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
    
    public function getEstablishmentsRatingUserID($userID) {
    	$this->db
    		->select('COUNT(rating_establishment.id) AS totalRatings')
    		->from('rating_establishment')
    		->join('users', 'users.id = rating_establishment.userID', 'inner')
    		->where('users.id', $userID);
    	$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	$row = $rs->row();
        	$count = $row->totalRatings;
		}
        
        if ($count > 0) {
            $limit = $count > ESTABLISHMENT_COUNT ? ESTABLISHMENT_COUNT : $count;
            $start = $count > ESTABLISHMENT_COUNT ? ($count - ESTABLISHMENT_COUNT - 1) : ($limit - 1);
            $rand = mt_rand(0, $start);

            $this->db
            	->select(
            		'establishment.id AS establishmentID,
                    establishment.name,
                    establishment.slug AS slug_establishment,
                    (rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100)) AS rating',
                    false
            	)
            	->from('establishment')
            	->join('rating_establishment', 'rating_establishment.establishmentID = establishment.id', 'inner')
            	->join('users', 'users.id = rating_establishment.userID', 'inner')
            	->where('rating_establishment.active', '1')
            	->where('users.id', $userID)
            	->order_by('establishment.name', 'asc')
            	->limit($limit, $rand);
            $rs = $this->db->get();
	        if ($rs->num_rows() > 0) {
	        	return $rs->result();
	        }
        }
        return [];
    }
    
    public function autoCompleteSearch($term) {
    	$this->db
    		->select(
    			'establishment.id,
    			establishment.name,
    			establishment.slug,
    			establishment.picture'
    		)
    		->from('establishment')
    		->like('establishment.name', $term, 'after')
    		->order_by('establishment.name', 'desc')
    		->limit(8);

    	$rs = $this->db->get();
    	if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
    }
}
?>