<?php
	require('session.php');
	require('config/config.php');
	require('classes/api.php');
	$api = new Api;
	$page_title = 'Email Setting';

	# Check role permission
	$page_access = $api->check_role_permissions($username, 112);
    $add_email_notification_settings = $api->check_role_permissions($username, 113);

	if($page_access == 0){
		header('location: 404-page.php');
	}
?>
        <?php
            require('views/_head.php');
        ?>
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/tui-calendar/tui-calendar.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/tui-date-picker/tui-date-picker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/tui-time-picker/tui-time-picker.min.css" rel="stylesheet" type="text/css" />
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
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Administrator Modules</a></li>
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                                            <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="card-title">Email Notification Table</h4>
                                            </div>
                                            <?php
                                                if($add_email_notification_settings > 0){
                                                    echo '<div class="col-md-6">
                                                        <div class="float-end">
                                                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" id="add-email-notification"><i class="bx bx-plus label-icon"></i> Add Email Notification</button>
                                                        </div>
                                                    </div>';
                                                }
                                            ?>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <table id="email-notification-datatable" class="table table-bordered align-middle mb-0 table-hover table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th style="width:50%" class="all">Notification</th>
                                                            <th style="width:20%" class="all">Status</th>
                                                            <th style="width:30%" class="all">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>       
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4 class="card-title">Email Configuration</h4>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <form class="cmxform" id="emailconfigurationForm" method="post" action="#">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="mailhost" class="form-label">Mail Host <span class="required">*</span></label>
                                                            <input type="hidden" id="mailid" name="mailid" value="1">
                                                            <input type="text" class="form-control maxlength" autocomplete="off" id="mailhost" name="mailhost" maxlength="100">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="port" class="form-label">Mail Port <span class="required">*</span></label>
                                                            <input id="port" name="port" class="form-control" type="number" min="0">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="mailuser" class="form-label">Mail Username <span class="required">*</span></label>
                                                            <input type="text" class="form-control maxlength" autocomplete="off" id="mailuser" name="mailuser" maxlength="200">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="mailpassword" class="form-label">Mail Password <span class="required">*</span></label>
                                                            <div class="input-group auth-pass-inputgroup">
                                                                <input type="password" id="mailpassword" name="mailpassword" class="form-control" aria-label="Password" aria-describedby="password-addon">
                                                                <button class="btn btn-light " type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="mailencryption" class="form-label">Mail Encryption <span class="required">*</span></label>
                                                            <select class="form-control select2" id="mailencryption" name="mailencryption">
                                                            <option value="">--</option>
                                                            <?php echo $api->generate_system_options('MAILENCRYPTION'); ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="smptauth" class="form-label">SMPT Authentication</label>
                                                            <select class="form-control select2" id="smptauth" name="smptauth">
                                                            <option value="1">True</option>
                                                            <option value="0">False</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label for="smptautotls" class="form-label">SMPT Auto TLS</label>
                                                            <select class="form-control select2" id="smptautotls" name="smptautotls">
                                                            <option value="1">True</option>
                                                            <option value="0">False</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="mailfromname" class="form-label">Mail From Name <span class="required">*</span></label>
                                                            <input type="text" class="form-control maxlength" autocomplete="off" id="mailfromname" name="mailfromname" maxlength="200">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="mailfromemail" class="form-label">Mail From Email <span class="required">*</span></label>
                                                            <input id="mailfromemail" name="mailfromemail" class="form-control email-inputmask maxlength" maxlength="200" autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="mb-3">
                                                            <button type="submit" class="btn btn-primary" id="submitform" form="emailconfigurationForm">Submit</button>
                                                            <button type="button" class="btn btn-success" id="testemail">Send Test Email</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
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
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/datatable.js"></script>
        <script src="assets/libs/tui-code-snippet/tui-code-snippet.min.js"></script>
        <script src="assets/libs/tui-dom/tui-dom.min.js"></script>
        <script src="assets/libs/tui-date-picker/tui-date-picker.min.js"></script>
        <script src="assets/libs/tui-time-picker/tui-time-picker.min.js"></script>
        <script src="assets/libs/tui-calendar/tui-calendar.min.js"></script>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    </body>
</html>
