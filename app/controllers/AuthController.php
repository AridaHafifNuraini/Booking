<?php
class AuthController {
    private $userModel;
    public function __construct() {
        $this->userModel = new User();
    }

    public function register($post) {
        $nama = $post['nama'] ?? '';
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';
        if ($this->userModel->findByEmail($email)) {
            return ['status'=>false, 'msg'=>'Email sudah terdaftar'];
        }
        $ok = $this->userModel->register($nama, $email, $password);
        return $ok ? ['status'=>true] : ['status'=>false, 'msg'=>'Gagal mendaftar'];
    }

    public function login($post) {
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';
        $user = $this->userModel->login($email, $password);
        if ($user) return ['status'=>true, 'user'=>$user];
        return ['status'=>false, 'msg'=>'Email atau password salah'];
    }
}
?>