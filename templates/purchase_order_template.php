<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order <?= htmlspecialchars($po_details['PURCHASE_ORDER_ID']) ?></title>
    <style>
    /* Folio Paper Size Definition for Print */
    @page {
        size: 8.5in 13in; /* Folio size (215.9mm x 330.2mm) */
        margin: 0.4in 0.5in; /* Optimized margins for content (approx 1cm top/bottom, 1.25cm left/right) */
    }
    :root {
        --brand-blue: #4B6CB7;
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
        margin: 0;
        background: #f5f8fa;
    }
    .page-wrapper {
        margin: 0 auto;
        background: #f5f8fa;
    }
    .container {
        background: #fff;
        max-width: 830px; /* For screen display */
        margin: 30px auto; /* For screen display */
        border-radius: 10px;
        padding: 22px 26px 12px 26px; /* For screen display */
        box-shadow: 0 10px 25px #4b6cb724;
        box-sizing: border-box;
        position: relative;
        page-break-after: always; /* Ensure each .container (representing a logical page) starts on a new physical page */
    }
    /* Specific style for the last container (the very last page of the entire document) */
    .container:last-of-type {
        margin-bottom: 0;
        box-shadow: none;
        page-break-after: auto; /* No page break after the very last container */
    }

    /* Header logo and address row */
    .header-row { width: 100%; margin-bottom: 10px; table-layout: fixed; }
    .header-logo { width: 110px; vertical-align: top; }
    .header-details {
        padding-left: 16px;
        font-size: 1.05em;
        color: var(--brand-dark);
        width: 40%; /* Adjust width to make space for PO meta */
    }
    .header-details b { font-size: 1.13em; color: var(--brand-blue); }
    /* PO header right */
    .po-meta {
        background: var(--brand-blue);
        color: #fff;
        border-radius: 7px;
        text-align: right;
        padding: 18px 24px 10px 14px;
        font-weight: 400;
        width: 320px; /* Fixed width */
        vertical-align: top;
    }
    .po-meta h1 { margin: 0; font-weight: 700; font-size: 1.5em; letter-spacing: 0.09em; }
    .po-meta .meta-row { font-size: .98em; margin-top: 5px; }
    .po-meta .meta-label { color: #dbe3f7; font-size: .94em; }
    .po-meta .po-id {
        display: inline-block; background: #fff; color: var(--brand-blue); font-weight: bold;
        border-radius: 17px; padding: 2.5px 18px; margin-left: 9px;
        border: 1.5px solid #537edf;
        font-size: 1.06em;
        box-shadow: 0 2px 6px #4b6cb72c;
    }

    /* Two columns: Supplier / ShipTo */
    .parties-row { width: 100%; margin-top: 18px; table-layout: fixed; }
    .party-box {
        background: #f8fafe;
        border: 1px solid #e0e4ed;
        border-radius: 6px;
        padding: 12px 16px 11px 16px;
        width: 48%; /* Adjust width to fit two columns */
        vertical-align: top;
        line-height: 1.7;
        font-size: 1em;
    }
    .party-box strong { color: var(--brand-blue); }
    .party-title { color: var(--brand-blue); font-weight: 700; }

    /* Details table */
    .details-table { width: 100%; margin-top: 18px; border-collapse: collapse; font-size: 1em; }
    .details-table th, .details-table td {
        border: 1px solid #e9e9ef;
        padding: 8px 6px; text-align: center;
    }
    .details-table th {
        background: var(--table-hdr-bg);
        color: var(--brand-blue);
        font-weight: 600;
    }

    /* Items Table */
    .items-table {
        width: 100%; margin-top: 15px; /* Slightly reduced margin */
        border-collapse: collapse; font-size: 1.07em;
        border-radius: 5px; overflow: hidden;
    }
    .items-table thead th {
        background: var(--table-hdr-bg);
        color: var(--brand-dark); font-weight: 700;
        border-bottom: 2px solid var(--brand-blue);
    }
    .items-table td, .items-table th {
        border: 1px solid #dde4f2;
        padding: 7px 6px; /* Slightly reduced padding */
    }
    .items-table tbody tr { height: 30px; } /* Consistent row height for screen layout */

    /* Summary block below items */
    .summary-block {
        width: 55%; margin: 12px 0 0 auto; /* Slightly reduced margin */
        background: var(--summary-bg);
        border-radius: 7px; border: 1px solid #d3d9e8;
        font-size: 1.05em;
        page-break-inside: avoid; /* Prevent breaking this block across pages */
    }
    .summary-block td { border: none; padding: 7px 13px; }
    .summary-block tr:not(:last-child) td { border-bottom: 1px solid #e5e9f7; }
    .summary-block tr:last-child td { font-weight: bold; color: var(--brand-blue); }

    .notes {
        margin-top: 12px; margin-bottom: 8px; /* Slightly reduced margin */
        font-size: .96em; color: #444;
        background: #f5faff; border-left: 3px solid var(--brand-blue);
        padding: 7px 17px 4px 17px; border-radius: 5px; /* Slightly reduced padding */
        page-break-inside: avoid; /* Prevent breaking this block across pages */
    }

    .signatures {
        margin-top: 20px; /* Reduced margin for tighter fit */
        width: 100%; text-align: center;
        border-spacing: 0; table-layout: fixed;
        page-break-inside: avoid; /* Prevent breaking this block across pages */
    }
    .signatures td { width: 50%; vertical-align: bottom; }
    .signature-line {
        display: inline-block; border-top: 2px solid var(--brand-blue); min-width: 170px;
        margin-top: 12px; padding-top: 6px; /* Slightly reduced margin/padding */
        font-size: .96em; font-weight: 500; color: #223458;
    }

    /* Copy designation footer */
    .footer-note {
        margin-top: 18px; /* Reduced margin */
        font-size: .96em; color: #4b6cb7;
        text-align: center; letter-spacing: .08em;
        page-break-inside: avoid; /* Prevent breaking this block across pages */
    }

    /* Print-specific optimizations */
    @media print {
        body, .page-wrapper {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            /* In print, body/wrapper will automatically conform to @page size if not explicitly set */
        }
        .container {
            /* Override screen specific styles for print */
            max-width: none !important; /* Allow full width of the printable area */
            width: 100% !important;     /* Take 100% of the parent (which is body/page-wrapper within @page margins) */
            margin: 0 !important;        /* Remove screen margins */
            padding: 15px 20px 10px 20px !important; /* Optimized print padding for content */
            box-shadow: none !important; /* Remove shadows in print */
            border-radius: 0 !important; /* Remove rounded corners in print */
            min-height: auto !important; /* Allow height to adjust to content, not fixed */
            /* page-break-after: always; is already set by default for .container */
        }
        /* Ensure critical sections stay together on a page */
        .summary-block, .notes, .signatures, .footer-note {
            page-break-inside: avoid !important;
        }
        /* Ensure footer is visible (color change for monochrome print) */
        .footer-note { color: #000 !important; }
        /* Optimize table spacing for print (making rows more compact) */
        .items-table tbody tr { height: 28px !important; } /* Slightly more compact row height */
        .items-table td, .items-table th { padding: 5px 6px !important; } /* Tighter cell padding */
    }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <?php
        // Set timezone for date function to ensure consistency
        date_default_timezone_set('Asia/Manila');

        /**
         * Renders a single copy of the Purchase Order, handling multi-page items and conditional block rendering.
         *
         * @param array $po_details The main purchase order details.
         * @param array $po_items An array of item details.
         * @param string $copy_type The type of copy (e.g., 'Supplier', 'Accounting').
         */
        function render_po_copy($po_details, $po_items, $copy_type) {
            // Empirically determined max items that fit on the FIRST page WITH all static blocks (summary, notes, signatures).
            // This value is crucial for dense single-page outputs when item count is low.
            // Adjust this value based on actual print tests on Folio paper if content overflows or too much space remains.
            $MAX_ITEMS_ON_FIRST_PAGE_WITH_FOOTER = 13; // Set to 6 to match the example image's item count and try to fit everything.

            // Empirically determined max items that can fit on subsequent pages
            // (which only have the header and item table, thus more vertical space).
            $MAX_ITEMS_ON_CONTINUATION_PAGE = 10; // More items can fit here

            $total_items = count($po_items);
            $pages_to_render = [];
            $current_item_index = 0;
            $page_counter = 1;

            if ($total_items <= $MAX_ITEMS_ON_FIRST_PAGE_WITH_FOOTER) {
                // Scenario 1: All items and all static blocks fit on a single page
                $pages_to_render[] = [
                    'items' => $po_items,
                    'is_first_page' => true,
                    'is_last_page' => true,
                    'page_number' => 1
                ];
            } else {
                // Scenario 2: Items require multiple pages
                // 1. Render the first page: Contains fixed header blocks and first set of items
                $items_for_first_page = array_slice($po_items, $current_item_index, $MAX_ITEMS_ON_FIRST_PAGE_WITH_FOOTER);
                $pages_to_render[] = [
                    'items' => $items_for_first_page,
                    'is_first_page' => true,
                    'is_last_page' => false,
                    'page_number' => $page_counter++
                ];
                $current_item_index += count($items_for_first_page);

                // 2. Render subsequent pages: Only header and items
                while ($current_item_index < $total_items) {
                    $items_for_page = array_slice($po_items, $current_item_index, $MAX_ITEMS_ON_CONTINUATION_PAGE);
                    $pages_to_render[] = [
                        'items' => $items_for_page,
                        'is_first_page' => false,
                        'is_last_page' => false, // Will be updated for the very last page
                        'page_number' => $page_counter++
                    ];
                    $current_item_index += count($items_for_page);
                }

                // 3. Mark the very last page in this copy's sequence as the last page
                if (!empty($pages_to_render)) {
                    $pages_to_render[count($pages_to_render) - 1]['is_last_page'] = true;
                }
            }

            // Total pages for THIS specific copy (e.g., Supplier Copy might be 1 page, Accounting Copy might be 2 pages)
            $total_pages_for_copy = count($pages_to_render);

            foreach ($pages_to_render as $page_data) {
                $current_items = $page_data['items'];
                $is_first_page = $page_data['is_first_page'];
                $is_last_page = $page_data['is_last_page'];
                $page = $page_data['page_number'];
                ?>
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
                                <?= date('F d, Y'); ?>
                            </div>
                            <div class="meta-row" style="margin-top:6px;">
                                <span class="meta-label">Page Number</span> <?= $page ?> of <?= $total_pages_for_copy ?>
                            </div>
                        </td>
                    </tr></table>

                    <?php if ($is_first_page): ?>
                    <!-- Supplier & Ship To Block (only on the first page of each copy) -->
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

                    <!-- Terms/Delivery Detail Table (only on the first page of each copy) -->
                    <table class="details-table">
                        <tr>
                            <th>Terms</th><th>F.O.B.</th><th>Delivery Note</th><th>Requested By</th><th>Req. No.</th>
                        </tr>
                        <tr>
                            <td><?= htmlspecialchars($po_details['TERMS']) ?></td>
                            <td><?= htmlspecialchars($po_details['FOB']) ?></td>
                            <td><?= htmlspecialchars($po_details['DELIVERY_NOTE']) ?></td>
                            <td><?= htmlspecialchars($po_details['REQUESTED_BY']) ?></td>
                            <td><?= htmlspecialchars($po_details['REQ_NO']) ?></td>
                        </tr>
                    </table>
                    <?php endif; // End is_first_page conditional for header blocks ?>

                    <!-- Items Table (appears on all relevant pages) -->
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th style="width:8%;">Qty</th>
                                <th style="width:45%;">Description</th>
                                <th style="width:12%;">Delivery</th>
                                <th style="width:15%;">Unit Price</th>
                                <th style="width:15%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rendered_items_count = 0;
                            foreach ($current_items as $item) {
                                $total_price = $item['QUANTITY'] * $item['UNIT_PRICE'];
                                echo '<tr>
                                        <td style="text-align: center;">'. number_format($item['QUANTITY']) .' '. htmlspecialchars($item['UNIT']) .'</td>
                                        <td style="text-align: left;">'. htmlspecialchars($item['ITEM_DESCRIPTION']) .'</td>
                                        <td style="text-align: center;">'. date('m/d/Y', strtotime($po_details['DELIVERY_DATE'])) .'</td>
                                        <td style="text-align:right;">₱ '. number_format($item['UNIT_PRICE'], 2) .'</td>
                                        <td style="text-align:right;">₱ '. number_format($total_price, 2) .'</td>
                                    </tr>';
                                $rendered_items_count++;
                            }

                            // Fill remaining rows to ensure consistent table height for the current page type
                            $target_rows_on_current_page = $is_first_page ? $MAX_ITEMS_ON_FIRST_PAGE_WITH_FOOTER : $MAX_ITEMS_ON_CONTINUATION_PAGE;
                            while ($rendered_items_count < $target_rows_on_current_page) {
                                echo '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>';
                                $rendered_items_count++;
                            }
                            ?>
                        </tbody>
                    </table>

                    <?php if ($is_last_page): ?>
                        <!-- Summary block (only on the last page of each copy) -->
                        <table class="summary-block">
                            <tr><td style="text-align:left;">Gross Amount</td><td style="text-align:right;">₱ <?= number_format($po_details['GROSS_AMOUNT'],2) ?></td></tr>
                            <tr><td style="text-align:left;">Withholding Tax (<?= number_format($po_details['WITHHOLDING_TAX_RATE'],2) ?>%)</td><td style="text-align:right;">(₱ <?= number_format($po_details['WITHHOLDING_TAX_AMOUNT'],2) ?>)</td></tr>
                            <tr><td style="text-align:left;">VAT (<?= number_format($po_details['VAT_TAX_RATE'],2) ?>%)</td><td style="text-align:right;">₱ <?= number_format($po_details['VAT_TAX_AMOUNT'],2) ?></td></tr>
                            <tr><td style="text-align:left;"><b>NET AMOUNT</b></td><td style="text-align:right;"><b>₱ <?= number_format($po_details['NET_AMOUNT'], 2) ?></b></td></tr>
                        </table>

                        <div class="notes">
                            <ol style="margin:0 1.2em 0 1.2em;">
                                <li>We reserve the right to cancel this order upon supplier's failure to meet required delivery date.</li>
                                <li>Items will be returned at supplier's expense if not according to specifications.</li>
                                <li>Copy of delivery receipt/Invoice should be provided for each delivery made.</li>
                            </ol>
                        </div>

                        <!-- Signatures (only on the last page of each copy) -->
                        <table class="signatures"><tr>
                            <td>
                                <div class="signature-line"><b>Conforme:</b> <?= htmlspecialchars($po_details['CONFORME_SUPPLIER']) ?><br>
                                <span style="font-weight:400;">(Supplier Name &amp; Signature)</span></div>
                            </td>
                            <td>
                                <div class="signature-line"><b>Approver:</b> <?= htmlspecialchars($po_details['APPROVED_BY_ASSISTANT_GM']) ?><br>
                                <span style="font-weight:400;">(Signature)</span></div>
                            </td>
                        </tr></table>

                        <div class="footer-note"><?= htmlspecialchars($copy_type) ?> Copy</div>
                    <?php endif; // End is_last_page conditional for footer blocks ?>
                </div>
                <?php
            }
        }

        // Render both copies: Supplier and Accounting
        render_po_copy($po_details, $po_items, 'Supplier');
        render_po_copy($po_details, $po_items, 'Accounting');
        ?>
    </div>
</body>
</html>