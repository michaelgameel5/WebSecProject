<?php
require_once 'config/database.php';
require_once 'classes/Cart.php';
require_once 'classes/Card.php';
require_once 'classes/Voucher.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart = new Cart($db);
$card = new Card($db);
$voucher = new Voucher($db);

$cartItems = $cart->getCartItems($_SESSION['user_id']);
$total = $cart->getCartTotal($_SESSION['user_id']);
$cards = $card->getCardsByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart->updateQuantity($_SESSION['user_id'], $_POST['item_id'], $_POST['quantity']);
        header('Location: cart.php');
        exit();
    }
    
    if (isset($_POST['remove_item'])) {
        $cart->removeItem($_SESSION['user_id'], $_POST['item_id']);
        header('Location: cart.php');
        exit();
    }
    
    if (isset($_POST['apply_voucher'])) {
        $voucherData = $voucher->validateVoucher($_POST['voucher_code']);
        if ($voucherData) {
            $total -= $voucherData['amount'];
            $voucher->markVoucherAsUsed($_POST['voucher_code']);
            header('Location: cart.php?voucher_applied=1');
            exit();
        } else {
            $error = "Invalid or expired voucher code";
        }
    }
    
    if (isset($_POST['buy'])) {
        if (empty($_POST['card_id'])) {
            $error = "Please select a payment card";
        } else {
            // Process the purchase
            try {
                $db->beginTransaction();
                
                // Deduct from card balance
                $card->updateCardBalance($_POST['card_id'], -$total);
                
                // Clear the cart
                $cart->clearCart($_SESSION['user_id']);
                
                $db->commit();
                header('Location: cart.php?purchase_success=1');
                exit();
            } catch (Exception $e) {
                $db->rollBack();
                $error = "Error processing purchase: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Shopping Cart</h2>
        
        <?php if (isset($_GET['purchase_success'])): ?>
            <div class="alert alert-success">Purchase completed successfully!</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['voucher_applied'])): ?>
            <div class="alert alert-success">Voucher applied successfully!</div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info">Your cart is empty</div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-8">
                    <!-- Cart Items -->
                    <?php foreach ($cartItems as $item): ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="card-title"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p class="card-text">Price: $<?php echo number_format($item['price'], 2); ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <form method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                   class="form-control form-control-sm" min="1" style="width: 70px;">
                                            <button type="submit" name="update_quantity" class="btn btn-sm btn-outline-primary ms-2">
                                                Update
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <p class="card-text">
                                            Subtotal: $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </p>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" name="remove_item" class="btn btn-sm btn-danger">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="col-md-4">
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <h5>Total: $<?php echo number_format($total, 2); ?></h5>
                            
                            <!-- Voucher Form -->
                            <form method="POST" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="voucher_code" class="form-control" placeholder="Enter voucher code">
                                    <button type="submit" name="apply_voucher" class="btn btn-outline-primary">Apply</button>
                                </div>
                            </form>
                            
                            <!-- Payment Form -->
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="card_id" class="form-label">Select Payment Card</label>
                                    <select name="card_id" id="card_id" class="form-select" required>
                                        <option value="">Choose a card...</option>
                                        <?php foreach ($cards as $card): ?>
                                            <option value="<?php echo $card['id']; ?>">
                                                Card ending in <?php echo substr($card['card_number'], -4); ?> 
                                                (Balance: $<?php echo number_format($card['credit_balance'], 2); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <button type="submit" name="buy" class="btn btn-primary w-100">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 