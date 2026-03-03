<?php
class UserController {
    private $lapanganModel, $bookingModel;
    public function __construct() {
        $this->lapanganModel = new Lapangan();
        $this->bookingModel = new Booking();
    }

    public function listLapangan() {
        return $this->lapanganModel->all();
    }

    public function createBooking($data) {
        if ($this->bookingModel->checkDoubleBooking($data['id_lapangan'], $data['tanggal'], $data['jam_mulai'], $data['jam_selesai'])) {
            return ['success' => false, 'message' => 'Lapangan sudah dipesan pada waktu tersebut.'];
        }

        $result = $this->bookingModel->create(
            $data['id_user'], $data['id_lapangan'], $data['tanggal'],
            $data['jam_mulai'], $data['jam_selesai'], $data['total'], $data['payment_method'], $data['proof'] ?? null
        );

        if ($result) {
            return ['success' => true, 'message' => 'Booking berhasil.'];
        } else {
            return ['success' => false, 'message' => 'Gagal membuat booking.'];
        }
    }

    public function getRiwayat($id_user) {
        return $this->bookingModel->getByUser($id_user);
    }

    public function uploadProof($id_booking, $filename) {
        return $this->bookingModel->updatePaymentProof($id_booking, $filename);
    }

    public function deleteBooking($id_booking, $id_user) {
      
        $booking = $this->bookingModel->getById($id_booking);
        if ($booking && $booking['payment_status'] === 'dibayar') {
            return false; 
        }
        return $this->bookingModel->deleteByUser($id_booking, $id_user);
    }

    public function getBookingById($id_booking, $id_user) {
        $booking = $this->bookingModel->getById($id_booking);
        if ($booking && $booking['id_user'] == $id_user) {
            return $booking;
        }
        return null;
    }

    public function getBookingReceipt($id_booking, $id_user) {
        $booking = $this->bookingModel->getById($id_booking);
        if ($booking && $booking['id_user'] == $id_user && ($booking['proof_payment'] || in_array($booking['payment_status'], ['dibayar', 'accepted']))) {
            return $booking;
        }
        return null;
    }
}
?>
