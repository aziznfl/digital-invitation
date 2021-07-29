<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('M_Impression');
    }

	public function index() {
        $result["impressions"] = $this->M_Impression->retrieveImpression();

		$this->load->view('dashboard/view', $result);
	}

    public function impression() {
        $data["name"] = $this->input->get("name");
        $data["messages"] = $this->input->get("messages");

        $result = $this->M_Impression->insertMessages($data);
        echo $result;
    }
}
