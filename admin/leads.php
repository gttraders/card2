<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';
$messageType = '';

// Handle lead actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_status') {
        $leadId = intval($_POST['lead_id']);
        $status = $_POST['status'];
        
        try {
            $stmt = $pdo->prepare("UPDATE free_website_requests SET status = ? WHERE id = ?");
            if ($stmt->execute([$status, $leadId])) {
                $message = 'Lead status updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating lead status';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating lead status';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_lead') {
        $leadId = intval($_POST['lead_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM free_website_requests WHERE id = ?");
            if ($stmt->execute([$leadId])) {
                $message = 'Lead deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting lead';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting lead';
            $messageType = 'error';
        }
    }
}

// Get all free website requests (leads)
$stmt = $pdo->query("SELECT * FROM free_website_requests ORDER BY created_at DESC");
$leads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Website Leads - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-users"></i> Free Website Leads</h1>
            <div class="page-stats">
                <span class="stat-badge">Total Leads: <?php echo count($leads); ?></span>
                <span class="stat-badge pending">Pending: <?php echo count(array_filter($leads, fn($l) => $l['status'] === 'pending')); ?></span>
                <span class="stat-badge contacted">Contacted: <?php echo count(array_filter($leads, fn($l) => $l['status'] === 'contacted')); ?></span>
            </div>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>All Free Website Requests</h3>
                <div class="card-filters">
                    <select id="statusFilter" onchange="filterLeads()">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="contacted">Contacted</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Business Details</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leadsTableBody">
                            <?php foreach ($leads as $lead): ?>
                            <tr data-status="<?php echo $lead['status']; ?>">
                                <td>
                                    <div class="lead-name">
                                        <strong><?php echo htmlspecialchars($lead['name']); ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:<?php echo htmlspecialchars($lead['mobile']); ?>" class="phone-link">
                                        <i class="fas fa-phone"></i>
                                        <?php echo htmlspecialchars($lead['mobile']); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($lead['email']): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($lead['email']); ?>" class="email-link">
                                            <i class="fas fa-envelope"></i>
                                            <?php echo htmlspecialchars($lead['email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="business-details">
                                        <?php echo htmlspecialchars(substr($lead['business_details'] ?: 'No details provided', 0, 100)); ?>
                                        <?php if (strlen($lead['business_details'] ?: '') > 100): ?>...<?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status status-<?php echo $lead['status']; ?>">
                                        <?php echo ucfirst($lead['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y H:i', strtotime($lead['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-primary" onclick="viewLead(<?php echo $lead['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="contactLead('<?php echo htmlspecialchars($lead['mobile']); ?>', '<?php echo htmlspecialchars($lead['name']); ?>')">
                                            <i class="fab fa-whatsapp"></i>
                                        </button>
                                        <select onchange="updateLeadStatus(<?php echo $lead['id']; ?>, this.value)" class="status-select">
                                            <option value="pending" <?php echo $lead['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="contacted" <?php echo $lead['status'] === 'contacted' ? 'selected' : ''; ?>>Contacted</option>
                                            <option value="completed" <?php echo $lead['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        </select>
                                        <button class="btn btn-sm btn-danger" onclick="deleteLead(<?php echo $lead['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Lead Details Modal -->
    <div id="leadDetailsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Lead Details</h3>
                <button class="close-modal" onclick="closeModal('leadDetailsModal')">&times;</button>
            </div>
            <div id="leadDetailsContent">
                <!-- Lead details will be loaded here -->
            </div>
        </div>
    </div>
    
    <script>
        function updateLeadStatus(leadId, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="lead_id" value="${leadId}">
                <input type="hidden" name="status" value="${status}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
        
        function deleteLead(leadId) {
            if (confirm('Are you sure you want to delete this lead?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_lead">
                    <input type="hidden" name="lead_id" value="${leadId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function viewLead(leadId) {
            const leads = <?php echo json_encode($leads); ?>;
            const lead = leads.find(l => l.id == leadId);
            
            if (lead) {
                document.getElementById('leadDetailsContent').innerHTML = `
                    <div class="lead-details">
                        <div class="detail-row">
                            <strong>Name:</strong> ${lead.name}
                        </div>
                        <div class="detail-row">
                            <strong>Mobile:</strong> 
                            <a href="tel:${lead.mobile}">${lead.mobile}</a>
                        </div>
                        <div class="detail-row">
                            <strong>Email:</strong> 
                            ${lead.email ? `<a href="mailto:${lead.email}">${lead.email}</a>` : 'Not provided'}
                        </div>
                        <div class="detail-row">
                            <strong>Status:</strong> 
                            <span class="status status-${lead.status}">${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}</span>
                        </div>
                        <div class="detail-row">
                            <strong>Request Date:</strong> ${new Date(lead.created_at).toLocaleString()}
                        </div>
                        <div class="detail-row">
                            <strong>Business Details:</strong>
                            <div class="business-details-full">
                                ${lead.business_details || 'No details provided'}
                            </div>
                        </div>
                        <div class="detail-actions">
                            <button class="btn btn-success" onclick="contactLead('${lead.mobile}', '${lead.name}')">
                                <i class="fab fa-whatsapp"></i> Contact via WhatsApp
                            </button>
                            ${lead.email ? `<button class="btn btn-primary" onclick="window.open('mailto:${lead.email}')">
                                <i class="fas fa-envelope"></i> Send Email
                            </button>` : ''}
                        </div>
                    </div>
                `;
                
                document.getElementById('leadDetailsModal').style.display = 'block';
            }
        }
        
        function contactLead(mobile, name) {
            const message = `Hi ${name}! Thank you for your interest in our free website service. I'd like to discuss your requirements. When would be a good time to talk?`;
            const whatsappUrl = `https://wa.me/${mobile.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(message)}`;
            window.open(whatsappUrl, '_blank');
        }
        
        function filterLeads() {
            const filter = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#leadsTableBody tr');
            
            rows.forEach(row => {
                if (filter === '' || row.dataset.status === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    </script>
    
    <style>
        .page-stats {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .stat-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            background: #f3f4f6;
            color: #374151;
        }
        
        .stat-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }
        
        .stat-badge.contacted {
            background: #dbeafe;
            color: #2563eb;
        }
        
        .lead-name strong {
            color: #1f2937;
        }
        
        .phone-link, .email-link {
            color: #3b82f6;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .phone-link:hover, .email-link:hover {
            text-decoration: underline;
        }
        
        .business-details {
            max-width: 200px;
            word-wrap: break-word;
            line-height: 1.4;
        }
        
        .text-muted {
            color: #6b7280;
            font-style: italic;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .status-select {
            padding: 4px 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .lead-details {
            padding: 20px;
        }
        
        .detail-row {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .business-details-full {
            margin-top: 10px;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            line-height: 1.6;
        }
        
        .detail-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
    </style>
</body>
</html>