<?php
require_once 'config/database.php';
require_once 'classes/Card.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$card = new Card($db);
$cards = $card->getCardsByUserId($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_card'])) {
        try {
            $card->addCard(
                $_SESSION['user_id'],
                $_POST['card_number'],
                $_POST['expiry_date'],
                $_POST['cvv'],
                [
                    'street_address' => $_POST['street_address'],
                    'city' => $_POST['city'],
                    'state' => $_POST['state'],
                    'postal_code' => $_POST['postal_code'],
                    'country' => $_POST['country']
                ]
            );
            header('Location: cards.php?success=1');
            exit();
        } catch (Exception $e) {
            $error = "Error adding card: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Cards</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Your Cards</h2>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">Card added successfully!</div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Add New Card Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Card</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="card_number">Card Number</label>
                            <input type="text" class="form-control" id="card_number" name="card_number" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cvv">CVV</label>
                            <input type="text" class="form-control" id="cvv" name="cvv" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="street_address">Street Address</label>
                        <input type="text" class="form-control" id="street_address" name="street_address" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="country">Country</label>
                        <input type="text" class="form-control" id="country" name="country" required>
                    </div>
                    
                    <button type="submit" name="add_card" class="btn btn-primary">Add Card</button>
                </form>
            </div>
        </div>

        <!-- Existing Cards -->
        <h4>Your Cards</h4>
        <div class="row">
            <?php foreach ($cards as $card): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card ending in <?php echo substr($card['card_number'], -4); ?></h5>
                            <p class="card-text">
                                Balance: $<?php echo number_format($card['credit_balance'], 2); ?><br>
                                Expires: <?php echo date('m/Y', strtotime($card['expiry_date'])); ?><br>
                                Address: <?php echo htmlspecialchars($card['street_address']); ?><br>
                                <?php echo htmlspecialchars($card['city']); ?>, <?php echo htmlspecialchars($card['state']); ?> <?php echo htmlspecialchars($card['postal_code']); ?>
                            </p>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="card_id" value="<?php echo $card['id']; ?>">
                                <button type="submit" name="deactivate_card" class="btn btn-danger btn-sm">Deactivate</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 