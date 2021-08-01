<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('M_Impression');
    }

	public function index() {
        $result["impressions"] = $this->M_Impression->retrieveImpression();

        $result["guestName"] = "<i>Nama Tamu</i>";
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

    public function test() {
        $this->load->view('dashboard/example');
    }
}
