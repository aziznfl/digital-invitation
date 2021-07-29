<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Impression extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function retrieveImpression() {
        $query = "
            SELECT * FROM t_impression
            WHERE isDeleted = 0
        ";

        return $this->db->query($query)->result();
    }

    function insertMessages($data) {
        $this->db->insert("t_impression", $data);
        return $this->db->affected_rows();
    }
}
