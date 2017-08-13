<?php
if (!defined('BASEPATH')) {
	exit ('No direct script access allowed');
}

class BeerModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}

	public function getAll()
	{
		$query = '
			SELECT
				be.id
				, be.beerName
				, be.alcoholContent
				, be.malts
				, be.hops
				, be.yeast
				, be.gravity
				, be.ibu
				, be.food
				, be.glassware
				, be.picture
				, be.seasonal	
				, be.seasonalPeriod							
				, e.id AS establishmentID
				, e.name
				, e.url
				, st.id as styleID
				, st.style
			FROM beers be
			INNER JOIN establishment e ON e.id = be.establishmentID
			INNER JOIN styles st ON st.id = be.styleID
			ORDER BY
				be.beerName ASC
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if ($rs->num_rows() > 0)
		{
			$array = $rs->result_array();
		}
		return $array;
	}
	
    public function getBeerByID($id)
    {
    	$this->db
    		->select(
    			'beers.id,
                beers.beerName,
                beers.alcoholContent,
                beers.beerNotes,
                beers.malts,
                beers.hops,
                beers.yeast,
                beers.gravity,
                beers.ibu,
                beers.food,
                beers.glassware,
                beers.picture,
                beers.seasonal,
                beers.seasonalPeriod,
                beers.retired,
                beers.slug AS slug_beer,
                establishment.id AS establishmentID,
                establishment.name,
                establishment.address,
                establishment.city,
                establishment.zip,
                establishment.url,
                establishment.closed,
                establishment.twitter,
                establishment.slug AS slug_establishment,
                styles.id as styleID,
                styles.style,
                state.id AS stateID,
                state.stateFull,
                state.stateAbbr'
    		)
    		->from('beers')
    		->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
    		->join('styles', 'styles.id = beers.styleID', 'inner')
    		->join('state', 'state.id = establishment.stateID')
    		->where('beers.id', $id);

    	$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->row();
		}
		return [];
    }
    
    public function getTrendByBeerID($id) {
        $query = '
            SELECT
                AVG((aroma * (' . PERCENT_AROMA . ' / 100)) + (taste * (' . PERCENT_TASTE . ' / 100)) + (look * (' . PERCENT_LOOK . ' / 100)) + (drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS trendrating
            FROM (
                SELECT
                    r.aroma
                    , r.taste
                    , r.look
                    , r.drinkability
                FROM beers be                
                LEFT OUTER JOIN ratings r ON r.beerID = be.id
                INNER JOIN users u ON u.id = r.userID
                WHERE
                    be.id = ' . $id . '
                    AND u.active = "1"
                    AND u.banned = "0"
                ORDER BY
                    r.dateAdded DESC
                LIMIT ' . TREND_BEER_RATING_LIMIT . '
            ) AS tmp
        ';

        $rs = $this->db->query($query);
        if ($rs->num_rows() == 1)
        {
            return $rs->row();
        }
        return [];
    }
	
	public function getAllForDropDownByBrewery($id) {
		$query = '
			SELECT
				be.id
				, be.beerName AS name
			FROM establishment e
			INNER JOIN beers be ON be.establishmentID = e.id
			WHERE
				e.id = ' . $id . '
				AND categoryID = 1
			ORDER BY
				be.beerName
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getAllForDropDownSwaps($userID) {
		$query = '
			SELECT
				be.id
				, CONCAT(be.beerName, " - ", e.name) AS name
			FROM establishment e
			INNER JOIN beers be ON be.establishmentID = e.id
			WHERE
				e.categoryID = 1
				AND be.id NOT IN (
					SELECT
						beerID
					FROM swapins						
					WHERE
						userID = ' . $userID . '
				)
				AND be.id NOT IN (
					SELECT
						beerID
					FROM swapouts						
					WHERE
						userID = ' . $userID . '
				)
			ORDER BY
				be.beerName
		';
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function updateBeerByID($data) {
		$query = '
			UPDATE beers SET
				establishmentID = ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, beerName = "' . mysqli_real_escape_string($this->db->conn_id, $data['beerName']) . '"
				, styleID = ' . mysqli_real_escape_string($this->db->conn_id, $data['styleID'])
		;
		$query .= !empty($data['alcoholContent']) ? ', alcoholContent = ' . mysqli_real_escape_string($this->db->conn_id, $data['alcoholContent']) : '';
		$query .= !empty($data['malts']) ? ', malts = "' . mysqli_real_escape_string($this->db->conn_id, $data['malts']) . '"' : '';
		$query .= !empty($data['hops']) ? ', hops = "' . mysqli_real_escape_string($this->db->conn_id, $data['hops']) . '"' : '';
		$query .= !empty($data['yeast']) ? ', yeast = "' . mysqli_real_escape_string($this->db->conn_id, $data['yeast']) . '"' : '';
		$query .= !empty($data['gravity']) ? ', gravity = ' . mysqli_real_escape_string($this->db->conn_id, $data['gravity']) : '';
		$query .= !empty($data['ibu']) ? ', ibu = ' . mysqli_real_escape_string($this->db->conn_id, $data['ibu']) : '';
		$query .= !empty($data['food']) ? ', food = "' . mysqli_real_escape_string($this->db->conn_id, $data['food']) . '"' : '';
		$query .= !empty($data['glassware']) ? ', glassware = "' . mysqli_real_escape_string($this->db->conn_id, $data['glassware']) . '"' : '';
		$query .= !empty($data['picture']) ? ', picture = "' . mysqli_real_escape_string($this->db->conn_id, $data['picture']) . '"' : '';
		$query .= '
				, seasonal = "' . mysqli_real_escape_string($this->db->conn_id, $data['seasonal']) . '"
		';
		$query .= $data['seasonal'] == 1 ? ', seasonalPeriod = "' . mysqli_real_escape_string($this->db->conn_id, $data['seasonalPeriod']) . '"' : ', seasonalPeriod = NULL';
		$query .= '
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';
		$rs = $this->db->query($query);
	}
	
	/**
	 * Stores the data for a new Beer
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createBeer($data) {
		$this->db->insert('beers', $data);
		return $this->db->insert_id();
		// create the query for creating a new record
		$query = '
			INSERT INTO beers (
				id
				, establishmentID
				, userID
				, beerName
				, styleID
		';
		$query .= !empty($data['alcoholContent']) ? ', alcoholContent' : '';
        $query .= !empty($data['beerNotes']) ? ', beerNotes' : '';
		$query .= !empty($data['malts']) ? ', malts' : '';
		$query .= !empty($data['hops']) ? ', hops' : '';
		$query .= !empty($data['yeast']) ? ', yeast' : '';
		$query .= !empty($data['gravity']) ? ', gravity' : '';
		$query .= !empty($data['ibu']) ? ', ibu' : '';
		$query .= !empty($data['food']) ? ', food' : '';
		$query .= !empty($data['glassware']) ? ', glassware' : '';
		$query .= !empty($data['picture']) ? ', picture' : '';
		$query .= '
				, seasonal
		';
		$query .= $data['seasonal'] == 1 ?	', seasonalPeriod' : '';
		$query .= '
				, dateAdded
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, ' . $data['userID'] . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['beerName']) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['styleID']) . '
		';
		$query .= !empty($data['alcoholContent']) ? ', ' . mysqli_real_escape_string($this->db->conn_id, $data['alcoholContent']) : '';
        $query .= !empty($data['beerNotes']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['beerNotes']) . '"': '';
		$query .= !empty($data['malts']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['malts']) . '"' : '';
		$query .= !empty($data['hops']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['hops']) . '"' : '';
		$query .= !empty($data['yeast']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['yeast']) . '"' : '';
		$query .= !empty($data['gravity']) ? ', ' . mysqli_real_escape_string($this->db->conn_id, $data['gravity']) : '';
		$query .= !empty($data['ibu']) ? ', ' . mysqli_real_escape_string($this->db->conn_id, $data['ibu']) : '';
		$query .= !empty($data['food']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['food']) . '"' : '';
		$query .= !empty($data['glassware']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['glassware']) . '"' : '';
		$query .= !empty($data['picture']) ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['picture']) . '"' : '';
		$query .= '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['seasonal']) . '"
		';
		$query .= $data['seasonal'] == 1 ? ', "' . mysqli_real_escape_string($this->db->conn_id, $data['seasonalPeriod']) . '"' : '';
		$query .= '
				, NOW()
				, "1"
			)
		';
		// run the query
		$this->db->query($query);
		// return the id of the data that was just inserted
		return $this->db->conn_id->insert_id;
	}
	
	public function getBeerRatingsByID($id)
	{//ratings.rating,
		$this->db
			->select(
				'beers.id,
                beers.beerName,
                beers.alcoholContent,
                beers.beerNotes,
                beers.alias,
                beers.malts,
                beers.hops,
                beers.yeast,
                beers.gravity,
                beers.ibu,
                beers.food,
                beers.glassware,
                beers.picture,
                beers.seasonal,
                beers.seasonalPeriod,
                beers.retired,
                beers.slug AS slug_beer,
                establishment.id AS establishmentID,
                establishment.name,
                establishment.address,
                establishment.city,
                establishment.zip,
                establishment.phone,
                establishment.url,
                establishment.twitter,
                establishment.closed,
                establishment.slug AS slug_establishment,
                state.id AS stateID,
                state.stateFull,
                state.stateAbbr,
                styles.id AS styleID,
                styles.style,
                DATE_FORMAT(ratings.dateTasted, "%M %d, %Y") AS formatDateTasted,
                DATE_FORMAT(ratings.dateAdded, "%W, %M %d, %Y at %T") AS formatDateAdded,
                ratings.color,
                ROUND((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100)), 1) AS rating,
                ratings.comments,
                ratings.haveAnother,
                ratings.shortrating,
                ratings.aroma,
                ratings.taste,
                ratings.look,
                ratings.drinkability,
                ratings.price,
                package.package,
                users.id AS userID,
                users.username,
                users.firstName,
                DATE_FORMAT(users.joindate, "%W, %M %d, %Y at %T") AS formatJoinDate,
                users.city AS userCity,
                users.state AS userState,
                users.avatar,
                users.avatarImage,
                breweryhops.id AS breweryhopsID', false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('package', 'package.id = ratings.packageID', 'left outer')
			->join('users', 'users.id = ratings.userID', 'left outer')
			->join('breweryhops', 'breweryhops.establishmentID = establishment.id', 'left outer')
			->where('beers.id', $id)
			->order_by('ratings.dateAdded', 'desc');

		$rs = $this->db->get();
		//echo '<pre>' . print_r($this->db->last_query(), true) . '</pre>'; exit;
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getBeerRating($id)
	{
		//AVG(CASE WHEN aroma = 0 THEN r.rating ELSE ((aroma * (' . PERCENT_AROMA . ' / 100)) + (taste * (' . PERCENT_TASTE . ' / 100)) + (look * (' . PERCENT_LOOK . ' / 100)) + (drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) END) AS averagerating
		$this->db
			->select(
				'COUNT(ratings.id) AS timesrated,
				SUM(CASE WHEN ratings.aroma = 0 THEN 1 ELSE 0 END) AS totaltimerated,
				AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS averagerating',
				false
			)
			->from('beers')
			->join('ratings', 'ratings.beerID = beers.id', 'left')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('beers.id', $id)
			->where('users.active', '1')
			->where('users.banned', '0');

		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return [];
	}
	
	public function getBestWorstBeers($type)
	{
		$ob = $type == 'low' ? 'asc' : 'desc';
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.slug AS slug_beer,
				beers.picture,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.slug AS slug_establishment,
				styles.id AS styleID,
				styles.style,
				COUNT(ratings.id) AS totalratings,
				AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS averagerating',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'left outer')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('users.active', '1')
			->where('users.banned', '0')
			->group_by('beers.beerName')
			->having('totalratings >', HIGHEST_RATED_LIMIT_RATINGS, false)
			->order_by('averagerating', $ob)
			->order_by('totalratings', $ob)
			->order_by('beers.beerName')
			->limit(HIGHEST_RATED_LIMIT);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getBestWorstStyles($type)
	{
		$ob = $type == 'low' ? 'asc' : 'desc';
		$this->db
			->select(
				'styles.id,
				styles.style,
				ROUND(AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))), 1) AS avgRating,
				COUNT(ratings.id) AS totalRatings,
				COUNT(DISTINCT beers.id) AS totalBeers',
				false
			)
			->from('styles')
			->join('beers', 'beers.styleID = styles.id', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'left outer')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('users.active', '1')
			->where('users.banned', '0')
			->group_by('styles.id')
			->having('totalRatings >', HIGHEST_RATED_BY_STYLE_LIMIT_RATINGS, false)
			->order_by('avgRating', $ob)
			->order_by('totalRatings', $ob)
			->order_by('styles.style', 'asc')
			->limit(HIGHEST_RATED_BY_STYLE_LIMIT);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getImageByID($id) {
		// create the query
		$query = '
			SELECT
				be.picture
				, be.beerName
				, e.name
			FROM beers be
			INNER JOIN establishment e ON e.id = be.establishmentID
			WHERE
				be.id = ' . mysqli_real_escape_string($this->db->conn_id, $id)
		;
		// run the query
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() == 1) {
			$array = $rs->row_array();
		}
		return $array;
	}

	public function updateImageByID($id, $picture) {
		// create the query
		$query = '
			UPDATE beers
			SET
				picture = "' . mysqli_real_escape_string($this->db->conn_id, $picture) . '"
			WHERE
				id = ' . $id
		;
		// run the query 
		$this->db->query($query);
	}
	
	public function removeImageByID($id) {
		// create the query
		$query = '
			UPDATE beers
			SET
				picture = NULL
			WHERE
				id = ' . $id
		;
		// run the query
		$this->db->query($query);
	}
	
	public function getPackageCount($id)
	{
		$this->db
			->select('package.package, COUNT(ratings.packageID) AS totalPackages', false)
			->from('beers')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->where('beers.id', $id)
			->where('ratings.shortrating', '0')
			->group_by('package.package')
			->order_by('package.package', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getAllBeerStyles() {
		$this->db
			->select('id, style, origin, styleType')
			->from('styles')
			->order_by('styleType', 'asc')
			->order_by('origin', 'asc')
			->order_by('style', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}

	public function getBeerByStyleCount($style_id) {
		$this->db
			->select('COUNT(DISTINCT beers.id) AS totalBeers', false)
			->from('styles')
			->join('beers', 'beers.styleID = styles.id', 'left outer')
			->join('establishment', 'establishment.id = beers.establishmentID', 'left outer')
			->join('ratings', 'ratings.beerID = beers.id', 'left outer')
			->where('styles.id', $style_id)
			->where('establishment.active', 1)
			->where('ratings.active', 1);
	
		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	$row = $rs->row();
        	return $row->totalBeers;
		}
		return 0;
	}
	
	public function getBeerStyleByID($id, $offset) {
		$this->db
			->select(
				'styles.id,
				styles.style,
				beers.id AS beerID,
				beers.beerName,
				beers.alcoholContent,
				beers.picture,
				beers.slug AS slug_beer,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.slug AS slug_establishment,
				ROUND(AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))), 1) AS avgRating,
				ROUND(AVG(ratings.price), 2) AS avgPrice,
				ROUND((AVG(IF(ratings.haveAnother = 2, 1, 0)) * 100), 0) AS avgHaveAnother,
				COUNT(beers.id) as totalRatings',
				false
			)
			->from('styles')
			->join('beers', 'beers.styleID = styles.id', 'left outer')
			->join('establishment', 'establishment.id = beers.establishmentID', 'left outer')
			->join('ratings', 'ratings.beerID = beers.id', 'left outer')
			->where('styles.id', $id)
			->where('establishment.active', 1)
			->where('ratings.active', 1)
			->group_by('beers.id')
			//->having('avgRating >', 0)
			->order_by('beers.beerName', 'asc')
			->limit(BEER_STYLE_PAGINATION, $offset);

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
        }
		return [];
	}
	
	public function getAvgCostPerPackage($id)
	{
		$this->db
			->select(
				'beers.id,
				package.package,
				COUNT(ratings.id) AS totalServings,
				ROUND(AVG(ratings.price), 2) AS averagePrice', false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->where('beers.id', $id)
			->where('ratings.shortrating', '0')
			->where('establishment.categoryID', 1)
			->where('ratings.price >', 0)
			->group_by('beers.id');

		$rs = $this->db->get();
		if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getHaveAnotherPercent($id)
	{
		$this->db
			->select(
				'beers.id,
				ROUND((AVG(CASE ratings.haveAnother WHEN 2 THEN 1 ELSE 0 END)) * 100, 0) AS percentHaveAnother', false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->where('beers.id', $id)
			->where_in('establishment.categoryID', [1, 4, 6])
			->group_by('beers.id')
			->order_by('beers.id', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	//return $rs->row();
        	$row = $rs->row();
        	return $row->percentHaveAnother;
		}
		return false;
	}
	
	public function tastedTwoBeerDudes($id)
	{
		$this->db
			->select('AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')) AS avergeRating', false)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('users',  'users.id = ratings.userID', 'inner')
			->where('beers.id', $id)
			->where_in('users.id', [1, 2]);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->row();
		}
		return [];
	}
	
	public function similarBeerByBeerIDAndStyleID($beerID, $styleID) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.alcoholContent,
				beers.picture,,
				beers.slug AS slug_beer,
				establishment.slug AS slug_establishment,
				establishment.id AS establishmentID,
				establishment.name,
				styles.style,
				ROUND(AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')), 1) AS avgRating,
				COUNT(ratings.id) AS totalRatings',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('beers.id <>',  $beerID)
			->where('styles.id', $styleID)
			->group_by('beers.id')
			->order_by('avgRating', 'desc')
			->order_by('totalRatings', 'desc')
			->order_by('beers.beerName', 'asc')
			->limit(8);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function similarBeerByStyleID($styleID) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.alcoholContent,
				beers.picture,
				beers.slug AS slug_beer,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.slug AS slug_establishment,
				styles.style,
				ROUND(AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))), 1) AS avgRating,
				COUNT(ratings.rating) AS totalRatings',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('styles.id', $styleID)
			->group_by('beers.id')
			->order_by('avgRating', 'desc')
			->order_by('totalRatings', 'desc')
			->order_by('beers.beerName', 'asc')
			->limit(8);
		
		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getStyleIDByBeerID($id)
	{
		$this->db
			->select('beers.styleID')
			->from('beers')
			->where('beers.id', $id);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	$row = $rs->row();
        	return $row->styleID;
		}
		return 0;
	}
	
	public function getBeerRatingByStyleAndUserID($styleID, $userID, $beerID)
	{
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.alcoholContent,
				beers.picture,
				establishment.id AS establishmentID,
				establishment.name,
				ROUND((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . '), 1) AS rating',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('styles.id', $styleID)
			->where('users.id', $userID)
			->where('beers.id <>', $beerID)
			->order_by('beers.beerName', 'acs')
			->limit(SIMILAR_BEER_RATINGS);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getNumBeersAndAverageByUserID($userID)
	{
		$this->db
			->select(
				'COUNT(DISTINCT beers.id) AS beersTasted,
				ROUND(AVG((ratings.aroma * .' . PERCENT_AROMA . ') + (ratings.taste * .' . PERCENT_TASTE . ') + (ratings.look * .' . PERCENT_LOOK . ') + (ratings.drinkability * .' . PERCENT_DRINKABILITY . ')), 1) AS rating',
				false
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('ratings', 'ratings.beerID = beers.id', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('users.id', $userID);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
        	return $rs->row();
		}
		return [];
	}
	
	public function getBeerReviewCount($userID) {
		$this->db
			->select('COUNT(DISTINCT ratings.id) AS beersTasted', false)
			->from('ratings')
			->join('beers', 'beers.id = ratings.beerID', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('users.id', $userID);

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	$row = $rs->row();
        	return $row->beersTasted;
		}
		return 0;
	}
	
	public function getBeersByEstablishmentID($establishmentID) {
		$this->db
			->select(
				'beers.id,
				beers.beerName,
				beers.styleID,
				beers.slug AS slug_beer,
				styles.style'
			)
			->from('beers')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->where('establishment.id', $establishmentID)
			->order_by('beerName', 'asc');
		
		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
	}
	
	public function getTotalBeersInDB() {
		// the query
		$query = '
			SELECT
				COUNT(id) AS beerCount
			FROM beers	
		';
		// get the record set
		$rs = $this->db->query($query);
		// get the number of rows
		$row = $rs->row_array();
		// get the result and send it back
		return $row['beerCount'];
	}
    
    public function autoCompleteSearch($term) {
    	$this->db
    		->select(
    			'beers.id,
    			CONCAT(beers.beerName, " - ", establishment.name) AS name,
    			beers.slug,
    			beers.picture',
    			false
    		)
    		->from('beers')
    		->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
    		->like('beers.beerName', $term, 'after')
    		->order_by('beers.beerName', 'desc')
    		->limit(8);

    	$rs = $this->db->get();
    	if ($rs->num_rows() > 0) {
        	return $rs->result();
		}
		return [];
    }
}