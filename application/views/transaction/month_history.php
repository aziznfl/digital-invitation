<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Transaction History</h1>
</section>

<!-- Main content -->
<section class="content">
	<div class="row">
		<div class="col-lg-2 col-md-12">
			<div class="box box-success">
				<div class="box-body">
					<div class="form-group">
						<label>Select Month:</label>
						<select id="date" class="form-control" onchange="changeDate()">
						<?php
							$nowDate = $first_transaction[0]->transaction_date;
							$nowDates = explode("-", $nowDate);
							$yearFirst = $nowDates[0];
							$monthFirst = $nowDates[1];
							$yearNow = date('Y');
							$monthNow = date('m');

							for ($i = (int)$yearFirst; $i <= (int)$yearNow; $i++) {
								$month = $i == $yearFirst ? $monthFirst : '01';
								$year = $i < $yearNow ? '12' : $monthNow;
								for ($j = (int)$month; $j <= (int)$year; $j++) {
									$time = strtotime($j.'/01/'.$i);
									$date = date("M Y", $time);
									echo '<option value="'.$i.'-'.$j.'">'.$date.'</option>';
								}
							}

							$timeTransaction = strtotime($monthFirst."/01/".$yearFirst);
						?>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-10 col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Detail Transaction</h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<div class="title">
								<h4>Top Transaction</h4>
								<div class="floating-rounded-default bg-primary">Total: <span id="top-floating-amount-table">-</span></div>
							</div>
							<div class="table-responsive">
								<table id="datatable-top-transaction" class="table table-bordered table-striped table-hover" style="cursor: pointer">
									<thead>
										<th class="text-center">No</th>
										<th class="text-center">Category</th>
										<th class="text-center">%</th>
									</thead>
								</table>
							</div>
						</div>
						<div class="col-md-8">
							<h4>List Transaction</h4>
							<div class="table-responsive">
								<table id="datatable-month-transaction" class="table table-bordered table-striped table-hover">
									<thead>
										<th class="text-center" width="10">No</th>
										<th class="text-center" width="200">Date</th>
										<th class="text-center">Rp</th>
										<th class="text-center">Category</th>
										<th class="text-center">Desc</th>
										<th class="text-center"></th>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /.content -->
