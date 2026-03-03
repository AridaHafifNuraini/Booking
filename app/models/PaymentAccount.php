 <?php
class PaymentAccount {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM payment_accounts ORDER BY payment_method");
        return $stmt->fetchAll();
    }

    public function get($method) {
        $stmt = $this->db->prepare("SELECT * FROM payment_accounts WHERE payment_method=:method");
        $stmt->execute([':method'=>$method]);
        return $stmt->fetch();
    }

    public function create($method, $account_number, $barcode) {
        $stmt = $this->db->prepare("INSERT INTO payment_accounts (payment_method, account_number, barcode) VALUES (:method, :account, :barcode) ON DUPLICATE KEY UPDATE account_number=:account_update, barcode=:barcode_update");
        return $stmt->execute([
            ':method' => $method,
            ':account' => $account_number,
            ':barcode' => $barcode,
            ':account_update' => $account_number,
            ':barcode_update' => $barcode
        ]);
    }

    public function update($method, $account_number, $barcode) {
        $stmt = $this->db->prepare("INSERT INTO payment_accounts (payment_method, account_number, barcode) VALUES (:method, :account, :barcode) ON DUPLICATE KEY UPDATE account_number=:account_update, barcode=:barcode_update");
        return $stmt->execute([
            ':method' => $method,
            ':account' => $account_number,
            ':barcode' => $barcode,
            ':account_update' => $account_number,
            ':barcode_update' => $barcode
        ]);
    }

    public function delete($method) {
        $stmt = $this->db->prepare("DELETE FROM payment_accounts WHERE payment_method=:method");
        return $stmt->execute([':method'=>$method]);
    }
}
?>
