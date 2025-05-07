<?php
class Cart {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getOrCreateCart($userId) {
        $stmt = $this->db->prepare("
            SELECT id FROM cart 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cart) {
            $stmt = $this->db->prepare("
                INSERT INTO cart (user_id)
                VALUES (?)
            ");
            $stmt->execute([$userId]);
            return $this->db->lastInsertId();
        }
        
        return $cart['id'];
    }
    
    public function addToCart($userId, $productId, $quantity) {
        $cartId = $this->getOrCreateCart($userId);
        
        // Check if product already in cart
        $stmt = $this->db->prepare("
            SELECT id, quantity FROM cart_items 
            WHERE cart_id = ? AND product_id = ?
        ");
        $stmt->execute([$cartId, $productId]);
        $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existingItem) {
            // Update quantity
            $stmt = $this->db->prepare("
                UPDATE cart_items 
                SET quantity = quantity + ?
                WHERE id = ?
            ");
            return $stmt->execute([$quantity, $existingItem['id']]);
        } else {
            // Add new item
            $stmt = $this->db->prepare("
                INSERT INTO cart_items (cart_id, product_id, quantity)
                VALUES (?, ?, ?)
            ");
            return $stmt->execute([$cartId, $productId, $quantity]);
        }
    }
    
    public function getCartItems($userId) {
        $cartId = $this->getOrCreateCart($userId);
        
        $stmt = $this->db->prepare("
            SELECT ci.*, p.name, p.price
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateQuantity($userId, $itemId, $quantity) {
        $cartId = $this->getOrCreateCart($userId);
        
        $stmt = $this->db->prepare("
            UPDATE cart_items 
            SET quantity = ?
            WHERE id = ? AND cart_id = ?
        ");
        return $stmt->execute([$quantity, $itemId, $cartId]);
    }
    
    public function removeItem($userId, $itemId) {
        $cartId = $this->getOrCreateCart($userId);
        
        $stmt = $this->db->prepare("
            DELETE FROM cart_items 
            WHERE id = ? AND cart_id = ?
        ");
        return $stmt->execute([$itemId, $cartId]);
    }
    
    public function clearCart($userId) {
        $cartId = $this->getOrCreateCart($userId);
        
        $stmt = $this->db->prepare("
            DELETE FROM cart_items 
            WHERE cart_id = ?
        ");
        return $stmt->execute([$cartId]);
    }
    
    public function getCartTotal($userId) {
        $items = $this->getCartItems($userId);
        $total = 0;
        
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }
} 