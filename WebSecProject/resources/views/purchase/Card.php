<?php
class Card {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function addCard($userId, $cardNumber, $expiryDate, $cvv, $billingAddress) {
        try {
            $this->db->beginTransaction();
            
            // Add card
            $stmt = $this->db->prepare("
                INSERT INTO cards (user_id, card_number, expiry_date, cvv)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$userId, $cardNumber, $expiryDate, $cvv]);
            $cardId = $this->db->lastInsertId();
            
            // Add billing address
            $stmt = $this->db->prepare("
                INSERT INTO billing_addresses (card_id, street_address, city, state, postal_code, country)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $cardId,
                $billingAddress['street_address'],
                $billingAddress['city'],
                $billingAddress['state'],
                $billingAddress['postal_code'],
                $billingAddress['country']
            ]);
            
            $this->db->commit();
            return $cardId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getCardsByUserId($userId) {
        $stmt = $this->db->prepare("
            SELECT c.*, b.* 
            FROM cards c
            LEFT JOIN billing_addresses b ON c.id = b.card_id
            WHERE c.user_id = ? AND c.is_active = TRUE
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateCardBalance($cardId, $amount) {
        $stmt = $this->db->prepare("
            UPDATE cards 
            SET credit_balance = credit_balance + ?
            WHERE id = ?
        ");
        return $stmt->execute([$amount, $cardId]);
    }
    
    public function deactivateCard($cardId) {
        $stmt = $this->db->prepare("
            UPDATE cards 
            SET is_active = FALSE
            WHERE id = ?
        ");
        return $stmt->execute([$cardId]);
    }
} 