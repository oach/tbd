<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class RatingModel extends CI_Model
{
	public function __construct() {
		parent::__construct();
	}

	//, DATE_FORMAT((CASE WHEN TIMEDIFF(r.dateAdded, r.dateEdit) < 0 THEN r.dateEdit ELSE r.dateAdded END), "%W, %M %D, %Y at %T") AS dateAdded
	//CASE WHEN aroma = 0 THEN r.rating ELSE ((aroma * (' . PERCENT_AROMA . ' / 100)) + (taste * (' . PERCENT_TASTE . ' / 100)) + (look * (' . PERCENT_LOOK . ' / 100)) + (drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) END AS rating
	public function getAll($limit = 0) {
		$this->db
			->select(
				'ratings.id,
				DATE_FORMAT(ratings.dateTasted, "%W, %M %D, %Y") AS dateTasted,
				DATE_FORMAT(ratings.dateAdded, "%W, %M %D, %Y at %T") AS dateAdded,
				ratings.color,
				(ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + 
					(ratings.taste * (' . PERCENT_TASTE . ' / 100)) + 
					(ratings.look * (' . PERCENT_LOOK . ' / 100)) + 
					(ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100)) AS rating,
				ratings.comments,
				ratings.haveAnother,
				ratings.price,
				ratings.shortrating,
				ratings.aroma,
				ratings.taste,
				ratings.look,
				ratings.drinkability,
				beers.id AS beerID,
				beers.beerName,
				beers.alcoholContent,
				beers.malts,
				beers.hops,
				beers.yeast,
				beers.gravity,
				beers.ibu,
				beers.food,
				beers.glassware,
				beers.picture,
				beers.slug AS slug_beer,
				beers.seasonal,
				beers.seasonalPeriod,
				styles.id AS styleID,
				styles.style,
				package.package,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.stateAbbr,
				users.id AS userID,
				users.username,
				users.firstname,
				users.lastname', false
			)
			->from('ratings')
			->join('beers', 'beers.id = ratings.beerID', 'inner')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner');
		
		if ($limit > 0)
		{
			$this->db->order_by('ratings.dateAdded', 'desc');
			$this->db->limit($limit);
		}
		$this->db->order_by('beers.beerName', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
			return $rs->result();
        }
		return array();
	}

	public function getTotalRatingCount()
	{
		$this->db
			->select('COUNT(DISTINCT ratings.id) AS totalRatings', false)
			->from('ratings')
			->join('beers', 'beers.id = ratings.beerID')
			->join('establishment', 'establishment.id = beers.establishmentID')
			->join('styles', 'beers.styleID = styles.id')
			->join('package', 'package.id = ratings.packageID')
			->join('state', 'state.id = establishment.stateID')
			->join('users', 'users.id = ratings.userID');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	$row = $rs->row();
        	return $row->totalRatings;
		}
		return 0;
	}
	
	public function getAllPagination($limit)
	{
		//CASE WHEN aroma = 0 THEN r.rating ELSE ((aroma * (' . PERCENT_AROMA . ' / 100)) + (taste * (' . PERCENT_TASTE . ' / 100)) + (look * (' . PERCENT_LOOK . ' / 100)) + (drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) END AS rating

		$query = '
			SELECT
				r.id
				, DATE_FORMAT(r.dateTasted, "%W, %M %D, %Y") AS dateTasted
				, DATE_FORMAT(r.dateAdded, "%W, %M %D, %Y at %T") AS dateAdded
				, r.color
				, (aroma * (' . PERCENT_AROMA . ' / 100)) + (taste * (' . PERCENT_TASTE . ' / 100)) + (look * (' . PERCENT_LOOK . ' / 100)) + (drinkability * (' . PERCENT_DRINKABILITY . ' / 100)) AS rating
				, r.comments
				, r.haveAnother
				, r.price
				, r.shortrating
				, r.aroma
				, r.taste
				, r.look
				, r.drinkability
				, be.id AS beerID
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
				, be.slug AS slug_beer
				, be.seasonal
				, be.seasonalPeriod
				, st.id AS styleID
				, st.style
				, p.package
				, e.id AS establishmentID
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.url
				, e.slug as slug_establishment
				, s.stateAbbr
				, u.id AS userID
				, u.username
				, u.firstname
				, u.lastname
			FROM ratings r
			INNER JOIN beers be ON be.id = r.beerID
			INNER JOIN establishment e ON e.id = be.establishmentID
			INNER JOIN styles st ON be.styleID = st.id
			INNER JOIN package p ON p.id = r.packageID
			INNER JOIN state s ON s.id = e.stateID
			INNER JOIN users u ON u.id = r.userID
			WHERE
				r.shortrating <> 1
			ORDER BY
				r.dateAdded DESC
				, be.beerName ASC
			LIMIT ' . $limit . ', ' . BEER_REVIEWS
		;
	
		$rs = $this->db->query($query);
		if ($rs->num_rows() > 0)
		{
			return $rs->result();
		}
		return [];

		$this->db
			->select(
				'ratings.id,
				DATE_FORMAT(ratings.dateTasted, "%W, %M %D, %Y") AS dateTasted,
				DATE_FORMAT(ratings.dateAdded, "%W, %M %D, %Y at %T") AS dateAdded,
				ratings.color,
				(ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100)) AS rating,
				ratings.comments,
				ratings.haveAnother,
				ratings.price,
				ratings.shortrating,
				ratings.aroma,
				ratings.taste,
				ratings.look,
				ratings.drinkability,
				beers.id AS beerID,
				beers.beerName,
				beers.alcoholContent,
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
				beers.slug AS slug_beer,
				styles.id AS styleID,
				styles.style,
				package.package,
				establishment.id AS establishmentID,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug as slug_establishment,
				state.stateAbbr,
				users.id AS userID,
				users.username,
				users.firstname,
				users.lastname', false
			)
			->from('ratings')
			->join('beers', 'beers.id = ratings.beerID', 'inner')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'beers.styleID = styles.id', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->order_by('ratings.dateAdded', 'desc')
			->order_by('beers.beerName', 'asc')
			->limit($limit, BEER_REVIEWS);

		$rs = $this->db->get();echo '<pre>' . print_r($this->db->last_query(), true) . '</pre>'; 
		echo $rs->num_rows(); exit;
        if ($rs->num_rows() > 0)
        {
        	return $rs->result();
		}
		return [];
	}
	
	public function getNonRatedBeersForDropDown() {
		/*$query = '
			SELECT	
				be.id AS id
				, CONCAT(be.beerName, " - ", b.name) AS name
			FROM beers be
			INNER JOIN breweries b ON b.id = be.breweryID
			INNER JOIN ratings r ON r.beerID = be.id
			ORDER BY 
				be.beerName ASC
		';*/
		
		$query = '
			SELECT	
				be.id AS id
				, CONCAT(be.beerName, " - ", e.name) AS name
			FROM beers be
			INNER JOIN establishment e ON e.id = be.establishmentID
			ORDER BY 
				be.beerName ASC
		';
		
		/*$query = '
			SELECT	
				be.id AS id
				, CONCAT(be.beerName, " - ", b.name) AS name
			FROM beers be
			INNER JOIN breweries b ON b.id = be.breweryID
			INNER JOIN ratings r ON r.beerID = be.id
			INNER JOIN users u ON u.id = r.userID			
			WHERE
				u.id <> ' . $this->session->userdata('id') . '
			ORDER BY 
				be.beerName ASC
		';*/
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function getRatingsByUserID($id) {
		$query = '
			SELECT
				r.id
				, DATE_FORMAT(r.dateTasted, "%W, %M %D, %Y") AS dateTasted
				, r.color
				, r.rating
				, r.comments
				, r.haveAnother
				, r.price
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
				, st.style
				, p.package
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.url
				, s.stateAbbr
			FROM ratings r
			INNER JOIN beers be ON be.id = r.beerID
			INNER JOIN establishment e ON e.id = be.establishmentID
			INNER JOIN styles st ON be.styleID = st.id
			INNER JOIN package p ON p.id = r.packageID
			INNER JOIN state s ON s.id = e.stateID
			WHERE
				r.userID = ' . $id . '
				AND r.active = "1"
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

	public function getRatingsByUserIDForUserProfileCount($id) {
		$this->db
        	->select('COUNT(ratings.id) AS total_beers')
        	->from('ratings')
        	->join('beers', 'beers.id = ratings.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->join('styles', 'styles.id = beers.styleID', 'inner')
        	->where('ratings.userID', $id)
        	->where('ratings.active', '1');

        $rs = $this->db->get();
        $row = $rs->row();
        return $row->total_beers;
	}
        
    public function getRatingsByUserIDForUserProfile($id, $offset, $for_style = false) {
    	$this->db
    		->select(
    			'ratings.id,
                ratings.dateTasted,
                ratings.haveAnother,
                ratings.price,
                (ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100)) AS rating,
                beers.id AS beer_id,
                beers.beerName,
                beers.alcoholContent,
                beers.ibu,
                beers.slug AS slug_beer,
                styles.id AS style_id,
                styles.style,
                establishment.id AS establishment_id,
                establishment.name,
                establishment.slug AS slug_establishment',
                false
    		)
    		->from('ratings')
        	->join('beers', 'beers.id = ratings.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->join('styles', 'styles.id = beers.styleID', 'inner')
        	->where('ratings.userID', $id)
        	->where('ratings.active', '1')
			->order_by('beers.beerName', 'asc')
			->limit(USER_BEER_REVIEWS_PAGINATION, $offset);    

        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
        }
		return [];
    }

    public function getStylesRatedByUserIDForUserProfileCount($id) {
		$this->db
        	->select('COUNT(DISTINCT styles.id) AS total_beers')
        	->from('ratings')
        	->join('beers', 'beers.id = ratings.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->join('styles', 'styles.id = beers.styleID', 'inner')
        	->where('ratings.userID', $id)
        	->where('ratings.active', '1');

        $rs = $this->db->get();
        $row = $rs->row();
        return $row->total_beers;
	}
        
    public function getStylesRatedByUserIDForUserProfile($id) {
    	$this->db
    		->select(
    			'DISTINCT styles.id,
                styles.style,
                styles.origin,
                styles.styleType,
                COUNT(styles.id) AS style_count',
                false
            )
    		->from('ratings')
        	->join('beers', 'beers.id = ratings.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->join('styles', 'styles.id = beers.styleID', 'inner')
        	->where('ratings.userID', $id)
        	->where('ratings.active', '1')
        	->group_by('styles.id')
			->order_by('styles.styleType', 'asc')
            ->order_by('styles.origin', 'asc')
            ->order_by('styles.style', 'asc');      

        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
        }
		return [];
    }

    public function getBeerByStylesRatedByUserIDForUserProfile($user_id, $style_id) {
    	$this->db
    		->select(
    			'ratings.id,
                ratings.dateTasted,
                ratings.comments,
                ROUND((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100)), 1) AS rating,
                beers.id AS beer_id,
                beers.beerName,
                beers.alcoholContent,
                beers.picture,
                beers.ibu,
                beers.slug AS slug_beer,
                styles.id AS style_id,
                styles.style,
                establishment.id AS establishment_id,
                establishment.name,
                establishment.slug AS slug_establishment',
                false
    		)
    		->from('ratings')
    		->join('beers', 'beers.id = ratings.beerID', 'inner')
        	->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
        	->join('styles', 'styles.id = beers.styleID', 'inner')
    		->where('ratings.userID', $user_id)
        	->where('ratings.active', '1')
        	->where('styles.id', $style_id)
        	->order_by('beers.beerName', 'asc');

        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
        }
		return [];
    }
    
    public function getBeerRatingByUserIDStatistics($id) {
    	/*$this->db
    		->select(

    		)*/
        $query = '
            SELECT
                COUNT(users.id) AS rated_beers,
                AVG((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS rated_beer_average,
                COUNT(DISTINCT styles.id) AS rated_styles,
                MAX((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS rated_beer_max,
                MIN((ratings.aroma * (' . PERCENT_AROMA . ' / 100)) + (ratings.taste * (' . PERCENT_TASTE . ' / 100)) + (ratings.look * (' . PERCENT_LOOK . ' / 100)) + (ratings.drinkability * (' . PERCENT_DRINKABILITY . ' / 100))) AS rated_beer_min
            FROM users
            LEFT OUTER JOIN ratings
                ON ratings.userID = users.id
            INNER JOIN beers
                ON beers.id = ratings.beerID
            INNER JOIN styles
                ON styles.id = beers.styleID
            WHERE
                users.id = ' . $id . '
                AND users.active = "1"
                AND users.banned = "0"
            GROUP BY
            	users.id
            HAVING
            	COUNT(ratings.id) > 0
        ';
        
        $rs = $this->db->query($query);
        $array = array();
        if($rs->num_rows() > 0) {
            $array = $rs->result_array();
        }
        return $array;    
    }

    public function getEstablishmentRatingByUserIDStatisticsCount($id) {
    	$this->db
    		->select(
    			'COUNT(establishment.id) AS rated_establishments',
                false
    		)
    		->from('users')
    		->join('rating_establishment', 'rating_establishment.userID = users.id', 'left outer')
    		->join('establishment', 'establishment.id = rating_establishment.establishmentID', 'inner')
    		->join('establishment_categories', 'establishment_categories.id = establishment.categoryID', 'inner')
    		->where('users.id', $id)
    		->where('users.active', '1')
    		->where('users.banned', '0');

    	$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	$row = $rs->row();
        	return $row->rated_establishments;
        }
		return 0;
    }
    
    public function getEstablishmentRatingByUserIDStatistics($id, $return = 'array') {
    	$this->db
    		->select(
    			'establishment.categoryID,
    			COUNT(users.id) AS rated_establishments,
                AVG((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) AS rated_establishment_average,
                establishment_categories.name,
                MAX((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) AS rated_establishment_max,
                MIN((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100))) AS rated_establishment_min',
                false
    		)
    		->from('users')
    		->join('rating_establishment', 'rating_establishment.userID = users.id', 'left outer')
    		->join('establishment', 'establishment.id = rating_establishment.establishmentID', 'inner')
    		->join('establishment_categories', 'establishment_categories.id = establishment.categoryID', 'inner')
    		->where('users.id', $id)
    		->where('users.active', '1')
    		->where('users.banned', '0')
    		->group_by('establishment_categories.name')
    		->order_by('rated_establishments', 'desc');

    	$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	if ('obj' == $return) {
        		return $rs->result();
        	}
        	else {
        		return $rs->result_array();
        	}
        }
		return [];
    }

	public function getEstablishmentRatingsByUserIDAndCategoryID($user_id, $category_id) {
		$this->db
			->select('
				rating_establishment.id,
				rating_establishment.establishmentID,
				rating_establishment.userID,
				rating_establishment.dateVisited,
				rating_establishment.visitAgain,
				rating_establishment.price,
				rating_establishment.drink,
				rating_establishment.service,
				rating_establishment.atmosphere,
				rating_establishment.pricing,
				rating_establishment.accessibility,
				rating_establishment.comments,
				ROUND((rating_establishment.drink * (' . PERCENT_DRINK . ' / 100)) + (rating_establishment.service * (' . PERCENT_SERVICE . ' / 100)) + (rating_establishment.atmosphere * (' . PERCENT_ATMOSPHERE . ' / 100)) + (rating_establishment.pricing * (' . PERCENT_PRICING . ' / 100)) + (rating_establishment.accessibility * (' . PERCENT_ACCESSIBILITY . ' / 100)), 1) AS establishment_rating,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.stateFull,
				state.stateAbbr',
				false
			)
			->from('rating_establishment')
			->join('establishment', 'establishment.id = rating_establishment.establishmentID', 'inner')
			->join('users', 'users.id = rating_establishment.userID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'left outer')
			->where('rating_establishment.userID', $user_id)
			->where('establishment.categoryID', $category_id)
			->where('rating_establishment.active', '1')
			->where('users.active', '1')
    		->where('users.banned', '0')
    		->order_by('establishment.name', 'asc');

		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
        	return $rs->result();
        }
		return [];
	}
	
	public function getRatingByID($ratingID, $return = 'obj') {
		$this->db
			->select(
				'ratings.id,
				DATE_FORMAT(ratings.dateTasted, "%W, %M %D, %Y") AS dateTasted,
				ratings.dateTasted AS mdate,
				ratings.color,
				ratings.rating,
				ratings.comments,
				ratings.haveAnother,
				ratings.price,
				ratings.packageID,
				ratings.shortrating,
				ratings.aroma,
				ratings.taste,
				ratings.look,
				ratings.drinkability,
				beers.beerName,
				beers.alcoholContent,
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
				beers.slug AS slug_beer,
				styles.style,
				package.package,
				establishment.name,
				establishment.address,
				establishment.city,
				establishment.zip,
				establishment.phone,
				establishment.url,
				establishment.slug AS slug_establishment,
				state.stateAbbr,
				users.firstname,
				users.lastname',
				false
			)
			->from('ratings')
			->join('beers', 'beers.id = ratings.beerID', 'inner')
			->join('establishment', 'establishment.id = beers.establishmentID', 'inner')
			->join('styles', 'styles.id = beers.styleID', 'inner')
			->join('package', 'package.id = ratings.packageID', 'inner')
			->join('state', 'state.id = establishment.stateID', 'inner')
			->join('users', 'users.id = ratings.userID', 'inner')
			->where('ratings.id', $ratingID)
			->where('ratings.active', '1');

		$rs = $this->db->get();
        if ($rs->num_rows() == 1) {
        	if ('obj' == $return) {
        		return $rs->row();
        	}
        	else {
        		return $rs->row_array();
        	}
        }
		return [];
	}
	
	public function getRatingsByUserIDEstablishmentID($userID, $establishmentID, $beerID = '') {
		$query = '
			SELECT
				r.id				
		';
		if(!empty($beerID)) {
			$query .= '
				, DATE_FORMAT(r.dateTasted, "%W, %M %D, %Y") AS dateTasted
				, r.color
				, r.rating
				, r.comments
				, r.haveAnother
				, r.price
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
				, st.style
				, p.package
			';
		}
		$query .= '				
				, e.name
				, e.address
				, e.city
				, e.zip
				, e.phone
				, e.url
				, s.stateAbbr
			FROM ratings r
		';
		if(!empty($beerID)) {
			$query .= '
			INNER JOIN beers be ON be.id = r.beerID
			INNER JOIN styles st ON be.styleID = st.id
			INNER JOIN package p ON p.id = r.packageID
			';
		}
		$query .= '
			INNER JOIN establishment e ON e.id = r.establishmentID			
			INNER JOIN state s ON s.id = e.stateID
			WHERE
				r.userID = ' . $userID . '
				AND r.active = "1"
				AND r.establishmentID = ' . $establishmentID
		;
		
		$rs = $this->db->query($query);
		$array = array();
		if($rs->num_rows() > 0) {
			$array = $rs->result_array();
		}
		return $array;
	}
	
	public function checkForRatingByUserIDBeerID($userID, $beerID) {
		$this->db
			->select(
				'id,
				dateTasted,
				color,
				rating,
				comments,
				haveAnother,
				price,
				packageID,
				shortrating,
				aroma,
				taste,
				look,
				drinkability'
			)
			->from('ratings')
			->where('userID', $userID)
			->where('beerID', $beerID);

		$rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
        	return $rs->row();
        }
		return [];
	}
	
	public function updateRatingByID($data) {
		/*$query = '
			UPDATE ratings SET
				packageID = ' . mysqli_real_escape_string($this->db->conn_id, $data['packageID']) . '
				, dateTasted = "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, dateEdit = NOW()
				, color = "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['color'])) . '"
				, rating = ' . mysqli_real_escape_string($this->db->conn_id, $data['rating']) . '
				, comments = "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"	
				, haveAnother = "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"			
				, price = ' . mysqli_real_escape_string($this->db->conn_id, $data['price']) . '
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';*/
		$query = '
			UPDATE ratings SET
				packageID = ' . mysqli_real_escape_string($this->db->conn_id, $data['packageID']) . '
				, dateTasted = "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, dateEdit = NOW()
				, color = "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['color'])) . '"
				, aroma = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['aroma'])) . '
				, taste = ' . mysqli_real_escape_string($this->db->conn_id, $data['taste']) . '
				, look = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['look'])) . '	
				, drinkability = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['drinkability'])) . '
				, comments = "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"	
				, haveAnother = "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"			
				, price = ' . mysqli_real_escape_string($this->db->conn_id, $data['price']) . '
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';
		$rs = $this->db->query($query);
	}
	
	public function updateShortRatingByID($data) {
		$query = '
			UPDATE ratings SET
				dateTasted = "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, dateEdit = NOW()
				, aroma = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['aroma'])) . '
				, taste = ' . mysqli_real_escape_string($this->db->conn_id, $data['taste']) . '
				, look = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['look'])) . '	
				, drinkability = ' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['drinkability'])) . '	
				, haveAnother = "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"			
			WHERE
				id = ' . $data['id'] . '
			LIMIT 1
		';
		$rs = $this->db->query($query);
	}
	
	/**
	 * Stores the data for a new Rating
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createRating($data) {
		// create the query for creating a new record
		/*$query = '
			INSERT INTO ratings (
				id
				, establishmentID
				, beerID
				, userID
				, packageID
				, dateTasted
				, dateAdded
				, color
				, rating
				, comments
				, haveAnother
				, price
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['beerID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['userID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['packageID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, NOW()
				, "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['color'])) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['rating']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['price']) . '
				, "1"
			)
		';*/
		$query = '
			INSERT INTO ratings (
				id
				, establishmentID
				, beerID
				, userID
				, packageID
				, dateTasted
				, dateAdded
				, color
				, shortRating
				, aroma
				, taste
				, look
				, drinkability
				, comments
				, haveAnother
				, price
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['beerID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['userID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['packageID']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, NOW()
				, "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['color'])) . '"
				, "0"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['aroma']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['taste']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['look']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['drinkability']) . '
				, "' . mysqli_real_escape_string($this->db->conn_id, htmlentities($data['comments'])) . '"
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['price']) . '
				, "1"
			)
		';
		// run the query
		$this->db->query($query);
		// return the id of the data that was just inserted
		return $this->db->conn_id->insert_id;
	}
	
	/**
	 * Stores the data for a new Rating
	 *
	 * @param array $data
	 * @return integer
	 */
	public function createShortRating($data) {
		// create the query for creating a new record
		$query = '
			INSERT INTO ratings (
				id
				, establishmentID
				, beerID
				, userID
				, packageID
				, dateTasted
				, dateAdded
				, haveAnother
				, shortRating
				, aroma
				, taste
				, look
				, drinkability
				, active
			) VALUES (
				NULL
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['establishmentID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['beerID']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['userID']) . '
				, 1
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['dateTasted']) . '"
				, NOW()
				, "' . mysqli_real_escape_string($this->db->conn_id, $data['haveAnother']) . '"
				, "1"
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['aroma']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['taste']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['look']) . '
				, ' . mysqli_real_escape_string($this->db->conn_id, $data['drinkability']) . '
				, "1"
			)
		';
		// run the query
		$this->db->query($query);
		// return the id of the data that was just inserted
		return $this->db->conn_id->insert_id;
	}
}