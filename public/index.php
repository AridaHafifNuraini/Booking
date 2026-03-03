<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rental PlayStation Barokah</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;scroll-behavior:smooth;}
body{background:#f1f5f9;}

.btn1 {
  display: inline-block;
  padding: 14px 20px;
  background: linear-gradient(45deg, #2563eb, #1e40af);
  color: #fff;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 500;
  transition: 0.3s;
  box-shadow: 0 8px 20px rgba(75, 105, 201, 0.3);
}

.btn1:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(117, 158, 232, 0.4);
}

/* NAVBAR */
nav{
display:flex;justify-content:space-between;align-items:center;
padding:18px 8%;background:white;
position:sticky;top:0;
z-index:1000;
box-shadow:0 4px 20px rgba(0,0,0,0.05);
}

.logo{font-size:22px;font-weight:700;color:#1e3a8a;}
nav ul{display:flex;gap:25px;list-style:none;}
nav a{text-decoration:none;color:#374151;font-weight:500;}

.btn{
background:linear-gradient(45deg,#2563eb,#1e40af);
color:white;padding:12px 22px;
border-radius:8px;text-decoration:none;

}

.nav-booking{
display:none;
}
.nav-booking.show{
display:inline-block;

}

/* SECTION */
section{padding:80px 8%;scroll-margin-top:80px;}
h1,h2{text-align:center;color:#1e3a8a;margin-bottom:20px;}
p{color:#4b5563;line-height:1.6;}

/* HERO */
.hero{
display:flex;flex-wrap:wrap;
align-items:center;justify-content:space-between;
background:linear-gradient(120deg,#dbeafe,#ffffff);
}
.hero img{
width:410px;border-radius:15px;
animation:float 8s ease-in-out infinite;
}

/* GRID */
.grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:25px;
}

.card{
background:white;padding:30px;
border-radius:15px;
box-shadow:0 6px 20px rgba(0,0,0,0.06);
transition:.3s;
opacity:0;transform:translateY(40px);
}
.card.show{opacity:1;transform:translateY(0);}
.card:hover{transform:translateY(-10px);}

/* TESTIMONI */
.testi-card{
text-align:left;
}
.testi-card i{
color:gold;
}

/* ABOUT */
.about{
background:white;
text-align:center;
}

/* HARGA */
.price{
font-size:28px;font-weight:700;color:#2563eb;
}

/* KONTAK */
.kontak{
background:#dbeafe;
text-align:center;
}

/* SOSMED */
.sosmed{
margin-top:20px;
display:flex;justify-content:center;gap:20px;
}
.sosmed i{
font-size:26px;
color:#1e3a8a;
transition:.3s;
}
.sosmed i:hover{
transform:scale(1.2);
color:#2563eb;
}


/* FOOTER */
footer{
background:white;
text-align:center;
padding:20px;
color:#6b7280;
}

/* ANIM */
@keyframes float{
0%,100%{transform:translateY(0);}
50%{transform:translateY(-15px);}
}

.about-modern {
  padding: 100px 10%;
  background: linear-gradient(to right, #f9f9f9, #f2f3ff);
}

.about-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 60px;
  flex-wrap: wrap;
}

/* TEXT */
.about-content {
  flex: 1;
}

.about-content .label {
  font-size: 14px;
  letter-spacing: 2px;
  color: #777;
  font-weight: 600;
}

.about-content h2 {
  font-size: 40px;
  margin: 20px 0;
  color: #1a1a1a;
  line-height: 1.3;
}

.about-content p {
  color: #555;
  line-height: 1.7;
  margin-bottom: 30px;
  max-width: 500px;
}

/* LIST */
.about-list {
  margin-bottom: 30px;
}

.list-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.list-item i {
  width: 28px;
  height: 28px;
  background: linear-gradient(45deg, #2563eb, #1e40af);
  color: white;
  border-radius: 50%;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
}

/* BUTTON */
.btn-modern {
  display: inline-block;
  padding: 14px 30px;
  background: linear-gradient(45deg, #2563eb, #1e40af);
  color: #fff;
  text-decoration: none;
  border-radius: 10px;
  font-weight: 500;
  transition: 0.3s;
  box-shadow: 0 8px 20px rgba(75, 105, 201, 0.3);
}

.btn-modern:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 25px rgba(117, 158, 232, 0.4);
}

/* IMAGE CARD */
.about-image {
  flex: 1;
  display: flex;
  justify-content: center;
}

.image-card {
  background: white;
  padding: 20px;
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
  transform: rotate(-2deg);
  transition: 0.3s;
}

.image-card:hover {
  transform: rotate(0deg) scale(1.03);
}

.image-card img {
  width: 100%;
  max-width: 450px;
  border-radius: 15px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav>
<div class="logo">Barokah PS</div>
<ul>
<li><a href="#home">Home</a></li>
<li><a href="#tentang">Tentang</a></li>
<li><a href="#fasilitas">Fasilitas</a></li>
<li><a href="#harga">Harga</a></li>
<li><a href="#testimoni">Testimoni</a></li>
<li><a href="#kontak">Kontak</a></li>

<div>
<?php if(isset($_SESSION['user'])): ?>
    <a href="dashboard.php" class="nav-btn register">Dashboard</a>
<?php else: ?>
    <a href="auth/login.php" class="nav-btn login">Login</a>
<?php endif; ?>
</div>
</ul>
</nav>

<!-- HOME -->
<section class="hero" id="home">
<div>
<h1>Rental PlayStation Barokah</h1>
<p>Tempat rental PS paling nyaman dengan perangkat terbaru dan harga pelajar</p>
<br>
<a href="auth/login.php" class="btn1">🎮 Booking Sekarang</a>
</div>
<img src="https://images.unsplash.com/photo-1606813907291-d86efa9b94db">
</section>

<!-- TENTANG -->

<section class="about-modern" id="tentang">
    <h2>Tentang Kami</h2>
  <div class="about-wrapper">

    <!-- KIRI (TEXT) -->
    <div class="about-content">

      <p>
Barokah PS adalah tempat rental PlayStation dengan tujuan memberikan pengalaman gaming terbaik bagi para penggemar game.
 Kami menawarkan berbagai jenis konsol, mulai dari PS4 hingga PS5, lengkap dengan koleksi game terbaru dan terpopuler. Dengan suasana yang nyaman, harga yang terjangkau,
  dan layanan pelanggan yang ramah, Barokah PS menjadi pilihan utama bagi para gamer untuk bersantai dan menikmati waktu bermain bersama teman-teman.
      </p>

      <div class="about-list">
        <div class="list-item">
          <i class="fas fa-check"></i>
          <span>Harga terjangkau & Ramah di Budget Pelajar</span>
        </div>

        <div class="list-item">
          <i class="fas fa-check"></i>
          <span>Perangkat terbaru & kualitas terbaik</span>
        </div>

        <div class="list-item">
          <i class="fas fa-check"></i>
          <span>Staff ramah & profesional</span>
        </div>
      </div>

      <a href="#" class="btn-modern">Baca Selengkapnya →</a>
    </div>

    <!-- KANAN (IMAGE CARD) -->
    <div class="about-image">
      <div class="image-card">
        <img src="https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Gaming Setup">
      </div>
    </div>

  </div>
</section>


<!-- FASILITAS -->
<section id="fasilitas">
<h2>Fasilitas</h2>
<div class="grid">
<div class="card">⚡ PS4 & PS5 Terbaru</div>
<div class="card">🖥️ TV Full HD</div>
<div class="card">❄️ Ruangan AC Nyaman</div>
<div class="card">🎮 Banyak Game Seru</div>
<div class="card">⏰ Layanan 24/7</div>
<div class="card">💰 Harga Terjangkau</div>

</div>  
</section>


<!-- HARGA -->
<section id="harga">
<h2>Harga</h2>
<div class="grid">
<div class="card"><h3>PS4</h3><p class="price">5K / jam</p></div>
<div class="card"><h3>PS5</h3><p class="price">10K / jam</p></div>
<div class="card"><h3>Paket Mabar</h3><p class="price">25K / 3 jam</p></div>
</div>
</section>


<!-- TESTIMONI -->
<section id="testimoni">
<h2>Testimoni Pelanggan</h2>
<div class="grid">

<div class="card testi-card">
<b>Rizky Ibad</b><br>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<p>Tempatnya bersih dan nyaman banget!</p>
</div>

<div class="card testi-card">
<b>Alvin</b><br>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<p>PS lancar, controller mulus.</p>
</div>

<div class="card testi-card">
<b>Melvin Syahputra</b><br>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i><i class="fa fa-star"></i>
<i class="fa fa-star"></i>
<p>Harga murah cocok buat pelajar.</p>
</div>

</div>
</section>


<!-- KONTAK -->
<section class="kontak" id="kontak">
<h2>Kontak Kami</h2>

<p>WhatsApp: 08xxxxxxxxxx</p>
<p>Alamat: Jl. Gaming No.1</p>

<!-- GOOGLE MAPS -->
<div style="margin-top:30px;">
<iframe 
src="https://www.google.com/maps?q=jakarta&output=embed"
width="100%" 
height="300" 
style="border-radius:15px;border:none;"
loading="lazy">
</iframe>
</div>

<div class="sosmed">
<i class="fab fa-instagram"></i>
<i class="fab fa-youtube"></i>
<i class="fab fa-whatsapp"></i>
<i class="fab fa-facebook"></i>
</div>

</section>

<footer>
© 2026 Rental PlayStation Barokah
</footer>

<script>
const cards=document.querySelectorAll(".card");
window.addEventListener("scroll",()=>{
cards.forEach(card=>{
let pos=card.getBoundingClientRect().top;
if(pos<window.innerHeight-100){
card.classList.add("show");
}
});
});
</script>

</body>
</html>
