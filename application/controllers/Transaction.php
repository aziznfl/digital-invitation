<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends MY_Controller {

    function __construct() {
        parent::__construct();

		if ($this->session->userdata('user') == '') {
			header("location: ".base_url('account'));
		}

		$this->load->model('M_Transaction');
    }

	function history($year = "", $month = "") {
		if ($year == "") $year = date('Y');
		if ($month == "") $month = date('n');
		$result["first_transaction"] = $this->getFirstTransaction()->result();

		$result["add_footer"] = "
			<script>
				var table;

				$(function() {
					$('#date').val('".$year."-".$month."');
					changeDate();
				});

				function changeDate() {
					$('#load_transactions_month').html('loading...');
					// $('#load_transactions_top').html('loading...');

					var date = $('#date').val().split('-');
					var params = date[0] + '/' + date[1];
					var site_url = '".base_url('transaction/viewGetMonthTransaction/')."' + params;
					$('#load_transactions_month').load(site_url, function() {});
					site_url = '".base_url('transaction/viewGetTopTransaction/')."' + params;
					reloadTopTransaction(date[1], date[0]);
					window.history.pushState('object or string', 'Title', '".base_url('transaction/history/')."' + params);
				}

				function reloadTopTransaction(month, year) {
					var link = '".base_url()."'+'settings/getTopTransaction/'+year+'/'+month;
					table = $('#datatable-top-transaction').DataTable({
						'searching': false,
						'paging': false,
						'destroy': true,
						'ajax': link,
						'columns': [
							{'data': null, 'defaultContent': '', 'className': 'text-center', 'orderable': false, 'target': 0},
							{'data': 'category_name', 'className': 'text-center'},
							{'data': 'percentage', 'className': 'text-right'}
						],
						'order': [2, 'desc']
					});

					table.on('order.dt search.dt', function() {
				        table.column(0).nodes().each( function (cell, i) {
				            cell.innerHTML = i+1;
				        } );
				    }).draw();
				}
			</script>
		";

		$this->load->view('root/_header', $result);
		$this->load->view('root/_menus');
		$this->load->view('transaction/month_history');
		$this->load->view('root/_footer');
	}

	function manage() {
		$result["categories"] = $this->getCategories();
		$result["add_footer"] = "
			<script>
				$(function () {
					$('input[name=date]').focus();
				})
			</script>
		";

		$this->load->view('root/_header', $result);
		$this->load->view('root/_menus');
		$this->load->view('transaction/manage');
		$this->load->view('root/_footer');
	}

	//------ AJAX ------//

	function viewGetMonthTransaction($year = "", $month = "") {
		if ($year == "") $year = date('Y');
		if ($month == "") $month = date('n');
		$result["transactions"] = $this->monthTransaction($month, $year);
		echo $this->load->view('transaction/ajax/list_month_transaction', $result, TRUE);
	}

	function viewGetTopTransaction($year = "", $month = "") {
		if ($year == "") $year = date('Y');
		if ($month == "") $month = date('n');
		$result["transactions"] = $this->topTransaction($month, $year);
		echo $this->load->view('transaction/ajax/list_top_transaction', $result, TRUE);
	}

	//----- My Function -------//

	// function getMonthTransaction($year = "YEAR(CURRENT_DATE())", $month = "MONTH(CURRENT_DATE())") {
	// 	$list = $this->M_Transaction->getMonthTransaction($month, $year)->result();
	// 	print_r(json_encode($list));
	// }

	function changeNullChar($result) {
		foreach ($result->result() as $key => $value) {
			$val = array_map(function($val) {
				return is_null($val) ? "-" : $val;
			}, $value);
			$result[$key] = $val;
		}
		return $result;
	}

	//------ GET -------//

	function getFirstTransaction() {
		return $this->M_Transaction->getFirstTransaction();
	}
}
?>