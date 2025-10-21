<?php
include 'koneksi.php';

$hari_map = [
    'Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu',
    'Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'
];

$bulan_map = [
    'January'=>'Januari','February'=>'Februari','March'=>'Maret',
    'April'=>'April','May'=>'Mei','June'=>'Juni',
    'July'=>'Juli','August'=>'Agustus','September'=>'September',
    'October'=>'Oktober','November'=>'November','December'=>'Desember'
];

$hari = $hari_map[date("l")];
$tanggal = date("j");
$bulan = $bulan_map[date("F")];
$tahun = date("Y");

$result_all = mysqli_query($koneksi,"SELECT * FROM jadwal WHERE hari='$hari' ORDER BY jam_mulai");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Lab 1 XII RPL 1</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    height: 100vh;
    overflow: hidden;
    background: url('foto/bg.jpg') no-repeat center center/cover;
    position: relative;
}
body::before {
    content: "";
    position: absolute;
    inset: 0;
    backdrop-filter: blur(10px);
    background: rgba(0,0,0,0.4);
    z-index: 0;
}

/* ====== LAYOUT ====== */
.wrapper {
    display: flex;
    height: 100%;
    position: relative;
    z-index: 1;
    color: white;
}

/* ====== SIDEBAR (JADWAL) ====== */
.sidebar {
    width: 25%;
    background: rgba(15, 100, 236, 0.7);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 20px 40px;
    border-right: 2px solid rgba(255,255,255,0.15);
}
.jadwal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.2);
    padding: 10px 0;
}
.jam {
    color: #facc15;
    font-weight: 600;
    width: 130px;
}
.mapel {
    flex: 1;
    text-align: left;
}

/* ====== MAIN ====== */
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
}
.lab-box {
    position: absolute;
    top: 25px;
    right: 35px;
    background: white;
    color: black;
    border-radius: 15px;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    gap: 15px; /* jarak antar logo */
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.lab-box img {
    height: 60px;
    width: 60px;
    border-radius: 50%;
    object-fit: cover;
    background: white;
    cursor: pointer;
    transition: transform .2s ease;
    padding: 4px;
    box-shadow: 0 0 6px rgba(0,0,0,0.25);
}
.lab-box img:hover {
    transform: scale(1.1);
}
.lab-box h4 {
    font-size: 18px;
    font-weight: 700;
}

/* ====== INFO GURU ====== */
.info {
    text-align: center;
}
.info h1 {
    font-size: 55px;
    font-weight: 700;
    margin-bottom: 10px;
}
.info h1 span {
    color: #0ea5e9;
}
.info h3 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 5px;
}
.info h2 {
    font-size: 65px;
    font-weight: 700;
    margin: 25px 0 10px;
}
#tanggal {
    font-size: 22px;
    font-weight: 600;
    color: #facc15;
    text-shadow: 0 0 8px rgba(0,0,0,0.6);
    margin-top: -10px;
}
.info p {
    font-size: 20px;
    margin-bottom: 8px;
}
.guru-foto {
    position: absolute;
    bottom: 30px;
    right: 50px;
    width: 230px;
    height: 230px;
    border-radius: 50%;
    object-fit: cover;
    border: 6px solid white;
    box-shadow: 0 0 25px rgba(0,0,0,0.5);
}
</style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <?php while ($row = mysqli_fetch_assoc($result_all)): ?>
            <div class="jadwal-item">
                <div class="jam"><?= date('H:i', strtotime($row['jam_mulai'])) ?> - <?= date('H:i', strtotime($row['jam_selesai'])) ?></div>
                <div class="mapel"><?= htmlspecialchars($row['mapel']); ?></div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="main">
        <div class="lab-box">
            <h4>Lab. 1</h4>
            <!-- Logo SMK -->
            <a href="crud_jadwal.php"><img src="foto/neskar.jpg" alt="SMK 1"></a>
            <!-- Logo RPL -->
            <img src="foto/rpl.png" alt="Logo RPL">
        </div>

        <div class="info">
            <h1>Penanggung<br><span>Jawab Lab</span></h1>
            <h3>Yusuf Effendy, S.T., M.Kom</h3>
            <h2 id="jam">09.00 AM</h2>
            <p id="tanggal"><?= "$hari, $tanggal $bulan $tahun"; ?></p>
        </div>

        <img src="foto/yusuf.jpg" alt="Yusuf Effendy" class="guru-foto">
    </div>
</div>

<script>
function updateJam() {
    const now = new Date();
    let jam = now.getHours();
    const menit = String(now.getMinutes()).padStart(2, '0');
    const ampm = jam >= 12 ? "PM" : "AM";
    jam = jam % 12;
    jam = jam ? jam : 12;
    document.getElementById("jam").textContent = `${jam}.${menit} ${ampm}`;
}
setInterval(updateJam, 1000);
updateJam();
</script>

</body>
</html>
