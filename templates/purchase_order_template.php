<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order <?= htmlspecialchars($po_details['PURCHASE_ORDER_ID']) ?></title>
    <style>
    :root {
        --brand-blue: #4B6CB7;      /* Adjust to match your logo blue */
        --brand-dark: #2a3042;
        --table-hdr-bg: #f1f4f8;
        --summary-bg: #f4f7fc;
    }
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        font-size: 11px;
        color: #222;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        margin: 0; background: #f5f8fa;
    }
    .page-wrapper {
        margin: 0 auto; /* Remove auto margin for page wrapper if you want it full width before print */
        background: #f5f8fa;
    }
    .container {
        background: #fff;
        max-width: 830px;
        margin: 30px auto; /* Standard margin for screen display */
        border-radius: 10px;
        padding: 22px 26px 12px 26px;
        box-shadow: 0 10px 25px #4b6cb724;
        page-break-after: always; /* This is key for printing two pages */
    }
    /* Style for the second container (Accounting Copy) to remove its bottom margin/shadow */
    .container:last-of-type {
        margin-bottom: 0;
        box-shadow: none; /* No shadow for the last one */
        page-break-after: auto; /* No page break after the very last one */
    }

    /* Header logo and address row */
    .header-row { width:100%; margin-bottom:10px; }
    .header-logo { width: 110px; vertical-align:top;}
    .header-details {
        padding-left: 16px;
        font-size: 1.05em;
        color: var(--brand-dark);
    }
    .header-details b { font-size: 1.13em; color: var(--brand-blue);}
    /* PO header right */
    .po-meta {
        background: var(--brand-blue);
        color: #fff;
        border-radius: 7px;
        text-align: right;
        padding: 18px 24px 10px 14px;
        font-weight: 400;
        width: 320px;
    }
    .po-meta h1 { margin: 0; font-weight: 700; font-size: 1.5em; letter-spacing: 0.09em;}
    .po-meta .meta-row { font-size: .98em; margin-top: 5px;}
    .po-meta .meta-label { color: #dbe3f7; font-size: .94em;}
    .po-meta .po-id {
        display: inline-block; background: #fff; color: var(--brand-blue); font-weight: bold;
        border-radius: 17px; padding: 2.5px 18px; margin-left: 9px;
        border: 1.5px solid #537edf;
        font-size: 1.06em;
        box-shadow: 0 2px 6px #4b6cb72c;
    }
    /* Two columns: Supplier / ShipTo */
    .parties-row { width:100%; margin-top:18px; }
    .party-box {
        background: #f8fafe;
        border: 1px solid #e0e4ed;
        border-radius: 6px;
        padding: 12px 16px 11px 16px;
        width: 48%;
        vertical-align: top;
        line-height: 1.7;
        font-size: 1em;
    }
    .party-box strong { color: var(--brand-blue); }
    .party-title { color:var(--brand-blue); font-weight:700;}
    /* Details table */
    .details-table { width:100%; margin-top:18px; border-collapse:collapse; font-size:1em;}
    .details-table th, .details-table td {
        border: 1px solid #e9e9ef;
        padding:8px 6px; text-align:center;
    }
    .details-table th {
        background: var(--table-hdr-bg);
        color: var(--brand-blue);
        font-weight:600;
    }
    /* Items Table */
    .items-table {
        width: 100%; margin-top:15px; border-collapse:collapse; font-size:1.07em;
        border-radius: 5px; overflow: hidden;
    }
    .items-table thead th {
        background: var(--table-hdr-bg);
        color: var(--brand-dark); font-weight:700;
        border-bottom: 2px solid var(--brand-blue);
    }
    .items-table td, .items-table th {
        border: 1px solid #dde4f2;
        padding: 8px 6px;
    }
    /* Summary block below items */
    .summary-block {
        width: 55%; margin: 14px 0 0 auto; background:var(--summary-bg);
        border-radius: 7px; border: 1px solid #d3d9e8;
        font-size: 1.05em;
    }
    .summary-block td { border: none; padding: 8px 13px;}
    .summary-block tr:not(:last-child) td { border-bottom: 1px solid #e5e9f7;}
    .summary-block tr:last-child td { font-weight: bold; color: var(--brand-blue);}
    .notes {
        margin-top: 14px; margin-bottom: 8px;
        font-size: .96em; color: #444;
        background: #f5faff; border-left: 3px solid var(--brand-blue);
        padding: 8px 17px 5px 17px; border-radius: 5px;
    }
    .signatures {
        margin-top: 29px; width:100%; text-align:center;
        border-spacing: 0; table-layout:fixed;
    }
    .signatures td { width:33%; vertical-align:bottom;}
    .signature-line {
        display:inline-block; border-top:2px solid var(--brand-blue); min-width:170px;
        margin-top:14px; padding-top:7px; font-size:.96em; font-weight:500; color:#223458;
    }
    /* Copy designation footer */
    .footer-note {
        margin-top: 23px; font-size:.96em; color: #4b6cb7; text-align: center; letter-spacing: .08em;
    }
    @media print {
        body,.page-wrapper { background:#fff !important; } /* Ensure white background when printing */
        .container {
            padding: 0 !important; /* Remove padding when printing, allow full bleed if needed */
            margin: 0 auto 30px auto !important; /* Restore margin for spacing between copies */
            box-shadow: none !important; /* Remove shadows for print */
            page-break-after: always; /* Explicitly ensure page break */
        }
        /* Remove the last page break if there are only two copies */
        .container:last-of-type {
            page-break-after: auto !important;
            margin-bottom: 0 !important;
        }
        .footer-note { color:#000; } /* Ensure footer is visible */
    }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <!-- Supplier Copy -->
        <div class="container">
            <table class="header-row"><tr>
                <td class="header-logo">
                    <img src="./assets/images/application-settings/PO.jpg" style="width: 95px;">
                </td>
                <td class="header-details">
                    <b>Encore Leasing & Finance Corp.</b><br>
                    Brgy. Dicarma<br>
                    Maharlika Highway<br>
                    Cabanatuan City, N.E. 3100<br>
                    Attn: Ma. Anjeli S. Cadiz - Baena<br>
                    Tel: 044-940-5625<br>
                    <span style="color:#2a3042;font-size:.99em;">Email: ascadiz@encorefinancials.com</span>
                </td>
                <td class="po-meta">
                    <h1>PURCHASE ORDER</h1>
                    <div class="meta-row">
                        <span class="meta-label">PO No.:</span>
                        <span class="po-id"><?= htmlspecialchars($po_details['PURCHASE_ORDER_ID']) ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Date:</span>
                           <?php
                                date_default_timezone_set('Asia/Manila');
                                echo date('F d, Y');
                            ?>
                    </div>
                    <div class="meta-row" style="margin-top:6px;">
                        <span class="meta-label">Page Number</span> 1 of 2
                    </div>
                </td>
            </tr></table>
            <!-- Supplier & Ship To Block -->
            <table class="parties-row"><tr>
                <td class="party-box">
                    <div class="party-title">Supplier:</div>
                    <strong><?= htmlspecialchars($po_details['VENDOR_NAME']) ?></strong><br>
                    <?= nl2br(htmlspecialchars($po_details['VENDOR_ADDRESS'])) ?><br>
                    <?= htmlspecialchars($po_details['PHONE']) ? "Phone: ".htmlspecialchars($po_details['PHONE'])."<br>" : "" ?>
                    <?php if(!empty($po_details['BANK_NAME'])): ?>
                        Bank: <?= htmlspecialchars($po_details['BANK_NAME']) ?><br>
                        Account Name: <?= htmlspecialchars($po_details['BANK_ACCOUNT_NAME']) ?><br>
                        Account No.: <?= htmlspecialchars($po_details['BANK_ACCOUNT_NUMBER']) ?>
                    <?php endif; ?>
                </td>
                <td class="party-box" style="text-align:left">
                    <div class="party-title">Ship To:</div>
                    <strong>Ma. Anjeli S. Cadiz-Baena</strong><br>
                    Encore Leasing & Finance Corp. Brgy. Dicarma<br>
                    Maharlika Highway, Cabanatuan City, N.E. 3100<br>
                    Phone: 044 940-5625
                </td>
            </tr></table>
            <!-- Terms/Delivery Detail Table -->
            <table class="details-table">
                <tr>
                    <th>Terms</th>
                    <th>F.O.B.</th>
                    <th>Delivery Note</th>
                    <th>Requested By</th>
                    <th>Req. No.</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($po_details['TERMS']) ?></td>
                    <td><?= htmlspecialchars($po_details['FOB']) ?></td>
                    <td><?= htmlspecialchars($po_details['DELIVERY_NOTE']) ?></td>
                    <td><?= htmlspecialchars($po_details['REQUESTED_BY']) ?></td>
                    <td><?= htmlspecialchars($po_details['REQ_NO']) ?></td>
                </tr>
            </table>
            <!-- Item Table -->
            <table class="items-table" style="margin-top:18px;">
                <thead>
                    <tr>
                        <th style="width:5%;">Qty</th>
                        <th style="width:45%;">Description</th>
                        <th style="width:12%;">Delivery</th>
                        <th style="width:14%;">Unit Price</th>
                        <th style="width:14%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $line_number = 1;
                    foreach ($po_items as $item) {
                        $total_price = $item['QUANTITY'] * $item['UNIT_PRICE'];
                        echo '<tr>
                                <td style="text-align: center;">'. number_format($item['QUANTITY']) .' '. htmlspecialchars($item['UNIT']) .'</td>
                                <td>'. htmlspecialchars($item['ITEM_DESCRIPTION']) .'</td>
                                <td style="text-align: center;">'. date('m/d/Y', strtotime($po_details['DELIVERY_DATE'])) .'</td>
                                <td style="text-align:right;">'. number_format($item['UNIT_PRICE'], 2) .'</td>
                                <td style="text-align:right;">'. number_format($total_price, 2) .'</td>
                            </tr>';
                        $line_number++;
                    }
                    while ($line_number <= 6) { // Adjust the number of blank rows as needed
                        echo '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>';
                        $line_number++;
                    }
                    ?>
                </tbody>
            </table>
            <!-- Summary block below items -->
            <table class="summary-block">
                <tr><td style="text-align:left;">Gross Amount</td><td style="text-align:right;"><?= number_format($po_details['GROSS_AMOUNT'],2) ?></td></tr>
                <tr><td style="text-align:left;">Withholding Tax (<?= number_format($po_details['WITHHOLDING_TAX_RATE'],2) ?>%)</td><td style="text-align:right;">(<?= number_format($po_details['WITHHOLDING_TAX_AMOUNT'],2) ?>)</td></tr>
                <tr><td style="text-align:left;">VAT (<?= number_format($po_details['VAT_TAX_RATE'],2) ?>%)</td><td style="text-align:right;"><?= number_format($po_details['VAT_TAX_AMOUNT'],2) ?></td></tr>
                <tr><td style="text-align:left;"><b>NET AMOUNT</b></td><td style="text-align:right;"><?= number_format($po_details['NET_AMOUNT'], 2) ?></td></tr>
            </table>
            <div class="notes">
                <ol style="margin:0 1.2em 0 1.2em;">
                    <li>We reserve the right to cancel this order upon supplier's failure to meet required delivery date.</li>
                    <li>Items will be returned at supplier's expense if not according to specifications.</li>
                    <li>Copy of delivery receipt/Invoice should be provided for each delivery made.</li>
                </ol>
            </div>
            <!-- Signatures bottom -->
            <table class="signatures"><tr>
                <td>
                    <div class="signature-line"><b>Conforme:</b> <?= htmlspecialchars($po_details['CONFORME_SUPPLIER']) ?><br>
                    <span style="font-weight:400;">(Supplier Name &amp; Signature)</span></div>
                </td>
                <td>
                    <div class="signature-line">Ms. Maria Anjeli S. Cadiz-Baena<br>
                    Assistant General Manager/OFA</div>
                </td>
                <td>
                    <div class="signature-line">Atty. Jose Enrique S. Cadiz<br>
                    General Manager</div>
                </td>
            </tr></table>
            <div class="footer-note">Supplier Copy</div>
        </div>

        <!-- Accounting Copy -->
        <div class="container">
            <table class="header-row"><tr>
                <td class="header-logo">
                    <img src="./assets/images/application-settings/PO.jpg" style="width: 95px;">
                </td>
                <td class="header-details">
                    <b>Encore Leasing & Finance Corp.</b><br>
                    Brgy. Dicarma<br>
                    Maharlika Highway<br>
                    Cabanatuan City, N.E. 3100<br>
                    Attn: Ma. Anjeli S. Cadiz - Baena<br>
                    Tel: 044-940-5625<br>
                    <span style="color:#2a3042;font-size:.99em;">Email: ascadiz@encorefinancials.com</span>
                </td>
                <td class="po-meta">
                    <h1>PURCHASE ORDER</h1>
                    <div class="meta-row">
                        <span class="meta-label">PO No.:</span>
                        <span class="po-id"><?= htmlspecialchars($po_details['PURCHASE_ORDER_ID']) ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Date:</span>
                           <?php
                                date_default_timezone_set('Asia/Manila');
                                echo date('F d, Y');
                            ?>
                    </div>
                    <div class="meta-row" style="margin-top:6px;">
                        <span class="meta-label">Page Number</span> 2 of 2
                    </div>
                </td>
            </tr></table>
            <!-- Supplier & Ship To Block -->
            <table class="parties-row"><tr>
                <td class="party-box">
                    <div class="party-title">Supplier:</div>
                    <strong><?= htmlspecialchars($po_details['VENDOR_NAME']) ?></strong><br>
                    <?= nl2br(htmlspecialchars($po_details['VENDOR_ADDRESS'])) ?><br>
                    <?= htmlspecialchars($po_details['PHONE']) ? "Phone: ".htmlspecialchars($po_details['PHONE'])."<br>" : "" ?>
                    <?php if(!empty($po_details['BANK_NAME'])): ?>
                        Bank: <?= htmlspecialchars($po_details['BANK_NAME']) ?><br>
                        Account Name: <?= htmlspecialchars($po_details['BANK_ACCOUNT_NAME']) ?><br>
                        Account No.: <?= htmlspecialchars($po_details['BANK_ACCOUNT_NUMBER']) ?>
                    <?php endif; ?>
                </td>
                <td class="party-box" style="text-align:left">
                    <div class="party-title">Ship To:</div>
                    <strong>Ma. Anjeli S. Cadiz-Baena</strong><br>
                    Encore Leasing & Finance Corp. Brgy. Dicarma<br>
                    Maharlika Highway, Cabanatuan City, N.E. 3100<br>
                    Phone: 044 940-5625
                </td>
            </tr></table>
            <!-- Terms/Delivery Detail Table -->
            <table class="details-table">
                <tr>
                    <th>Terms</th>
                    <th>F.O.B.</th>
                    <th>Delivery Note</th>
                    <th>Requested By</th>
                    <th>Req. No.</th>
                </tr>
                <tr>
                    <td><?= htmlspecialchars($po_details['TERMS']) ?></td>
                    <td><?= htmlspecialchars($po_details['FOB']) ?></td>
                    <td><?= htmlspecialchars($po_details['DELIVERY_NOTE']) ?></td>
                    <td><?= htmlspecialchars($po_details['REQUESTED_BY']) ?></td>
                    <td><?= htmlspecialchars($po_details['REQ_NO']) ?></td>
                </tr>
            </table>
            <!-- Item Table -->
            <table class="items-table" style="margin-top:18px;">
                <thead>
                    <tr>
                        <th style="width:5%;">Qty</th>
                        <th style="width:45%;">Description</th>
                        <th style="width:12%;">Delivery</th>
                        <th style="width:14%;">Unit Price</th>
                        <th style="width:14%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $line_number = 1;
                    foreach ($po_items as $item) {
                        $total_price = $item['QUANTITY'] * $item['UNIT_PRICE'];
                        echo '<tr>
                                <td style="text-align: center;">'. number_format($item['QUANTITY']) .' '. htmlspecialchars($item['UNIT']) .'</td>
                                <td>'. htmlspecialchars($item['ITEM_DESCRIPTION']) .'</td>
                                <td style="text-align: center;">'. date('m/d/Y', strtotime($po_details['DELIVERY_DATE'])) .'</td>
                                <td style="text-align:right;">'. number_format($item['UNIT_PRICE'], 2) .'</td>
                                <td style="text-align:right;">'. number_format($total_price, 2) .'</td>
                            </tr>';
                        $line_number++;
                    }
                    while ($line_number <= 6) { // Adjust the number of blank rows as needed
                        echo '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>';
                        $line_number++;
                    }
                    ?>
                </tbody>
            </table>
            <!-- Summary block below items -->
            <table class="summary-block">
                <tr><td style="text-align:left;">Gross Amount</td><td style="text-align:right;"><?= number_format($po_details['GROSS_AMOUNT'],2) ?></td></tr>
                <tr><td style="text-align:left;">Withholding Tax (<?= number_format($po_details['WITHHOLDING_TAX_RATE'],2) ?>%)</td><td style="text-align:right;">(<?= number_format($po_details['WITHHOLDING_TAX_AMOUNT'],2) ?>)</td></tr>
                <tr><td style="text-align:left;">VAT (<?= number_format($po_details['VAT_TAX_RATE'],2) ?>%)</td><td style="text-align:right;"><?= number_format($po_details['VAT_TAX_AMOUNT'],2) ?></td></tr>
                <tr><td style="text-align:left;"><b>NET AMOUNT</b></td><td style="text-align:right;"><?= number_format($po_details['NET_AMOUNT'], 2) ?></td></tr>
            </table>
            <div class="notes">
                <ol style="margin:0 1.2em 0 1.2em;">
                    <li>We reserve the right to cancel this order upon supplier's failure to meet required delivery date.</li>
                    <li>Items will be returned at supplier's expense if not according to specifications.</li>
                    <li>Copy of delivery receipt/Invoice should be provided for each delivery made.</li>
                </ol>
            </div>
            <!-- Signatures bottom -->
            <table class="signatures"><tr>
                <td>
                    <div class="signature-line"><b>Conforme:</b> <?= htmlspecialchars($po_details['CONFORME_SUPPLIER']) ?><br>
                    <span style="font-weight:400;">(Supplier Name &amp; Signature)</span></div>
                </td>
                <td>
                    <div class="signature-line">Ms. Maria Anjeli S. Cadiz-Baena<br>
                    Assistant General Manager/OFA</div>
                </td>
                <td>
                    <div class="signature-line">Atty. Jose Enrique S. Cadiz<br>
                    General Manager</div>
                </td>
            </tr></table>
            <div class="footer-note">Accounting Copy</div>
        </div>
    </div>
</body>
</html>