<?php
require_once '../config/database.php';
require_once '../classes/Voucher.php';

session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_employee']) || !$_SESSION['is_employee']) {
    header('Location: ../login.php');
    exit();
}

$voucher = new Voucher($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_voucher'])) {
        try {
            $expiryDate = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : null;
            $voucher->createVoucher($_SESSION['user_id'], $_POST['amount'], $expiryDate);
            header('Location: vouchers.php?success=1');
            exit();
        } catch (Exception $e) {
            $error = "Error creating voucher: " . $e->getMessage();
        }
    }
}

// Get all vouchers created by this employee
$stmt = $db->prepare("
    SELECT * FROM vouchers 
    WHERE created_by = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$vouchers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vouchers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Vouchers</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Voucher created successfully!</div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Create Voucher Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Create New Voucher</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount ($)</label>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="expiry_date" class="form-label">Expiry Date (Optional)</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                        </div>
                    </div>
                    <button type="submit" name="create_voucher" class="btn btn-primary">Create Voucher</button>
                </form>
            </div>
        </div>

        <!-- Vouchers List -->
        <h4>Your Vouchers</h4>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Expires</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vouchers as $v): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v['code']); ?></td>
                            <td>$<?php echo number_format($v['amount'], 2); ?></td>
                            <td>
                                <?php if ($v['is_used']): ?>
                                    <span class="badge bg-secondary">Used</span>
                                <?php elseif ($v['expires_at'] && strtotime($v['expires_at']) < time()): ?>
                                    <span class="badge bg-danger">Expired</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($v['created_at'])); ?></td>
                            <td>
                                <?php echo $v['expires_at'] ? date('Y-m-d', strtotime($v['expires_at'])) : 'Never'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 