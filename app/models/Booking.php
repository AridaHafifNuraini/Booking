<?php
class Booking {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function checkDoubleBooking($id_lapangan, $tanggal, $jam_mulai, $jam_selesai) {
        $sql = "SELECT COUNT(*) as count FROM booking
                WHERE id_lapangan = :id_lapangan
                AND tanggal = :tanggal
                AND (
                    (jam_mulai < :jam_selesai AND jam_selesai > :jam_mulai)
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_lapangan' => $id_lapangan,
            ':tanggal' => $tanggal,
            ':jam_mulai' => $jam_mulai,
            ':jam_selesai' => $jam_selesai
        ]);
        $result = $stmt->fetch();
        return $result['count'] > 0;
    }

    public function create($id_user, $id_lapangan, $tanggal, $jam_mulai, $jam_selesai, $total, $payment_method, $proof = null) {
        if ($this->checkDoubleBooking($id_lapangan, $tanggal, $jam_mulai, $jam_selesai)) {
            return false; 
        }

        $sql = "INSERT INTO booking (id_user, id_lapangan, tanggal, jam_mulai, jam_selesai, total_harga, payment_method, proof_payment, payment_status)
                VALUES (:id_user, :id_lapangan, :tanggal, :jam_mulai, :jam_selesai, :total, :payment_method, :proof, :payment_status)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_user'=>$id_user, ':id_lapangan'=>$id_lapangan, ':tanggal'=>$tanggal,
            ':jam_mulai'=>$jam_mulai, ':jam_selesai'=>$jam_selesai, ':total'=>$total,
            ':payment_method'=>$payment_method, ':proof'=>$proof, ':payment_status'=>'pending'
        ]);
    }

    public function getByUser($id_user) {
        $stmt = $this->db->prepare("SELECT b.*, l.nama_lapangan FROM booking b JOIN lapangan l ON b.id_lapangan=l.id WHERE b.id_user=:id ORDER BY b.id_booking DESC");
        $stmt->execute([':id'=>$id_user]);
        return $stmt->fetchAll();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT b.*, u.nama AS usernama, l.nama_lapangan FROM booking b JOIN users u ON b.id_user=u.id_user JOIN lapangan l ON b.id_lapangan=l.id ORDER BY b.id_booking DESC");
        return $stmt->fetchAll();
    }

    public function getById($id_booking) {
        $stmt = $this->db->prepare("SELECT b.*, l.nama_lapangan FROM booking b JOIN lapangan l ON b.id_lapangan=l.id WHERE b.id_booking=:id");
        $stmt->execute([':id'=>$id_booking]);
        return $stmt->fetch();
    }

    public function updatePaymentProof($id_booking, $filename) {
        $stmt = $this->db->prepare("UPDATE booking SET proof_payment=:p WHERE id_booking=:id");
        return $stmt->execute([':p'=>$filename, ':id'=>$id_booking]);
    }

    public function updatePaymentStatus($id_booking, $status) {
        $stmt = $this->db->prepare("UPDATE booking SET payment_status=:s WHERE id_booking=:id");
        return $stmt->execute([':s'=>$status, ':id'=>$id_booking]);
    }

    public function updatePaymentMethod($id_booking, $method) {
        $stmt = $this->db->prepare("UPDATE booking SET payment_method=:method, proof_payment=NULL, payment_status='pending' WHERE id_booking=:id");
        return $stmt->execute([':method'=>$method, ':id'=>$id_booking]);
    }

    public function markAsPaidByCashier($id_booking) {
        $stmt = $this->db->prepare("UPDATE booking SET payment_status='dibayar', proof_payment='paid_by_cashier' WHERE id_booking=:id");
        return $stmt->execute([':id'=>$id_booking]);
    }

    public function deleteByUser($id_booking, $id_user) {
        $stmt = $this->db->prepare("SELECT proof_payment FROM booking WHERE id_booking=:id AND id_user=:user");
        $stmt->execute([':id'=>$id_booking, ':user'=>$id_user]);
        $booking = $stmt->fetch();
        if ($booking && $booking['proof_payment'] && $booking['proof_payment'] !== 'paid_by_cashier') {
            $filePath = __DIR__ . '/../../public/uploads/' . $booking['proof_payment'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $stmt = $this->db->prepare("DELETE FROM booking WHERE id_booking=:id AND id_user=:user");
        return $stmt->execute([':id'=>$id_booking, ':user'=>$id_user]);
    }

    public function deleteById($id_booking) {
        $stmt = $this->db->prepare("DELETE FROM booking WHERE id_booking=:id");
        return $stmt->execute([':id'=>$id_booking]);
    }
}
?>
