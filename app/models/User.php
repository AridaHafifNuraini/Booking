<?php
class User {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($nama, $email, $password, $role = 'user') {
        $sql = "INSERT INTO users (nama, email, password, role) VALUES (:nama, :email, :password, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nama'=>$nama, ':email'=>$email, ':password'=>md5($password), ':role'=>$role
        ]);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email=:email AND password=:password LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email'=>$email, ':password'=>md5($password)]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
        $stmt->execute([':email'=>$email]);
        return $stmt->fetch();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id_user=:id LIMIT 1");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }
}
?>