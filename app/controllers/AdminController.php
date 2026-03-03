 <?php
class AdminController {
    private $lapanganModel, $bookingModel;
    public function __construct() {
        $this->lapanganModel = new Lapangan();
        $this->bookingModel = new Booking();
    }

    public function allLapangan() { return $this->lapanganModel->all(); }
    public function addLapangan($nama, $harga, $des, $gambar = null) { return $this->lapanganModel->create($nama,$harga,$des,$gambar); }
    public function deleteLapangan($id) { return $this->lapanganModel->delete($id); }

    public function allBooking() { return $this->bookingModel->getAll(); }
    public function getBookingById($id) { return $this->bookingModel->getById($id); }
    public function verifyPayment($id_booking, $status) { return $this->bookingModel->updatePaymentStatus($id_booking, $status); }
    public function markAsPaidByCashier($id_booking) { return $this->bookingModel->markAsPaidByCashier($id_booking); }
    public function deleteBooking($id_booking) { return $this->bookingModel->deleteById($id_booking); }

    public function getPaymentAccounts() {
        $paymentAccount = new PaymentAccount();
        return $paymentAccount->all();
    }

    public function updatePaymentAccount($method, $account_number, $barcode) {
        $paymentAccount = new PaymentAccount();
        return $paymentAccount->update($method, $account_number, $barcode);
    }

    public function updateBookingPaymentMethod($id_booking, $method) {
        return $this->bookingModel->updatePaymentMethod($id_booking, $method);
    }

    public function addPaymentMethod($method, $account_number, $barcode) {
        $paymentAccount = new PaymentAccount();
        return $paymentAccount->create($method, $account_number, $barcode);
    }

    public function deletePaymentMethod($method) {
        $paymentAccount = new PaymentAccount();
        return $paymentAccount->delete($method);
    }
}
?>