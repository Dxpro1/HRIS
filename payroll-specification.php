<?php
	require('session.php');
	require('config/config.php');
	require('classes/api.php');
	$api = new Api;
	$page_title = 'Payroll Specification';

	# Check role permission
	$page_access = $api->check_role_permissions($username, 73);
    $add_payroll_specification = $api->check_role_permissions($username, 74);
    $import_payroll_specification = $api->check_role_permissions($username, 141);

	if($page_access == 0){
		header('location: 404-page.php');
	}
?>
        <?php
            require('views/_head.php');
        ?>
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body data-sidebar="dark">
        <?php
            require('views/_preloader.php');
        ?>
        <div id="layout-wrapper">
            <?php
                require('views/_nav_header.php');
                require('views/_menu.php');
            ?>

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $page_title; ?></h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Human Resource Modules</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Payroll</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="card-title">Payroll Specification Table</h4>
                                            </div>
                                            <?php
                                                if($add_payroll_specification > 0 || $import_payroll_specification > 0){
                                                    $dropdown = '<div class="col-md-6">
                                                        <div class="float-end btn-group">
                                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                                Options <i class="mdi mdi-chevron-down"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">';

                                                                if($add_payroll_specification > 0){
                                                                    $dropdown .= '<a class="dropdown-item" href="javascript: void(0);" id="add-payroll-specification">Add Payroll Specification</a>';
                                                                }

                                                                if($import_payroll_specification > 0){
                                                                    $dropdown .= '<a class="dropdown-item" href="javascript: void(0);" id="import-payroll-specification">Import Payroll Specification</a>';
                                                                }

                                                    $dropdown .= '</div>
                                                            </div>
                                                        </div>';

                                                    echo $dropdown;
                                                }
                                            ?>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="payroll-specification-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th class="all">Employee</th>
                                                            <th class="all">Payroll ID</th>
                                                            <th class="all">Specification Type</th>
                                                            <th class="all">Category</th>
                                                            <th class="all">Status</th>
                                                            <th class="all">Amount</th>
                                                            <th class="all">Payroll Date</th>
                                                            <th class="all">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    require('views/_footer.php');
                ?>
            </div>
        </div>
        <?php
	        require('views/_scripts.php');
        ?>
        
        <script src="assets/js/click-events.js"></script>
        <script src="assets/js/on-change-events.js"></script>
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/datatable.js"></script>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    </body>
</html>
