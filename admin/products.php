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
    
    if ($action === 'add_product') {
        $productData = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'price' => floatval($_POST['price']),
            'discount_price' => !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null,
            'qty_stock' => intval($_POST['qty_stock']),
            'image_url' => sanitizeInput($_POST['image_url']),
            'inquiry_only' => isset($_POST['inquiry_only']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (addProduct($productData)) {
            $message = 'Product added successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error adding product';
            $messageType = 'error';
        }
    }
    
    if ($action === 'update_product') {
        $productId = intval($_POST['product_id']);
        $productData = [
            'title' => sanitizeInput($_POST['title']),
            'description' => sanitizeInput($_POST['description']),
            'price' => floatval($_POST['price']),
            'discount_price' => !empty($_POST['discount_price']) ? floatval($_POST['discount_price']) : null,
            'qty_stock' => intval($_POST['qty_stock']),
            'image_url' => sanitizeInput($_POST['image_url']),
            'inquiry_only' => isset($_POST['inquiry_only']) ? 1 : 0,
            'status' => $_POST['status'] ?? 'active'
        ];
        
        if (updateProduct($productId, $productData)) {
            $message = 'Product updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error updating product';
            $messageType = 'error';
        }
    }
    
    if ($action === 'delete_product') {
        $productId = intval($_POST['product_id']);
        if (deleteProduct($productId)) {
            $message = 'Product deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Error deleting product';
            $messageType = 'error';
        }
    }
}

// Get all products
$products = getProducts('all');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/sidebar.php'; ?>
    
    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-box"></i> Products Management</h1>
            <button class="btn btn-primary" onclick="showAddProductModal()">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h3>All Products</h3>
            </div>
            <div class="card-content">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="Product" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td><?php echo htmlspecialchars($product['title']); ?></td>
                                <td>
                                    <?php if ($product['discount_price']): ?>
                                        <span class="original-price">₹<?php echo number_format($product['price']); ?></span>
                                        <span class="discount-price">₹<?php echo number_format($product['discount_price']); ?></span>
                                    <?php else: ?>
                                        ₹<?php echo number_format($product['price']); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $product['qty_stock']; ?></td>
                                <td>
                                    <span class="status status-<?php echo $product['status']; ?>">
                                        <?php echo ucfirst($product['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editProduct(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(<?php echo $product['id']; ?>)">
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
    
    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Product</h3>
                <button class="close-modal" onclick="closeModal('addProductModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_product">
                
                <div class="form-group">
                    <label>Product Title</label>
                    <input type="text" name="title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Discount Price (₹)</label>
                        <input type="number" name="discount_price" step="0.01">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="qty_stock" value="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="inquiry_only">
                        Inquiry Only (No Add to Cart)
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addProductModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Product</h3>
                <button class="close-modal" onclick="closeModal('editProductModal')">&times;</button>
            </div>
            <form method="POST" id="editProductForm">
                <input type="hidden" name="action" value="update_product">
                <input type="hidden" name="product_id" id="editProductId">
                
                <div class="form-group">
                    <label>Product Title</label>
                    <input type="text" name="title" id="editTitle" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="editDescription" rows="3"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Price (₹)</label>
                        <input type="number" name="price" id="editPrice" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Discount Price (₹)</label>
                        <input type="number" name="discount_price" id="editDiscountPrice" step="0.01">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock Quantity</label>
                        <input type="number" name="qty_stock" id="editStock">
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="editStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Image URL</label>
                    <input type="url" name="image_url" id="editImageUrl" required>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="inquiry_only" id="editInquiryOnly">
                        Inquiry Only (No Add to Cart)
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editProductModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function showAddProductModal() {
            document.getElementById('addProductModal').style.display = 'block';
        }
        
        function editProduct(productId) {
            // Fetch product data and populate modal
            fetch(`../api/get-product.php?id=${productId}`)
                .then(response => response.json())
                .then(product => {
                    document.getElementById('editProductId').value = product.id;
                    document.getElementById('editTitle').value = product.title;
                    document.getElementById('editDescription').value = product.description || '';
                    document.getElementById('editPrice').value = product.price;
                    document.getElementById('editDiscountPrice').value = product.discount_price || '';
                    document.getElementById('editStock').value = product.qty_stock;
                    document.getElementById('editStatus').value = product.status;
                    document.getElementById('editImageUrl').value = product.image_url;
                    document.getElementById('editInquiryOnly').checked = product.inquiry_only == 1;
                    
                    document.getElementById('editProductModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error fetching product:', error);
                    alert('Error loading product data');
                });
        }
        
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_product">
                    <input type="hidden" name="product_id" value="${productId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
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
</body>
</html>