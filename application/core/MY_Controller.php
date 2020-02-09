<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();

		// load model
		$this->load->model('CoreModel');
		
		$this->setupMenus();
	}

	function setupMenus() {
		$GLOBALS['menus'] = $this->CoreModel->getMenus();
	}

	function listCategories() {
		$result = $this->M_Transaction->getCategories()->result_array();
		$all = array();
		foreach ($result as $cat) {
			if ($cat["parent_id"] == 1) {
				$cat["child"] = array();
				$all[$cat["category_id"]] = $cat;
			} else {
				array_push($all[$cat["parent_id"]]["child"], $cat);
			}
		}
		$all = array_values($all);
		return $all;
	}

	function listCategoriesInvestment() {
		return $this->M_Transaction->getCategoriesInvestment()->result_array();
	}

	function loginUser($user) {
		$email = $user["email"];
		$user = $this->M_User->login($email)->result();
		return $user;
	}

	//-------- Transaction ---------//

	function addNewTransaction($data) {
		$result = $this->M_Transaction->addData("transaction", $data);
		header("location:".base_url());
	}

	function updateTransaction($data, $where) {
		$result = $this->M_Transaction->updateData("transaction", $data, $where);
		header("location:".base_url());
	}

	function transaction($transaction_id) {
		$result = $this->M_Transaction->getTransaction($transaction_id)->result_array();
		$result = $result[0];
		$result["amount"] = (int)$result["amount"];
		return $result;
	}

	function lastTransaction($limit) {
		$result = $this->M_Transaction->getAllTransaction($limit)->result_array();
		$all = array();
		foreach ($result as $transaction) {
			$transaction["amount"] = (int)$transaction["amount"];
			$transaction["amount_text"] = number_format($transaction["amount"]);
			$transaction["category_name"] = ucwords($transaction["category_name"]);
			array_push($all, $transaction);
		}
		return $all;
	}

	function monthTransaction($month, $year) {
		$result = $this->M_Transaction->getMonthTransaction($month, $year)->result_array();
		$all = array();
		foreach ($result as $transaction) {
			$transaction["amount"] = (int)$transaction["amount"];
			$transaction["amount_text"] = number_format($transaction["amount"]);
			$transaction["category_name"] = ucwords($transaction["category_name"]);
			array_push($all, $transaction);
		}
		return $all;
	}

	function topTransaction($month, $year) {
		$result = $this->M_Transaction->getTopTransaction($month, $year)->result_array();
		$all = array();
		foreach ($result as $transaction) {
			$transaction["category_id"] = (int)$transaction["category_id"];
			$transaction["category_name"] = ucwords($transaction["category_name"]);
			$transaction["total"] = (int)$transaction["total"];
			$transaction["total_text"] = number_format($transaction["total"]);
			$transaction["percentage"] = number_format($transaction["percentage"], 2)."%";
			array_push($all, $transaction);
		}
		return $all;
	}

	//-------- Investment --------//

	function addNewInvestment($data) {
		$result = $this->M_Transaction->addData("transaction_investment", $data);
		header("location:".base_url());
	}

	function totalInvestment() {
		$investment = $this->M_Transaction->getTotalInvestment()->result();
		$investment = $investment[0]->total_investment;
		return $investment;
	}

	function listInvestment() {
		$result = $this->M_Transaction->getInvestment()->result_array();
		$portfolios = array();
		foreach ($result as $portfolio) {
			$portfolio["amount_text"] = number_format($portfolio["amount"]);

			$arr = array();
			$arr["date"] = $portfolio["transaction_date"];
			$arr["amount"] = (int)$portfolio["amount"];
			$arr["amount_text"] = number_format($arr["amount"]);
			$arr["value"] = (float)$portfolio["value"];
			if ($portfolio["unit"] != "") $arr["value_text"] = $portfolio["value"] ." ". $portfolio["unit"];
			else $arr["value_text"] = null;
			$arr["state_text"] = !$portfolio["is_done"] ? "Progress" : "Done";
			$arr["description"] = $portfolio["description"];
			$arr["manager"] = $portfolio["manager"];
			$arr["child"] = array($portfolio);
			if (array_key_exists($portfolio["description"], $portfolios)) {
				$amount = $portfolios[$portfolio["description"]]["amount"];
				$amount += $portfolio["is_done"] == 0 ? $portfolio["amount"] : -$portfolio["amount"];
				$portfolios[$portfolio["description"]]["amount"] = (int)$amount;

				$portfolios[$portfolio["description"]]["value"] += (float)$portfolio["value"];
				if ($portfolio["unit"] != "") {
					$portfolios[$portfolio["description"]]["value_text"] = $portfolios[$portfolio["description"]]["value"] ." ". $portfolio["unit"];
				}

				if ($portfolio["is_done"]) {
					$amount *= -1;
				}
				$portfolios[$portfolio["description"]]["amount_text"] = number_format($amount);
				array_push($portfolios[$portfolio["description"]]["child"], $portfolio);
			} else {
				$arr["amount_text"] = number_format($arr["amount"]);
				$portfolios[$portfolio["description"]] = $arr;
			}
		}
		$portfolios = array_values($portfolios);
		return $portfolios;
	}
}
