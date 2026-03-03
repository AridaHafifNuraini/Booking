<?php
class Lapangan {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM PlayStation ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function get($id) {
        $stmt = $this->db->prepare("SELECT * FROM PlayStation WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }

    public function create($nama, $harga, $deskripsi, $gambar = null) {
        $stmt = $this->db->prepare("INSERT INTO PlayStation (nama_playstation, harga_per_jam, deskripsi, gambar) VALUES (:nama, :harga, :deskripsi, :gambar)");
        return $stmt->execute([':nama'=>$nama, ':harga'=>$harga, ':deskripsi'=>$deskripsi, ':gambar'=>$gambar]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM PlayStation WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }
}
?>