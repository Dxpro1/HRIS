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
    $add_purchase_order = $api->check_role_permissions($username, 282);

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
                                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="purchase-order.php">Purchase Order</a></li>
                                        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                 <form id="purchaseOrderForm" method="post" action="#">
                        <input type="hidden" id="purchase_order_id" name="purchase_order_id">

                        <!-- Supplier & Status -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body row">
                                        <div class="col-md-6 mb-3">
                                            <label for="vendor_id" class="form-label">Supplier <span class="required">*</span></label>
                                            <select class="form-control select2" id="vendor_id" name="vendor_id" data-placeholder="Choose a supplier" required></select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status_vendor" name="status">
                                                <option value="Pending Approval">Pending Approval</option>
                                                <option value="Approved">Approved</option>
                                                <option value="Fulfilled">Fulfilled</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="order_date" class="form-label">Order Date</label>
                                            <input type="date" class="form-control" id="order_date" name="order_date" value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="delivery_date" class="form-label">Delivery Date</label>
                                            <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Line Items -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                   <div class="repeater">
                                        <div data-repeater-list="items">
                                            <div data-repeater-item class="row mb-3">
                                                <div class="col-lg-3">
                                                    <label>Product Name</label>
                                                    <input type="text" name="item_description" class="form-control item-description" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>Quantity</label>
                                                    <input type="number" name="quantity" class="form-control item-quantity" value="1" min="1" required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>Unit</label>
                                                    <input type="text" name="unit" class="form-control item-unit"   required>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label>Unit Price</label>
                                                    <input type="number" name="price" class="form-control item-price" value="0.00" step="0.01" min="0" required>
                                                </div>

                                                    <div class="col-lg-2">
                                                        <label>Total</label>
                                                        <p class="form-control-static item-total pt-2">₱0.00</p>
                                                    </div>
                                                    <div class="col-lg-1 d-flex align-items-end">
                                                        <button data-repeater-delete type="button" class="btn btn-danger">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" data-repeater-create class="btn btn-success mt-2">
                                                <i class="bx bx-plus"></i> Add Item
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tax & Summary -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Tax Configuration</h5>
                                        <label for="withholding_tax_rate" class="form-label">Withholding Tax Rate (%)</label>
                                        <input type="number" class="form-control" id="withholding_tax_rate" name="withholding_tax_rate" value="2.00" step="0.01">
                                        <label for="vat_tax_rate" class="form-label mt-3">VAT Rate (%)</label>
                                        <input type="number" class="form-control" id="vat_tax_rate" name="vat_tax_rate" value="12.00" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Order Summary</h5>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td>Gross Amount:</td>
                                                    <td class="text-end" id="summary_gross_amount">₱0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Withholding Tax (<span id="summary_tax_rate_text">2</span>%):</td>
                                                    <td class="text-end text-danger" id="summary_tax_amount">- ₱0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>VAT (<span id="summary_vat_rate_text">12.00</span>%):</td>
                                                    <td class="text-end text-success" id="summary_vat_amount">+ ₱0.00</td>
                                                </tr>
                                                <tr class="bg-light">
                                                    <th>Net Amount:</th>
                                                    <td class="text-end" id="summary_net_amount">₱0.00</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Fields -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Additional Details</h5>
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="terms" class="form-label">Terms</label>
                                                <input type="text" class="form-control" id="terms" name="terms" value="Full payment">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="fob" class="form-label">F.O.B</label>
                                                <input type="text" class="form-control" id="fob" name="fob">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="requested_by" class="form-label">Requested By</label>
                                                <input type="text" class="form-control" id="requested_by" name="requested_by">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="req_no" class="form-label">Req. No.</label>
                                                <input type="text" class="form-control" id="req_no" name="req_no">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="delivery_note" class="form-label">Delivery Note</label>
                                            <textarea class="form-control" id="delivery_note" name="delivery_note" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approvers -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="conforme_supplier" class="form-label">Conforme Supplier</label>
                                                <input type="text" class="form-control" id="conforme_supplier" name="conforme_supplier">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="approved_by_assistant_gm" class="form-label">Approved by Assistant GM</label>
                                                <input type="text" class="form-control" id="approved_by_assistant_gm" name="approved_by_assistant_gm">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="approved_by_gm" class="form-label">Approved by GM</label>
                                                <input type="text" class="form-control" id="approved_by_gm" name="approved_by_gm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Submit Purchase Order</button>
                                    <a href="purchase-order.php" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <?php require('views/_footer.php'); ?>
        </div>
    </div>
    <?php
        require('views/_scripts.php'); 
    ?>
 
        
        <script src="assets/js/click-events.js"></script>
        <script src="assets/js/form-validation.js"></script>
        <script src="assets/js/datatable.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/jquery.repeater/jquery.repeater.min.js"></script>
        <script>
        $(document).ready(function () {
        // --- Repeater and Summary Calculation Logic (no changes needed here) ---
        $('.repeater').repeater({
            initEmpty: false,
            show: function () {
            $(this).slideDown();
            updateSummary();
            },
            hide: function (deleteElement) {
            $(this).slideUp(deleteElement, updateSummary);
            },
            isFirstItemUndeletable: true
        });

        function updateSummary() {
            let gross = 0.0;
            $('[data-repeater-item]').each(function () {
            const qty = parseFloat($(this).find('.item-quantity').val()) || 0;
            const price = parseFloat($(this).find('.item-price').val()) || 0;
            const total = qty * price;
            $(this).find('.item-total').text(`₱${total.toFixed(2)}`);
            gross += total;
            });

            const wt = parseFloat($('#withholding_tax_rate').val()) || 0;
            const vat = parseFloat($('#vat_tax_rate').val()) || 0;
            const wtAmount = gross * (wt / 100);
            const vatAmount = gross * (vat / 100);
            const net = gross - wtAmount + vatAmount;

            $('#summary_gross_amount').text(`₱${gross.toFixed(2)}`);
            $('#summary_tax_rate_text').text(wt.toFixed(2));
            $('#summary_tax_amount').text(`- ₱${wtAmount.toFixed(2)}`);
            $('#summary_vat_rate_text').text(vat.toFixed(2));
            $('#summary_vat_amount').text(`+ ₱${vatAmount.toFixed(2)}`);
            $('#summary_net_amount').text(`₱${net.toFixed(2)}`);
        }

        $(document).on('input', '.item-quantity, .item-price, #withholding_tax_rate, #vat_tax_rate', updateSummary);
        updateSummary();

        // --- CORRECTED FORM SUBMISSION LOGIC ---
        $('#purchaseOrderForm').submit(function(e){
            e.preventDefault();
            let formData = $(this).serializeArray();
            let username = $('#username').text().trim();

            // Structure items from repeater
            let itemsArray = [];
            let otherFormData = {};

            formData.forEach(function(field){
                let itemMatch = field.name.match(/^items\[(\d+)\]\[(.+)\]$/);
                if(itemMatch){
                    let index = itemMatch[1];
                    let fieldName = itemMatch[2];
                    if(!itemsArray[index]) { itemsArray[index] = {}; }
                    itemsArray[index][fieldName] = field.value;
                } else {
                    otherFormData[field.name] = field.value;
                }
            });

            // --- THIS IS THE FIX ---
            // 1. Recalculate the gross amount from the items array
            let grossAmount = 0.0;
            (itemsArray || []).forEach(item => {
                const quantity = parseFloat(item.quantity) || 0;
                const price = parseFloat(item.price) || 0;
                grossAmount += quantity * price;
            });

            // 2. Add the calculated gross_amount to the data being sent
            let submitData = otherFormData;
            submitData.gross_amount = grossAmount.toFixed(2);
            // --- END OF FIX ---

            submitData.items = JSON.stringify(itemsArray || []);
            submitData.transaction = 'add purchase order';
            submitData.username = username;

            // AJAX call to submit the form
            $.ajax({
                type: 'POST',
                url: 'controller.php',
                data: submitData,
                success: function(response){
                    if(response === 'Inserted'){
                        Swal.fire('Success','Purchase Order has been added.','success').then(() => {
                            window.location = 'purchase-order.php';
                        });
                    } else {
                        Swal.fire('Error', response || 'An unexpected error occurred.','error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('AJAX Error', `Request failed: ${error}`, 'error');
                }
            });
        });
        });
        </script>

       
    </body>
</html>
