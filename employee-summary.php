<?php
    require('session.php');
    require('config/config.php');
    require('classes/api.php');
    $api = new Api;
    $page_title = 'Employee Summary';
    # Check role permission (use new permission ID for this page)
    $page_access = $api->check_role_permissions($username, 32);
 
    if($page_access == 0){
        header('location: 404-page.php');
    }
    $get_total_employee_headcount = $api ->get_total_employee_headcount($username, 23);
    $get_total_employee_male = $api ->get_total_employee_male($username, 23);
    $get_total_employee_female = $api ->get_total_employee_female($username, 23);

    $get_total_employee_regular = $api ->get_total_employee_regular($username, 23);
    $get_total_employee_probitionary = $api ->get_total_employee_probitionary($username, 23);

    $get_total_employee_male_staff = $api ->get_total_employee_male_staff($username, 23);
    $get_total_employee_male_officer = $api ->get_total_employee_male_officer($username, 23);
    $get_total_employee_female_staff = $api ->get_total_employee_female_staff($username, 23);
    $get_total_employee_female_officer = $api ->get_total_employee_female_officer($username, 23);

    
?>
        <?php
            require('views/_head.php');
        ?>
        <link href="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="assets/libs/sweetalert2/sweetalert2.min.css">
        <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Add Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

        <style>
            .summary-card {
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                transition: transform 0.3s;
            }
            .summary-card:hover {
                transform: translateY(-5px);
            }
            .chart-container {
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                padding: 20px;
                margin-bottom: 24px;
                height: 100%;
            }
            .export-btn {
                margin-right: 10px;
            }
            .card-title, h2{
                color: black;
            }
        </style>
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
                                        <div class="d-flex align-items-center">
                                            <button type="button" class="btn btn-success export-btn" onclick="exportToPDF()">
                                                <i class="fas fa-file-pdf"></i> Export PDF
                                            </button>
                                            <button type="button" class="btn btn-primary export-btn" onclick="exportToExcel()">
                                                <i class="fas fa-file-excel"></i> Export Excel
                                            </button>
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Human Resource Modules</a></li>
                                                <li class="breadcrumb-item"><a href="employee-list.php">Employee</a></li>
                                                <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Summary Cards -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card summary-card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">TOTAL EMPLOYEES</h5>
                                        <h2 class="mb-0"><?php echo number_format($get_total_employee_headcount); ?></h2>

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card summary-card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">MALE</h5>
                                        <h2 class="mb-0"><?php echo number_format($get_total_employee_male); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card summary-card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">FEMALE</h5>
                                        <h2 class="mb-0"><?php echo number_format($get_total_employee_female); ?></h2>
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                        
                        <!-- Charts Row -->
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h5 class="mb-4">Headcount by Position & Gender</h5>
                                    <canvas id="positionGenderChart"></canvas>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="chart-container">
                                    <h5 class="mb-4">Headcount by Department</h5>
                                    <canvas id="departmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status and Department Breakdown Row -->
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">According to Status</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="statusTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Permanent</td>
                                                        <td>  <?php echo number_format($get_total_employee_regular); ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Probationary</td>
                                                        <td><?php echo number_format($get_total_employee_probitionary); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Trainee</td>
                                                        <td>0</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Contractual</td>
                                                        <td>0</td>
                                                    </tr>
                                                    <tr class="table-info">
                                                        <td><strong>TOTAL</strong></td>
                                                        <td><strong><strong>
                                                        <?php 
                                                            $total = $get_total_employee_regular + $get_total_employee_probitionary;
                                                            echo number_format($total); 
                                                        ?>
                                                    </strong></strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">According to Department</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="departmentTable">
                                                   <tr>
                                                        <th>Department</th>
                                                        <th>Count</th>
                                                    </tr>
                                                      <tbody id="departmentTableBody">
                                                    </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Detailed Table -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Employee Breakdown by Position and Gender</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="positionGenderTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Gender</th>
                                                        <th>Staff</th>
                                                        <th>Officer</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Male</td>
                                                        <td><?php echo number_format($get_total_employee_male_staff); ?></td>
                                                        <td><?php echo number_format($get_total_employee_male_officer); ?></td>
                                                        <td>
                                                            <?php $total_male = $get_total_employee_male_staff + $get_total_employee_male_officer;
                                                            echo number_format($total_male);    
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Female</td>
                                                        <td><?php echo number_format($get_total_employee_female_staff); ?></td>
                                                        <td><?php echo number_format($get_total_employee_female_officer); ?></td>
                                                        <td>
                                                            <?php 
                                                             $totalfemale = $get_total_employee_female_staff + $get_total_employee_female_officer;
                                                             echo number_format($totalfemale); 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr class="table-info">
                                                        <td><strong>TOTAL</strong></td>
                                                        <td><strong>
                                                            <?php 
                                                             $totalboth = $get_total_employee_female_staff + $get_total_employee_male_staff;
                                                              echo number_format($totalboth); 
                                                              ?>
                                                        </strong></td>
                                                        <td><strong>
                                                             <?php 
                                                             $totalboth = $get_total_employee_female_officer + $get_total_employee_male_officer;
                                                              echo number_format($totalboth); 
                                                              ?>
                                                        </strong></td>
                                                        <td><strong>
                                                             <?php 
                                                             $totalofficer = $get_total_employee_female_officer + $get_total_employee_male_officer;
                                                             $totalstaff = $get_total_employee_female_staff + $get_total_employee_male_staff;
                                                             $total = $totalstaff + $totalofficer;
                                                              echo number_format($total); 
                                                              ?>
                                                        </strong></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Personnel Status Table
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">STATUS OF PERSONNEL</h5>
                                        <h6 class="mb-3">As of June 15, 2025</h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover" id="personnelStatusTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>As of May 13, 2025</th>
                                                        <th>As of June 15, 2025</th>
                                                
 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>32</td>
                                                        <td>33</td>
                                                    
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
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
        <script src="assets/libs/qrcode/qrcode.min.js"></script>
        <script src="assets/libs/inputmask/min/jquery.inputmask.bundle.min.js"></script>
        <script src="assets/libs/select2/js/select2.min.js"></script>
        <script src="assets/libs/jquery.repeater/jquery.repeater.min.js"></script>
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        
        <!-- Add jsPDF and html2canvas for PDF export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <!-- Add SheetJS for Excel export -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

            <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Chart.js Data Labels Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>


        
        <script>
         
            
            // Department Chart - Updated with correct data
     
            
            // Export to PDF function
            function exportToPDF() {
                const element = document.querySelector('.page-content');
                const exportButtons = document.querySelectorAll('.export-btn');
                
                // Hide export buttons temporarily
                exportButtons.forEach(btn => btn.style.display = 'none');
                
                html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const { jsPDF } = window.jspdf;
                    const pdf = new jsPDF('p', 'mm', 'a4');
                    
                    const imgWidth = 210; // A4 width in mm
                    const pageHeight = 295; // A4 height in mm
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    let heightLeft = imgHeight;
                    let position = 0;
                    
                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                    
                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }
                    
                    pdf.save('Employee_Summary_Report.pdf');
                    
                    // Show export buttons again
                    exportButtons.forEach(btn => btn.style.display = 'inline-block');
                });
            }
            
            // Export to Excel function
            function exportToExcel() {
                const wb = XLSX.utils.book_new();
                
                // Summary data
                const summaryData = [
                    ['Employee Summary Report'],
                    ['Generated on: ' + new Date().toLocaleDateString()],
                    [''],
                    ['OVERVIEW'],
                    ['Total Employees', '33'],
                    ['Male', '15'],
                    ['Female', '18'],
                    ['Turnover Rate', '3.03%'],
                    [''],
                    ['ACCORDING TO STATUS'],
                    ['Status', 'Count'],
                    ['Permanent', '25'],
                    ['Probationary', '8'],
                    ['Trainee', '0'],
                    ['Contractual', '0'],
                    ['Total', '33'],
                    [''],
                    ['ACCORDING TO DEPARTMENT'],
                    ['Department', 'Count'],
                    ['Sales', '6'],
                    ['Marketing', '2'],
                    ['Loan Operations', '5'],
                    ['Finance & Accounting', '2'],
                    ['Credit & Collection', '7'],
                    ['Data Center', '2'],
                    ['Executive Offices', '5'],
                    ['HR & Admin', '3'],
                    ['Audit & Compliance', '1'],
                    ['Total', '33'],
                    [''],
                    ['BREAKDOWN BY POSITION AND GENDER'],
                    ['Gender', 'Staff', 'Officer', 'Total'],
                    ['Male', '12', '3', '15'],
                    ['Female', '12', '6', '18'],
                    ['Total', '24', '9', '33'],
                    [''],
                    ['STATUS OF PERSONNEL'],
                    ['As of May 13, 2025', 'As of June 15, 2025', 'Hired', 'Resigned/Terminated/End of Contract'],
                    ['32', '33', '5', '4', '', '-']
                ];
                
                const ws = XLSX.utils.aoa_to_sheet(summaryData);
                XLSX.utils.book_append_sheet(wb, ws, 'Employee Summary');
                
                XLSX.writeFile(wb, 'Employee_Summary_Report.xlsx');
            }
        </script>
    </body>
</html>