<?php
if(!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class BreweriesModel extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	public function getAll() {
		$query = '
			SELECT
				e.id
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.url
				, s.id AS stateID
				, s.stateFull
				, s.stateAbbr
			FROM establishment e
			INNER JOIN state s ON s.id = e.stateID
			WHERE
				categoryID IN (1, 4)
			ORDER BY
				name ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getAllForDropDown() {
		$query = '
			SELECT
				id
				, name
			FROM establishment
			WHERE
				categoryID IN (1, 4)
			ORDER BY
				name ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getBreweryByID($id) {
		$this->db
			->select(
				'establishment.id,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateFull,
				state.stateAbbr',
				false
			)
			->from('establishment')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('establishment.id', $id)
			->where_in('establishment.categoryID', [1, 4]);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return [];
	}
	
	public function updateBreweryByID($data) {
		// holder for the phone number
		$phone = $data['phone'];
		// check if phone number is empty
		if(empty($phone)) {
			$phone = 'NULL';
		}
		
		$query = '
			UPDATE establishment SET
				name = "' . mysqli_real_escape_string($this->db->conn_id, $data['name']) . '"
				, categoryID = ' . mysqli_real_escape_string($this->db->conn_id, $data['categoryID']) . '
				, address = "' . mysqli_real_escape_string($this->db->conn_id, $data['address']) . '"
				, city = "' . mysqli_real_escape_string($this->db->conn_id, $data['city']) . '"
				, stateID = ' . mysqli_real_escape_string($this->db->conn_id, $data['stateID']) . '
				, zip = "' . mysqli_real_escape_string($this->db->conn_id, $data['zip']) . '"
				, phone = ' . mysqli_real_escape_string($this->db->conn_id, $phone) . '
				, url = "' . mysqli_real_escape_string($this->db->conn_id, $data['url']) . '"				
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';
		$rs = $this->db->query($query);
	}
	
	/**
	 * Stores the data for a new Brewery
	 * only to be used by the admin as it has
	 * forced activation
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createBrewery($data) {
		// create the query for creating a new record
		$query = '
			INSERT INTO establishment (
				id
				, userID
				, categoryID
				, name
				, address
				, city
				, stateID
				, zip
				, phone
				, url
				, active
				, dateAdded
			) VALUES (
				NULL
				, ' . $data['userID'] . '
				, 1
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['name']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['address']) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['city']) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['stateID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['zip']) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['phone']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['url']) . '"			
				, "1"	
				, NOW()
			)
		';
		// run the query
		$this->db->query($query);
		// return the id of the data that was just inserted
		return $this->db->conn_id->insert_id;
	}
	
	/**
	 * create an establishment
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createEstablishment($data) {
		//echo '<pre>'; print_r($data); exit;
		// create the query for creating a new record
		$query = '
			INSERT INTO establishment (
				id,
				userID,
				categoryID,
				name,
		';
		$query .= !empty($data['address']) ? 'address, ' : '';
		$query .= !empty($data['city']) ? 'city, ' : '';
		$query .= '
				stateID,
		';
		$query .= !empty($data['zip']) ? 'zip, ' : '';
		$query .= !empty($data['phone']) ? 'phone, ' : '';
		$query .= !empty($data['url']) ? 'url, ' : '';
		$query .= !empty($data['twitter']) ? 'twitter, ' : '';
		$query .= '
				slug,
				active,
				dateAdded
			) VALUES (
				NULL
				, ' . $data['userID'] . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['categoryID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['name']) . '"
		';
		$query .= !empty($data['address']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['address']) . '"' : '';
		$query .= !empty($data['city']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['city']) . '"' : '';
		$query .= '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['state'])
		;
		$query .= !empty($data['zip']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['zip']) . '"' : '';
		$query .= !empty($data['phone']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['phone']) . '"' : '';
		$query .= !empty($data['url']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['url']) . '"' : '';
		$query .= !empty($data['twitter']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['twitter']) . '"' : '';
		$query .= '
				, "' . $data['slug'] . '"
				, "1"
				, NOW()
			)
		';
		$this->db->query($query);
		return $this->db->conn_id->insert_id;
	}
	
	public function getBreweryInfoByID($id) {
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
                establishment.twitter,
				establishment.picture,
				establishment.pictureApproval,
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
			->where('establishment.id', $id);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getAllRatingsForBreweryByID($id) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.seasonal,
				beers.retired,
				beers.slug AS slug_beer,
				beers.picture,
				establishment.name,
				establishment.slug AS slug_establishment,
				styles.id AS styleID,
				styles.style,
				AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')) AS averagereview,
				COUNT(DISTINCT ratings.id) AS reviews',
				false
			)
			->from('establishment')
			->join('beers', 'beers.establishmentID = establishment.id', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id AND ratings.active = "1"', 'left outer')
			->where('establishment.id', $id)
			->where('establishment.active', '1')
			->group_by('beers.retired')
			->group_by('beers.id')
			->group_by('beers.beerName')
			->group_by('styles.id')
			->group_by('styles.style')
			->order_by('beers.retired', 'desc')
			->order_by('beers.beerName', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}

	public function getAllActiveRatingsForBreweryByID($id) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.seasonal,
				beers.active,
				beers.retired,
				beers.slug AS slug_beer,
				beers.picture,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.slug AS slug_establishment,
				styles.id AS styleID,
				styles.style,
				ROUND(AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')), 1) AS averagereview,
				COUNT(DISTINCT ratings.id) AS reviews,
				ROUND(AVG(ratings.price), 2) AS avgPrice,
				ROUND((AVG(IF(ratings.haveAnother = 2, 1, 0)) * 100), 0) AS avgHaveAnother',
				false
			)
			->from('establishment')
			->join('beers', 'beers.establishmentID = establishment.id', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id AND ratings.active = "1"', 'left outer')
			->where('establishment.id', $id)
			->where('establishment.active', '1')
			->where('beers.retired', '0')
			->group_by('beers.id')
			->group_by('beers.beerName')
			->group_by('styles.id')
			->group_by('styles.style')
			->order_by('beers.beerName', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}

	public function getAllRetiredRatingsForBreweryByID($id) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.seasonal,
				beers.active,
				beers.retired,
				beers.slug AS slug_beer,
				beers.picture,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.slug AS slug_establishment,
				styles.id AS styleID,
				styles.style,
				ROUND(AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')), 1) AS averagereview,
				COUNT(DISTINCT ratings.id) AS reviews,
				ROUND(AVG(ratings.price), 2) AS avgPrice,
				ROUND((AVG(IF(ratings.haveAnother = 2, 1, 0)) * 100), 0) AS avgHaveAnother',
				false
			)
			->from('establishment')
			->join('beers', 'beers.establishmentID = establishment.id', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id AND ratings.active = "1"', 'left outer')
			->where('establishment.id', $id)
			->where('establishment.active', '1')
			->where('beers.retired', '1')
			->group_by('beers.id')
			->group_by('beers.beerName')
			->group_by('styles.id')
			->group_by('styles.style')
			->order_by('beers.beerName', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getAllBrewreyHops($limit = 8) {
		$this->db
			->select(
				'breweryhops.id,
				DATE_FORMAT(breweryhops.hopDate, "%W, %M %d, %Y") AS hopDate,
				breweryhops.article,
				breweryhops.brewerypic,
				breweryhops.shorttext,
				breweryhops.author,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateAbbr,
				state.stateFull', false
			)
			->from('breweryhops')
			->join('establishment', 'establishment.id = breweryhops.establishmentID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner');

		if ($limit == 1) {
			$this->db->order_by('RAND()');
			$this->db->limit($limit);
		}
		else {
			$this->db->order_by('breweryhops.hopDate', 'desc');
			$this->db->order_by('breweryhops.id', 'desc');
		}

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
			return $rs->result();
        }
		return [];
	}
	
	public function getBreweryHopByID($id) {
		$this->db
			->select(
				'breweryhops.id,
				DATE_FORMAT(breweryhops.hopDate, "%W, %M %d, %Y") AS hopDate,
				breweryhops.article,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.id AS stateID,
				state.stateAbbr,
				state.stateFull',
				false
			)
			->from('breweryhops')
			->join('establishment', 'establishment.id = breweryhops.establishmentID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->where('breweryhops.id', $id);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
			return $rs->row();
        }
		return [];
	}
	
	public function getTotalEachBeer($id) {
		$this->db
			->select(
				'COUNT(beers.id) AS totalBeers,
				SUM(CASE WHEN ratings.rating IS NOT NULL THEN ratings.rating ELSE ((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')) END) AS totalPoints',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->where('establishment.id', $id)
			->where('establishment.active', 1)
			->where('ratings.active', 1);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getHighestRatedBreweries() {
		$this->db
			->select(
				'establishment.id,
				establishment.name,
				establishment.slug AS slug_establishment,
				COUNT(beers.id) AS beerTotal,
				AVG(CASE WHEN ratings.rating IS NOT NULL THEN ratings.rating ELSE ((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')) END) as avgRating'
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->where('establishment.active', 1)
			->where('ratings.active', 1)
			->group_by('establishment.id')
			->having('COUNT(beers.id) >', TOP_RATED_LIMIT, false)
			->order_by('avgRating', 'desc')
			->limit(TOP_RATED_BREWERIES);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getDistinctBeerCount($id) {
		$this->db
			->select('COUNT(DISTINCT beers.id) AS totalBeers', false)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->where('establishment.id', $id);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getAvgCostPerPackage($id) {
		$this->db
			->select(
				'beers.id,
				package.package,
				COUNT(ratings.id) AS totalServings,
				ROUND(AVG(CASE WHEN ratings.price > 0.00 THEN ratings.price END), 2) AS averagePrice',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->where('establishment.id', $id)
			->where('ratings.price >', 0)
			->group_by('beers.id')
			->group_by('package.package')
			->order_by('averagePrice', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getOverallAverageCostOfBeerByEstablishmentID($id) {
		$this->db
			->select('ROUND(AVG(CASE WHEN ratings.price > 0.00 THEN ratings.price END), 2) AS averagePrice', false)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->where('establishment.id', $id);

		$rs = $this->db->get();
		if ($rs->num_rows() == 1) {
        	return $rs->row();
		}
		return [];
	}
	
	public function getOverallAverageCostOfBeer($order = '') {
		$order = empty($order) ? 'asc' : $order;
		$this->db
			->select(
				'establishment.name,
				establishment.id,
				establishment.slug AS slug_establishment,
				COUNT(ratings.id) AS totalServings,
				ROUND(AVG(CASE WHEN ratings.price > 0.00 THEN ratings.price END), 2) AS averagePrice',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->group_by('establishment.id')
			->group_by('establishment.name')
			->having('averagePrice >', 0.00)
			->order_by('averagePrice', $order)
			->limit(AVERAGE_COST_FOR_BEER);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getHaveAnotherPercent($id) {
		$this->db
			->select(
				'beers.id,
				AVG(CASE ratings.haveAnother WHEN 2 THEN 1 ELSE 0 END) AS percentHaveAnother',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->where('establishment.id', $id)
			->group_by('beers.id')
			->order_by('beers.id');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getHaveAnotherPercentByEstablishment($id) {
		// create the query
		$query = '
			SELECT
				e.id
				, AVG(CASE r.haveAnother WHEN 2 THEN 1 ELSE 0 END) AS percentHaveAnother
				, COUNT(DISTINCT be.id) AS totalBeers
				, COUNT(r.id) AS totalDrank
			FROM beers be
			INNER JOIN establishment e ON e.id = be.establishmentID
			INNER JOIN ratings r ON r.beerID = be.id
			WHERE
				e.id = ' . mysqli_real_escape_string($this->db->conn_id, $id) . '
				AND e.active = 1
				AND r.active = 1
			GROUP BY
				e.id
			ORDER BY
				e.id			
		';
		// get the record set
		$rs = $this->db->query($query);
		// temporary holder for results
		$array = array();
		// check if there are any results in the record set
		if($rs->num_rows() > 0) {
			$array = $rs->row_array();
		}
		// return results
		return $array;
	}
	
	public function getBreweryByCity($state, $city) {
		// create the query
		$query = '
			SELECT
				e.id AS establishmentID
				, e.categoryID
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.picture
				, e.pictureApproval
				, e.url
				, st.id AS stateID
				, st.stateAbbr
				, st.stateFull
			FROM establishment e
			INNER JOIN state st ON st.id = e.stateID
			WHERE
				e.city = "' . mysqli_real_escape_string($this->db->conn_id, $city) . '"
				AND e.stateID = ' . mysqli_real_escape_string($this->db->conn_id, $state) . '
				AND e.active = 1
			ORDER BY
				e.city ASC
				, e.name ASC
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
	
	public function getBreweryByState($state) {
		// create the query
		$query = '
			SELECT
				e.id AS establishmentID
				, e.categoryID
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.picture
				, e.pictureApproval
				, e.url
				, st.id AS stateID
				, st.stateAbbr
				, st.stateFull
			FROM establishment e
			LEFT JOIN state st ON st.id = e.stateID
			WHERE
				e.stateID = ' . mysqli_real_escape_string($this->db->conn_id, $state) . '
				AND e.active = 1
			ORDER BY
				e.name		
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
	
	public function getAllCategoriesForDropDown() {
		$this->db
			->select('id, name')
			->from('establishment_categories')
			->order_by('id', 'asc');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return $rs->result_array();
		}
		return [];
	}
	
	public function getCategoryCheck($categoryID) {
		$this->db
			->select('id')
			->from('establishment_categories')
			->where('id', $categoryID);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0) {
        	return true;
		}
		return false;
	}
}