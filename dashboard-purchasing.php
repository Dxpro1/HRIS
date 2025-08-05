<?php
	require('session.php');
	require('config/config.php');
	require('classes/api.php');
    
	$api = new Api;
	$page_title = 'Purchasing Dashboard';

	# Check role permission
	$page_access = $api->check_role_permissions($username, 136); // Assuming 136 is general PO access

	if($page_access == 0){
		header('location: 404-page.php');
	}
?>
        <?php
            require('views/_head.php');
        ?>
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
         <link href="assets/css/dashboard.css"  rel="stylesheet" type="text/css" />
 
    <body data-sidebar="dark">
    <?php require('views/_preloader.php'); ?>

    <div id="layout-wrapper">
        <?php require('views/_nav_header.php'); ?>
        <?php require('views/_menu.php'); ?>
        
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Purchasing Dashboard</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Purchasing</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-0" id="total-pos-count">--</h4>
                                            <p class="text-muted mb-0">Total Purchase Orders</p>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm rounded-circle bg-primary-lighten text-primary text-center">
                                            <i class="mdi mdi-file-document-multiple font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-0" id="total-po-amount">--</h4>
                                            <p class="text-muted mb-0">Total PO Amount</p>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm rounded-circle bg-success-lighten text-success text-center">
                                            <i class="mdi mdi-currency-php font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-0" id="pending-pos-count">--</h4>
                                            <p class="text-muted mb-0">Pending POs</p>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm rounded-circle bg-warning-lighten text-warning text-center">
                                            <i class="mdi mdi-timer-sand font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-0" id="approved-pos-count">--</h4>
                                            <p class="text-muted mb-0">Approved POs</p>
                                        </div>
                                        <div class="flex-shrink-0 avatar-sm rounded-circle bg-info-lighten text-info text-center">
                                            <i class="mdi mdi-check-decagram font-size-24"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Monthly Purchase Orders (Last 12 Months)</h4>
                                    <canvas id="monthlyPurchaseOrdersChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Top 5 Vendors by Spend</h4>
                                    <canvas id="topVendorsChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title mb-4">Vendor Type Distribution</h4>
                                    <canvas id="vendorTypeDistributionChart" height="180"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php require('views/_footer.php'); ?>
        </div>
    </div>

    <?php require('views/_scripts.php'); ?>
    <!-- Chart JS -->
    <script src="assets/libs/chartjs/chart.js"></script>

    <script>
        $(document).ready(function() {
            const username = $('#username').text();

            // Function to format currency
            function formatCurrency(value) {
                return '₱' + parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Fetch and display PO Summary
            function loadPOSummary() {
                $.ajax({
                    url: 'controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { transaction: 'get purchase order summary', username: username },
                    success: function(data) {
                        if(data) {
                            $('#total-pos-count').text(data.total_pos || 0);
                            $('#total-po-amount').text(formatCurrency(data.total_amount || 0));
                            $('#pending-pos-count').text(data.pending_pos || 0);
                            $('#approved-pos-count').text(data.approved_pos || 0);
                        }
                    }
                });
            }

            // Fetch and render Monthly PO Chart
            function loadMonthlyPOChart() {
                $.ajax({
                    url: 'controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { transaction: 'get monthly purchase orders', username: username },
                    success: function(data) {
                        if(data && data.length > 0) {
                            const labels = data.map(item => new Date(item.month + '-02').toLocaleString('default', { month: 'short', year: '2-digit' }));
                            const amounts = data.map(item => item.total_amount);
                            const counts = data.map(item => item.po_count);

                            const ctx = document.getElementById('monthlyPurchaseOrdersChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Total Amount',
                                        data: amounts,
                                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                                        borderColor: 'rgba(52, 152, 219, 1)',
                                        borderWidth: 1,
                                        yAxisID: 'y-axis-amount'
                                    }, {
                                        label: 'PO Count',
                                        data: counts,
                                        backgroundColor: 'rgba(231, 76, 60, 0.7)',
                                        borderColor: 'rgba(231, 76, 60, 1)',
                                        borderWidth: 1,
                                        yAxisID: 'y-axis-count'
                                    }]
                                },
                                options: {
                                    scales: {
                                        yAxes: [{
                                            id: 'y-axis-amount',
                                            type: 'linear',
                                            position: 'left',
                                            ticks: {
                                                beginAtZero: true,
                                                callback: function(value) { return '₱' + value.toLocaleString(); }
                                            }
                                        }, {
                                            id: 'y-axis-count',
                                            type: 'linear',
                                            position: 'right',
                                            ticks: {
                                                beginAtZero: true,
                                                stepSize: 1
                                            },
                                            gridLines: {
                                                drawOnChartArea: false
                                            }
                                        }]
                                    }
                                }
                            });
                        }
                    }
                });
            }

            // Fetch and render Top Vendors Chart
            function loadTopVendorsChart() {
                $.ajax({
                    url: 'controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { transaction: 'get top vendors', username: username },
                    success: function(data) {
                        if(data && data.length > 0) {
                            const labels = data.map(item => item.VENDOR_NAME);
                            const amounts = data.map(item => item.total_spent);

                            const ctx = document.getElementById('topVendorsChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        data: amounts,
                                        backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f1c40f', '#9b59b6'],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    legend: {
                                        position: 'top',
                                    },
                                }
                            });
                        }
                    }
                });
            }
            
            // Fetch and render Vendor Type Distribution Chart
            function loadVendorTypeDistributionChart() {
                $.ajax({
                    url: 'controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { transaction: 'get vendor type distribution', username: username },
                    success: function(data) {
                        if(data && data.length > 0) {
                            const labels = data.map(item => item.VENDOR_TYPE);
                            const counts = data.map(item => item.vendor_count);

                            const ctx = document.getElementById('vendorTypeDistributionChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        data: counts,
                                        backgroundColor: ['#1abc9c', '#3498db', '#9b59b6', '#f1c40f', '#e67e22', '#e74c3c', '#34495e'],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    legend: {
                                        position: 'right',
                                    },
                                }
                            });
                        }
                    }
                });
            }


            // Initial load
            loadPOSummary();
            loadMonthlyPOChart();
            loadTopVendorsChart();
            loadVendorTypeDistributionChart();
        });
    </script>
    </body>
</html>
 

 