<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');

    $api = new Api;
    $page_title = 'PMW Monitoring';

    # Check role permission for page access
    $page_access = $api->check_role_permissions($username, 429); // Use your actual permission ID
    if($page_access == 0){
        header('location: 404-page.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require('views/_head.php'); ?>
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    </head>

    <body data-sidebar="dark">
        <?php require('views/_preloader.php'); ?>

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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Modules</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Employee Management</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Options Card -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Filter Options</h5>
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-3">
                                                <label class="form-label">Employment Type</label>
                                                <select class="form-select" id="employment-type-filter">
                                                    <option value="">All Types</option>
                                                    <option value="PERMANENT">Permanent / Regular</option>
                                                    <option value="PROVISIONAL">Provisional</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Submission Status</label>
                                                <select class="form-select" id="status-filter">
                                                    <option value="">All Statuses</option>
                                                    <option value="PENDING">Pending</option>
                                                    <option value="SUBMITTED">Submitted</option>
                                                    <option value="OVERDUE">Overdue</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Department</label>
                                                <select class="form-select" id="department-filter">
                                                     <option value="">All Departments</option>
                                                     <!-- This can be populated with PHP or AJAX -->
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-secondary w-100" id="reset-pmw-filters">
                                                    <i class="bx bx-refresh me-1"></i>Reset Filters
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dashboard Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3"><div class="card bg-success text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><span class="text-white-50">Submitted</span><h4 class="mb-0 text-white" id="submitted-count">0</h4></div><div class="flex-shrink-0"><i class="bx bx-check-circle font-size-24"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card bg-warning text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><span class="text-white-50">Pending</span><h4 class="mb-0 text-white" id="pending-count">0</h4></div><div class="flex-shrink-0"><i class="bx bx-time font-size-24"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card bg-danger text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><span class="text-white-50">Overdue</span><h4 class="mb-0 text-white" id="overdue-count">0</h4></div><div class="flex-shrink-0"><i class="bx bx-error font-size-24"></i></div></div></div></div></div>
                            <div class="col-md-3"><div class="card bg-info text-white"><div class="card-body"><div class="d-flex align-items-center"><div class="flex-grow-1"><span class="text-white-50">Total</span><h4 class="mb-0 text-white" id="total-count">0</h4></div><div class="flex-shrink-0"><i class="bx bx-group font-size-24"></i></div></div></div></div></div>
                        </div>

                        <!-- PMW Monitoring Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                         <h4 class="card-title">PMW Submission Monitoring</h4>
                                         <button type="button" class="btn btn-info" id="refresh-pmw-data">
                                            <i class="bx bx-refresh me-1"></i>Refresh Data
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <table id="pmw-monitoring-datatable" class="table table-bordered table-hover align-middle dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th class="all" width="20%">Full Name</th>
                                                    <th class="all" width="15%">Department</th>
                                                    <th class="none">Designation</th>
                                                    <th class="none">Employment Type</th>
                                                    <th class="none">Join Date</th>
                                                    <th class="all" width="15%">PMW Period</th>
                                                    <th class="all" width="15%">Due Date</th>
                                                    <th class="all" width="10%">Status</th>
                                                    <th class="none">Submission Date</th>
                                                    <th class="all" width="10%">Action</th>
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

                <?php require('views/_footer.php'); ?>
            </div>
        </div>

        <?php require('views/_scripts.php'); ?>
        <script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>


    </body>
</html>