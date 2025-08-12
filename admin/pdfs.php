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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_pdf') {
        $pdfData = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'file_url' => sanitizeInput($_POST['file_url']),
            'file_size' => intval($_POST['file_size']),
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (addPDF($pdfData)) {
            $message = 'PDF added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding PDF';
            $messageType = 'error';
        }
    }
    
    if ($action === 'update_pdf') {
        $pdfId = intval($_POST['pdf_id']);
        
        try {
            $stmt = $pdo->prepare("UPDATE pdfs SET title = ?, description = ?, file_url = ?, file_size = ?, status = ? WHERE id = ?");
            if ($stmt->execute([
                sanitizeInput($_POST['title']),
                sanitizeInput($_POST['description']),
                sanitizeInput($_POST['file_url']),
                intval($_POST['file_size']),
                $_POST['status'],
                $pdfId
            ])) {
                $message = 'PDF updated successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating PDF';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error updating PDF';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_pdf') {
        $pdfId = intval($_POST['pdf_id']);
        
        try {
            $stmt = $pdo->prepare("DELETE FROM pdfs WHERE id = ?");
            if ($stmt->execute([$pdfId])) {
                $message = 'PDF deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting PDF';
                $messageType = 'error';
            }
        } catch (PDOException $e) {
            $message = 'Error deleting PDF';
            $messageType = 'error';
        }
    }
}

// Get all PDFs
$pdfs = getPDFs('all');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Downloads - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-file-pdf"></i> PDF Downloads Management</h1>
            <button class="btn btn-primary" onclick="showAddPdfModal()">
                <i class="fas fa-plus"></i> Add PDF
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>PDF Downloads (Max 5 PDFs, 2MB each)</h3>
                <p>Current PDFs: <?php echo count($pdfs); ?>/5</p>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>File Size</th>
                                <th>Downloads</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pdfs as $pdf): ?>
                            <tr>
                                <td>
                                    <div class="pdf-title">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                        <?php echo htmlspecialchars($pdf['title']); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars(substr($pdf['description'] ?: '', 0, 100)); ?></td>
                                <td>
                                    <?php 
                                    $sizeKB = $pdf['file_size'] ? round($pdf['file_size'] / 1024, 1) : 0;
                                    echo $sizeKB . ' KB';
                                    ?>
                                </td>
                                <td><?php echo number_format($pdf['download_count']); ?></td>
                                <td>
                                    <span class="status status-<?php echo $pdf['status']; ?>">
                                        <?php echo ucfirst($pdf['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" onclick="previewPdf('<?php echo htmlspecialchars($pdf['file_url']); ?>')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="editPdf(<?php echo $pdf['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deletePdf(<?php echo $pdf['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Add PDF Modal -->
    <div id="addPdfModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New PDF</h3>
                <button class="close-modal" onclick="closeModal('addPdfModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_pdf">
                
                <div class="form-group">
                    <label>PDF Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>PDF File URL</label>
                    <input type="url" name="file_url" required>
                    <small>Upload your PDF file and enter the URL here. Max size: 2MB</small>
                </div>
                
                <div class="form-group">
                    <label>File Size (bytes)</label>
                    <input type="number" name="file_size" max="2097152" required>
                    <small>Maximum 2MB (2,097,152 bytes)</small>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add PDF</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addPdfModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit PDF Modal -->
    <div id="editPdfModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit PDF</h3>
                <button class="close-modal" onclick="closeModal('editPdfModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_pdf">
                <input type="hidden" name="pdf_id" id="editPdfId">
                
                <div class="form-group">
                    <label>PDF Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="3"></textarea>
                </div>
                
                <div class="form-group">
                    <label>PDF File URL</label>
                    <input type="url" name="file_url" id="editFileUrl" required>
                </div>
                
                <div class="form-group">
                    <label>File Size (bytes)</label>
                    <input type="number" name="file_size" id="editFileSize" max="2097152" required>
                </div>
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update PDF</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editPdfModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddPdfModal() {
            const currentCount = <?php echo count($pdfs); ?>;
            if (currentCount >= 5) {
                alert('Maximum 5 PDFs allowed. Please delete some PDFs first.');
                return;
            }
            document.getElementById('addPdfModal').style.display = 'block';
        }
        
        function editPdf(pdfId) {
            const pdfs = <?php echo json_encode($pdfs); ?>;
            const pdf = pdfs.find(p => p.id == pdfId);
            
            if (pdf) {
                document.getElementById('editPdfId').value = pdf.id;
                document.getElementById('editTitle').value = pdf.title;
                document.getElementById('editDescription').value = pdf.description || '';
                document.getElementById('editFileUrl').value = pdf.file_url;
                document.getElementById('editFileSize').value = pdf.file_size || 0;
                document.getElementById('editStatus').value = pdf.status;
                
                document.getElementById('editPdfModal').style.display = 'block';
            }
        }
        
        function deletePdf(pdfId) {
            if (confirm('Are you sure you want to delete this PDF?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_pdf">
                    <input type="hidden" name="pdf_id" value="${pdfId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function previewPdf(fileUrl) {
            window.open(fileUrl, '_blank');
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
        .pdf-title {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .text-red-500 {
            color: #ef4444;
        }
    </style>
</body>
</html>