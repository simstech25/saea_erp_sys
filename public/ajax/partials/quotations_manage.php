<?php
// Safety check
if (!isset($quotations) || !is_object($quotations)) {
    echo "<p style='color:red'>Error: Quotations object not initialized</p>";
    return;
}

// Get all quotations with client info
$quotationsList = $quotations->getAllWithClients();
?>

<h2>Quotations Management</h2>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div>
        <button onclick="loadCreateQuotationForm()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px;">
            + New Quotation
        </button>
        <button onclick="loadRfqUploadForm()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
            📄 Upload RFQ
        </button>
    </div>
    
    <div>
        <select id="statusFilter" onchange="filterQuotations()" style="padding: 8px;">
            <option value="">All Status</option>
            <option value="RFQ Received">RFQ Received</option>
            <option value="Pending Processing">Pending Processing</option>
            <option value="Pending Approval">Pending Approval</option>
            <option value="Approved">Approved</option>
            <option value="Sent">Sent</option>
        </select>
    </div>
</div>

<table class="quotations-table" style="width: 100%; border-collapse: collapse;">
<thead>
<tr style="background: #f8f9fa;">
<th>Quote #</th>
<th>Client</th>
<th>RFQ File</th>
<th>Excel Quote</th>
<th>PDF Sent</th>
<th>Total Amount</th>
<th>Status</th>
<th>Created</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php if (!empty($quotationsList)): ?>
    <?php foreach ($quotationsList as $q): ?>
    <tr id="quote-row-<?= $q['id'] ?>" style="border-bottom: 1px solid #dee2e6;">
        <td style="padding: 10px;">
            <strong><?= $q['quotation_number'] ?></strong>
        </td>
        <td style="padding: 10px;">
            <?= $q['client_name'] ?? 'Client #' . $q['client_id'] ?>
        </td>
        <td style="padding: 10px;">
            <?php if ($q['rfq_file']): ?>
                <a href="<?= $q['rfq_file'] ?>" target="_blank" style="color: #007bff;">
                    📄 View RFQ
                </a>
            <?php else: ?>
                <span style="color: #6c757d;">No RFQ</span>
            <?php endif; ?>
        </td>
        <td style="padding: 10px;">
            <?php if ($q['excel_file']): ?>
                <a href="<?= $q['excel_file'] ?>" target="_blank" style="color: #28a745;">
                    📊 Excel Quote
                </a>
            <?php else: ?>
                <span style="color: #6c757d;">No Excel</span>
            <?php endif; ?>
        </td>
        <td style="padding: 10px;">
            <?php if ($q['pdf_file']): ?>
                <a href="<?= $q['pdf_file'] ?>" target="_blank" style="color: #dc3545;">
                    📑 PDF Sent
                </a>
            <?php else: ?>
                <span style="color: #6c757d;">No PDF</span>
            <?php endif; ?>
        </td>
        <td style="padding: 10px;">
            <?= $q['currency'] ?> <?= number_format($q['total_price'] ?? 0, 2) ?>
        </td>
        <td style="padding: 10px;">
            <span class="status-badge status-<?= str_replace(' ', '-', strtolower($q['status'])) ?>">
                <?= $q['status'] ?>
            </span>
        </td>
        <td style="padding: 10px;">
            <?= date('d/m/Y', strtotime($q['created_at'])) ?>
        </td>
        <td style="padding: 10px;">
            <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                <?php if (in_array($q['status'], ['RFQ Received', 'Pending Processing'])): ?>
                    <button onclick="uploadExcelQuote(<?= $q['id'] ?>)" 
                            style="padding: 5px 10px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        Upload Excel
                    </button>
                <?php endif; ?>
                
                <?php if ($q['excel_file'] && $q['status'] == 'Pending Processing'): ?>
                    <button onclick="submitForApproval(<?= $q['id'] ?>)" 
                            style="padding: 5px 10px; background: #ffc107; color: black; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        Send for Approval
                    </button>
                <?php endif; ?>
                
                <?php if ($q['status'] == 'Pending Approval' && $_SESSION['role'] == 'admin'): ?>
                    <button onclick="approveQuote(<?= $q['id'] ?>)" 
                            style="padding: 5px 10px; background: #17a2b8; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        Approve
                    </button>
                <?php endif; ?>
                
                <?php if ($q['status'] == 'Approved'): ?>
                    <button onclick="uploadPdfQuote(<?= $q['id'] ?>)" 
                            style="padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        Upload PDF & Mark Sent
                    </button>
                <?php endif; ?>
                
                <button onclick="viewQuote(<?= $q['id'] ?>)" 
                        style="padding: 5px 10px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                    View
                </button>
                
                <?php if (in_array($q['status'], ['RFQ Received', 'Pending Processing'])): ?>
                    <button onclick="deleteQuote(<?= $q['id'] ?>)" 
                            style="padding: 5px 10px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer; font-size: 12px;">
                        Delete
                    </button>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="9" style="text-align: center; padding: 40px; color: #666;">
            <p>No quotations found</p>
            <p>Click "New Quotation" to start or "Upload RFQ" to process a client request</p>
        </td>
    </tr>
<?php endif; ?>
</tbody>
</table>

<!-- Status Badge CSS -->
<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
    display: inline-block;
}
.status-rfq-received { background: #6f42c1; color: white; }
.status-pending-processing { background: #fd7e14; color: white; }
.status-pending-approval { background: #ffc107; color: black; }
.status-approved { background: #28a745; color: white; }
.status-sent { background: #17a2b8; color: white; }
</style>

<!-- JavaScript Functions -->
<script>
function loadCreateQuotationForm() {
    fetch('ajax/load.php?page=create_quotation')
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        });
}

function loadRfqUploadForm() {
    fetch('ajax/load.php?page=upload_rfq')
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        });
}

function uploadExcelQuote(id) {
    fetch('ajax/load.php?page=upload_excel&id=' + id)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        });
}

function uploadPdfQuote(id) {
    fetch('ajax/load.php?page=upload_pdf&id=' + id)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        });
}

function submitForApproval(id) {
    if (confirm('Send this quotation for approval?')) {
        fetch('ajax/quotations_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'submit_approval', id: id})
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                loadPage('quotations'); // Reload
            }
        });
    }
}

function approveQuote(id) {
    if (confirm('Approve this quotation?')) {
        fetch('ajax/quotations_action.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'approve', id: id})
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                loadPage('quotations'); // Reload
            }
        });
    }
}

function viewQuote(id) {
    fetch('ajax/load.php?page=view_quotation&id=' + id)
        .then(response => response.text())
        .then(html => {
            document.getElementById('content').innerHTML = html;
        });
}

function filterQuotations() {
    const filter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.quotations-table tbody tr');
    
    rows.forEach(row => {
        if (!filter) {
            row.style.display = '';
            return;
        }
        
        const statusCell = row.querySelector('.status-badge');
        if (statusCell && statusCell.textContent.includes(filter)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>