<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();
    }

	public function index() {
        $result["impressions"] = [];

        $result["guestName"] = "";
        if (($this->input->get("u")) != null) {
            $result["guestName"] = $this->input->get("u");
        }

		$this->load->view('dashboard/_header', $result);
		$this->load->view('dashboard/view');
		$this->load->view('dashboard/_footer');
	}

    public function impression() {
        $data["name"] = $this->input->get("name");
        $data["messages"] = $this->input->get("messages");

        $result = $this->M_Impression->insertMessages($data);
        echo $result;
    }

    public function retrieveImpression() {
        $result = $this->M_Impression->retrieveImpression();

        $listImpression = array();
        foreach($result as $impression) {
            $data["name"] = $impression->name;
            $data["messages"] = $impression->messages;
			array_push($listImpression, $data);
        }
        echo json_encode(array("data" => $listImpression));
    }

    public function test() {
        $this->load->view('dashboard/example');
    }
}
