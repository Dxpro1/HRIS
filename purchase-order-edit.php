<?php
// --- purchase-order-edit.php ---
require('session.php');
require('config/config.php');
require('classes/api.php');
    
$api = new Api;
$page_title = 'Edit Purchase Order';

// --- Permissions Check ---
$page_access = $api->check_role_permissions($username, 136);
if($page_access == 0){
    header('location: 404-page.php');
    exit;
}
$edit_purchase_order_permission = $api->check_role_permissions($username, 156);

// --- Get ID from URL ---
$purchase_order_id_to_edit = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $purchase_order_id_to_edit = $_GET['id']; // Keep as string for varchar(50)
    if ($edit_purchase_order_permission == 0) {
        $page_title = 'View Purchase Order'; 
    }
} else {
    header('location: purchase-order.php'); 
    exit;
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
                            <input type="hidden" id="purchase_order_id" name="purchase_order_id" value="<?php echo $purchase_order_id_to_edit; ?>">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-body row">
                                            <div class="col-md-6 mb-3">
                                                <label for="vendor_id" class="form-label">Supplier <span class="required">*</span></label>
                                                <select class="form-control select2" id="vendor_id" name="vendor_id" data-placeholder="Choose a supplier" required></select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="status_vendor" class="form-label">Status</label>
                                                <select class="form-control" id="status_vendor" name="status">
                                                    <option value="Pending Approval">Pending Approval</option>
                                                    <option value="Approved">Approved</option>
                                                    <option value="Fulfilled">Fulfilled</option>
                                                    <option value="Cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="order_date" class="form-label">Order Date</label>
                                                <input type="date" class="form-control" id="order_date" name="order_date" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="delivery_date" class="form-label">Delivery Date</label>
                                                <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- FIXED: Corrected repeater HTML structure -->
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
                                                            <input type="text" name="unit" class="form-control item-unit" required>
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

                            <!-- Tax Configuration and Summary sections remain the same -->
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

                            <!-- Additional Details and Approvers sections remain the same -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Additional Details</h5>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="terms" class="form-label">Terms</label>
                                                    <input type="text" class="form-control" id="terms" name="terms">
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Approvers</h5>
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="d-flex gap-2">
                                        <?php if($edit_purchase_order_permission > 0) { ?>
                                            <button type="submit" id="submitform" class="btn btn-primary">Update Purchase Order</button>
                                        <?php } ?>
                                        <a href="purchase-order.php" class="btn btn-secondary">Back to List</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php require('views/_footer.php'); ?>
            </div>
        </div>
        
        <?php require('views/_scripts.php'); ?>
        
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

    
// Replace the entire JavaScript section with this completely fixed version

// Replace the entire JavaScript section with this completely fixed version

// Replace the entire JavaScript section with this simplified version

<script>
let currentMode = 'edit';
let currentPOId = null;
let isFormInitialized = false;

function initializeVendorSelect2() {
    if ($('#vendor_id').hasClass('select2-hidden-accessible')) {
        $('#vendor_id').select2('destroy');
    }
    $('#vendor_id').select2({
        placeholder: "Choose a supplier",
        allowClear: true,
        minimumInputLength: 1, // Add minimum input length for search
        width: '100%',
        ajax: {
            url: 'controller.php',
            type: 'POST', // Change to POST to match working version
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    transaction: 'vendor dropdown', // Use same transaction as working version
                    search: params.term || ''
                };
            },
            processResults: function(data) {
                if (data.error) {
                    Swal.fire('Error', data.error, 'error');
                    return { results: [] };
                }
                // Map the results to the correct format
                return {
                    results: data.map(function (item) {
                        return {
                            id: item.id,        // your PHP uses 'id' and 'text'
                            text: item.text     // required by Select2
                        };
                    })
                };
            },
            cache: true,
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                console.error('Response:', xhr.responseText);
            }
        }
    });

    // Optional: Add the vendor details fetch functionality like in the working version
    $('#vendor_id').off('change.vendorDetails').on('change.vendorDetails', function () {
        var vendor_id = $(this).val();
        
        if (vendor_id) {
            $.ajax({
                url: 'controller.php',
                type: 'POST',
                data: {
                    transaction: 'get vendor details',
                    vendor_id: vendor_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Optional: Show vendor details (you can remove this if not needed)
                        // Swal.fire({
                        //     icon: 'info',
                        //     title: 'Vendor Selected',
                        //     html: `<strong>Contact:</strong> ${response.data.CONTACT_PERSON}<br><strong>Email:</strong> ${response.data.EMAIL}<br><strong>Phone:</strong> ${response.data.PHONE}`
                        // });
                    }
                },
                error: function () {
                    console.log('Failed to fetch vendor details');
                }
            });
        }
    });
}

function initializeRepeaterOnce() {
    const $repeater = $('.repeater');
    
    // Check if repeater is already initialized by checking for specific data attribute
    if ($repeater.attr('data-repeater-initialized') === 'true') {
        return;
    }
    
    // Mark as being initialized BEFORE actual initialization
    $repeater.attr('data-repeater-initialized', 'true');
    
    $repeater.repeater({
        initEmpty: false,
        isFirstItemUndeletable: true,
        show: function () {
            $(this).slideDown();
            updateSummary();
        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
            updateSummary();
        }
    });
}

function loadPurchaseOrderDataForEditing(poId) {
    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'json',
        data: {
            transaction: 'get purchase order details',
            purchase_order_id: poId
        },
        success: function(response) {
            if (response.error || !response.details) {
                Swal.fire('Error', response.error || 'Could not load data.', 'error').then(() => {
                    window.location.href = 'purchase-order.php';
                });
                return;
            }
            
            const details = response.details;
            const items = response.items || [];
            
            // Populate header fields
            $('#purchase_order_id').val(details.PURCHASE_ORDER_ID);
            
            if (details.VENDOR_ID_FK && details.VENDOR_NAME) {
                $('#vendor_id').empty();
                var newOption = new Option(details.VENDOR_NAME, details.VENDOR_ID_FK, true, true);
                $('#vendor_id').append(newOption).trigger('change');
            }
            
            $('#status_vendor').val(details.STATUS || '');
            $('#order_date').val(details.ORDER_DATE || '');
            $('#delivery_date').val(details.DELIVERY_DATE || '');
            $('#withholding_tax_rate').val(details.WITHHOLDING_TAX_RATE || '2.00');
            $('#vat_tax_rate').val(details.VAT_TAX_RATE || '12.00');
            $('#terms').val(details.TERMS || '');
            $('#fob').val(details.FOB || '');
            $('#delivery_note').val(details.DELIVERY_NOTE || '');
            $('#requested_by').val(details.REQUESTED_BY || '');
            $('#req_no').val(details.REQ_NO || '');
            $('#conforme_supplier').val(details.CONFORME_SUPPLIER || '');
            $('#approved_by_assistant_gm').val(details.APPROVED_BY_ASSISTANT_GM || '');
            $('#approved_by_gm').val(details.APPROVED_BY_GM || '');

            // Handle items - populate existing rows instead of recreating repeater
            if (items && items.length > 0) {
                // Get all existing repeater items
                const $existingItems = $('.repeater [data-repeater-item]');
                
                items.forEach(function(item, index) {
                    let $targetItem;
                    
                    if (index < $existingItems.length) {
                        // Use existing row
                        $targetItem = $existingItems.eq(index);
                    } else {
                        // Add new row if needed
                        $('[data-repeater-create]').click();
                        $targetItem = $('.repeater [data-repeater-item]').last();
                    }
                    
                    // Populate the row
                    $targetItem.find('input[name*="item_description"]').val(item.ITEM_DESCRIPTION || '');
                    $targetItem.find('input[name*="quantity"]').val(item.QUANTITY || '1');
                    $targetItem.find('input[name*="unit"]').val(item.UNIT || '');
                    $targetItem.find('input[name*="price"]').val(item.UNIT_PRICE || '0.00');
                });
                
                // Remove extra rows if we have more rows than items
                if ($existingItems.length > items.length) {
                    for (let i = items.length; i < $existingItems.length; i++) {
                        $existingItems.eq(i).find('[data-repeater-delete]').click();
                    }
                }
            }
            
            updateSummary();
        },
        error: function(xhr) {
            Swal.fire('Error', 'An error occurred while fetching PO data.', 'error').then(() => {
                window.location.href = 'purchase-order.php';
            });
        }
    });
}

function updateSummary() {
    let gross = 0.0;
    $('.repeater [data-repeater-item]').each(function() {
        const qty = parseFloat($(this).find('input[name*="quantity"]').val()) || 0;
        const price = parseFloat($(this).find('input[name*="price"]').val()) || 0;
        const total = qty * price;
        $(this).find('.item-total').text('₱' + total.toFixed(2));
        gross += total;
    });
    
    const wtRate = parseFloat($('#withholding_tax_rate').val()) || 0;
    const vatRate = parseFloat($('#vat_tax_rate').val()) || 0;
    const wtAmount = gross * (wtRate / 100);
    const vatAmount = gross * (vatRate / 100);
    const net = gross - wtAmount + vatAmount;
    
    $('#summary_gross_amount').text('₱' + gross.toFixed(2));
    $('#summary_tax_rate_text').text(wtRate.toFixed(2));
    $('#summary_tax_amount').text('- ₱' + wtAmount.toFixed(2));
    $('#summary_vat_rate_text').text(vatRate.toFixed(2));
    $('#summary_vat_amount').text('+ ₱' + vatAmount.toFixed(2));
    $('#summary_net_amount').text('₱' + net.toFixed(2));
}

function initializePurchaseOrderForm(poIdToLoad) {
    if (isFormInitialized) return;
    
    // Initialize components
    initializeVendorSelect2();
    
    // Initialize repeater ONLY ONCE
    initializeRepeaterOnce();
    
    if (poIdToLoad) {
        currentMode = 'edit';
        currentPOId = poIdToLoad;
        // Small delay to ensure repeater is ready
        setTimeout(function() {
            loadPurchaseOrderDataForEditing(poIdToLoad);
        }, 500);
        $('#submitform').html('Update Purchase Order');
    }

    // Bind event handlers
    $(document).off('input', '.item-quantity, .item-price, #withholding_tax_rate, #vat_tax_rate')
        .on('input', '.item-quantity, .item-price, #withholding_tax_rate, #vat_tax_rate', updateSummary);

    // Form submission handler
    $('#purchaseOrderForm').off('submit').on('submit', function(e){
        e.preventDefault();
        if (!$(this).valid()) return false;
        
        let formDataArray = $(this).serializeArray();
        let itemsArray = [];
        let otherFormData = {};
        
        formDataArray.forEach(function(field){
            let itemMatch = field.name.match(/^items\[(\d+)\]\[(.+)\]$/);
            if (itemMatch) {
                let index = itemMatch[1];
                let fieldName = itemMatch[2];
                if (!itemsArray[index]) { itemsArray[index] = {}; }
                itemsArray[index][fieldName] = field.value;
            } else {
                otherFormData[field.name] = field.value;
            }
        });
        
        let gross = 0.0;
        (itemsArray || []).forEach(item => {
            if(item) {
               gross += (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
            }
        });
        
        let submitData = otherFormData;
        submitData.items = JSON.stringify(itemsArray.filter(Boolean));
        submitData.gross_amount = gross;
        submitData.username = '<?php echo $username; ?>';
        submitData.transaction = 'update purchase order';
        submitData.purchase_order_id = currentPOId;
        
        $.ajax({
            type: 'POST',
            url: 'controller.php',
            data: submitData,
            beforeSend: function() {
                $('#submitform').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status"></span> Updating...');
            },
            success: function(response) {
                if (response === 'Updated') {
                    Swal.fire('Success', 'Purchase Order updated successfully.', 'success').then(() => {
                        window.location.href = 'purchase-order.php';
                    });
                } else {
                    Swal.fire('Error', response || 'An error occurred.', 'error');
                    $('#submitform').prop('disabled', false).html('Update Purchase Order');
                }
            },
            error: function() {
                Swal.fire('AJAX Error', 'An error occurred during the request.', 'error');
                $('#submitform').prop('disabled', false).html('Update Purchase Order');
            }
        });
    });
    
    isFormInitialized = true;
}

$(document).ready(function() {
    const phpPoId = <?php echo json_encode($purchase_order_id_to_edit); ?>;
    initializePurchaseOrderForm(phpPoId);
});
</script>

    </body>
</html>
