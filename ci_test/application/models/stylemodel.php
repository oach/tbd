<?php
if(!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StyleModel extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function getAll() {
        $query = '
            SELECT
                id
                , style
                , description
            FROM styles
            ORDER BY
                style ASC
        ';
        
        $rs = $this->db->query($query);
        $array = array();
        if($rs->num_rows() > 0) {
            $array = $rs->result_array();
        }
        return $array;
    }

    public function getStyleByIDAllInfo($style_id) {
        $this->db
            ->select('*')
            ->from('styles')
            ->where('id', $style_id);

        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
            return $rs->row();
        }
        return [];
    }
    
    public function getAllForDropDown() {
        $this->db
            ->select('id, style AS name, origin, styleType')
            ->from('styles')
            ->order_by('styleType', 'asc')
            ->order_by('origin', 'asc')
            ->order_by('style', 'asc');

        $rs = $this->db->get();
        if ($rs->num_rows() > 0)
        {
            return $rs->result_array();
        }
        return [];
    }
    
    public function getStyleByID($styleID) {
        $this->db
            ->select('id, style AS name', false)
            ->from('styles')
            ->where('id', $styleID);

        $rs = $this->db->get();
        if ($rs->num_rows() == 1)
        {
            return $rs->row();
        }
        return [];
    }
    
    public function getStylesByUserIDForUserProfile($id, $offset) {
        // temporary holder for results
        $array = '';

        // create the query
        $query = '
            SELECT
                COUNT(DISTINCT styles.id) AS rated_styles
            FROM users
            LEFT OUTER JOIN ratings
                ON ratings.userID = users.id
            INNER JOIN beers
                ON beers.id = ratings.beerID
            INNER JOIN styles
                ON styles.id = beers.styleID
            WHERE
                users.id = ' . mysqli_real_escape_string($this->db->conn_id, $id) . '
                AND users.active = "1"
                AND users.banned = "0"
                AND ratings.active = "1"
        ';        
        // get the record set
        $rs = $this->db->query($query);
        // get the number of rows
        $row = $rs->row_array();
        $total = $row['rated_styles'];
        
        echo $total; exit;
    }
}
?>