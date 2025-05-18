<?php
class Voucher {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function createVoucher($employeeId, $amount, $expiryDate = null) {
        $code = $this->generateUniqueCode();
        
        $stmt = $this->db->prepare("
            INSERT INTO vouchers (code, amount, created_by, expires_at)
            VALUES (?, ?, ?, ?)
        ");
        
        return $stmt->execute([$code, $amount, $employeeId, $expiryDate]);
    }
    
    public function validateVoucher($code) {
        $stmt = $this->db->prepare("
            SELECT * FROM vouchers 
            WHERE code = ? 
            AND is_used = FALSE 
            AND (expires_at IS NULL OR expires_at > NOW())
        ");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function markVoucherAsUsed($code) {
        $stmt = $this->db->prepare("
            UPDATE vouchers 
            SET is_used = TRUE 
            WHERE code = ?
        ");
        return $stmt->execute([$code]);
    }
    
    private function generateUniqueCode() {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM vouchers WHERE code = ?");
            $stmt->execute([$code]);
            $exists = $stmt->fetchColumn();
        } while ($exists);
        
        return $code;
    }
} 