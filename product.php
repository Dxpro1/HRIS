<?php
	require('session.php');
	require('config/config.php');
	require('classes/api.php');
    
	$api = new Api;
	$page_title = 'Purchase Order';

	# Check role permission
	$page_access = $api->check_role_permissions($username, 136);
    $add_attendance_record = $api->check_role_permissions($username, 137);
    $import_attendance_record = $api->check_role_permissions($username, 140);
    $import_employee_leave = $api->check_role_permissions($username, 282);
    $add_product = $api->check_role_permissions($username, 136);

	if($page_access == 0){
		header('location: 404-page.php');
	}
?>
        <?php
            require('views/_head.php');
        ?>
        <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Purchase Order</a></li>
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
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <p class="text-muted">Manage the company's reusable products and their prices.</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <?php if($add_product > 0){ echo '<button id="add-product" type="button" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-plus me-1"></i> Add Product</button>'; } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="product-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="all">Product Name</th>
                                                    <th>Term</th>
                                                    <th>Unit Price</th>
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
            <?php require('views/_footer.php'); ?>
        </div>
    </div>

    <?php require('views/_scripts.php'); ?>
    
    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>

    <!-- Page specific scripts -->
    <script src="assets/js/datatable.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/functions.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/form-validation.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/click-events.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/main.js?v=<?php echo time(); ?>"></script>
    </body>
</html>
