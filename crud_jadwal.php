<?php 
include 'koneksi.php';

// === CREATE ===
if (isset($_POST['tambah'])) {
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mapel = $_POST['mapel'];
    $guru = $_POST['guru'];

    // Upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "foto/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $foto = $targetDir . time() . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $koneksi->query("INSERT INTO jadwal (hari, jam_mulai, jam_selesai, mapel, guru, foto)
                    VALUES ('$hari','$jam_mulai','$jam_selesai','$mapel','$guru','$foto')");
    header("Location: crud_jadwal.php");
    exit;
}

// === UPDATE ===
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    $mapel = $_POST['mapel'];
    $guru = $_POST['guru'];

    $fotoQuery = "";
    if (!empty($_FILES['foto']['name'])) {
        $targetDir = "foto/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $foto = $targetDir . time() . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
        $fotoQuery = ", foto='$foto'";
    }

    $koneksi->query("UPDATE jadwal SET 
                        hari='$hari',
                        jam_mulai='$jam_mulai',
                        jam_selesai='$jam_selesai',
                        mapel='$mapel',
                        guru='$guru'
                        $fotoQuery
                    WHERE id=$id");
    header("Location: crud_jadwal.php");
    exit;
}

// === DELETE ===
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $koneksi->query("DELETE FROM jadwal WHERE id=$id");
    header("Location: crud_jadwal.php");
    exit;
}

// === READ ===
$result = $koneksi->query("SELECT * FROM jadwal ORDER BY hari, jam_mulai");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>CRUD Jadwal Pelajaran</title>
<style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }
    body {
        background: #f3f4f6;
        margin: 0;
        padding: 0;
    }

    /* === HEADER === */
    header {
        background: linear-gradient(90deg, #2563eb, #3b82f6);
        color: white;
        padding: 12px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .logo-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .logo-wrap img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }
    .logo-wrap h1 {
        font-size: 18px;
        margin: 0;
    }
    .btn-back {
        background: #111827;
        color: #facc15;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }
    .btn-back:hover {
        background: #1f2937;
    }

    /* === CONTENT === */
    .container {
        width: 90%;
        max-width: 1000px;
        margin: 25px auto;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    h2 {
        text-align: center;
        color: #1e293b;
        margin-bottom: 20px;
    }

    /* === FORM === */
    form {
        display: grid;
        gap: 10px;
        max-width: 400px;
        margin: 0 auto;
    }
    form input, form button {
        padding: 8px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
    }
    form button {
        background: #2563eb;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border: none;
    }
    form button:hover {
        background: #1e40af;
    }

    /* === TABLE === */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        border-radius: 8px;
        overflow: hidden;
    }
    th, td {
        border: 1px solid #e2e8f0;
        padding: 10px;
        text-align: center;
    }
    th {
        background: #2563eb;
        color: white;
    }
    tr:nth-child(even) {
        background: #f8fafc;
    }
    td img {
        border-radius: 6px;
    }

    /* === BUTTONS === */
    .btn {
        padding: 5px 8px;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-size: 13px;
    }
    .btn-edit { background: #3b82f6; }
    .btn-hapus { background: #ef4444; }

    /* === MODAL === */
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 350px;
        position: relative;
    }
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 20px;
        color: #333;
    }
</style>
</head>
<body>

<header>
    <div class="logo-wrap">
        <img src="foto/neskar.jpg" alt="Logo">
        <h1>LAB 1 XII RPL 1</h1>
    </div>
    <a href="index.php" class="btn-back">⬅ Kembali</a>
</header>

<div class="container">
    <h2>Kelola Jadwal Pelajaran</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="text" name="hari" placeholder="Hari" required>
        <input type="time" name="jam_mulai" required>
        <input type="time" name="jam_selesai" required>
        <input type="text" name="mapel" placeholder="Mata Pelajaran" required>
        <input type="text" name="guru" placeholder="Nama Guru" required>
        <input type="file" name="foto">
        <button type="submit" name="tambah">Tambah</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Hari</th>
            <th>Jam</th>
            <th>Mapel</th>
            <th>Guru</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['hari'] ?></td>
            <td><?= $row['jam_mulai'] ?> - <?= $row['jam_selesai'] ?></td>
            <td><?= $row['mapel'] ?></td>
            <td><?= $row['guru'] ?></td>
            <td><?php if($row['foto']): ?><img src="<?= $row['foto'] ?>" width="60"><?php endif; ?></td>
            <td>
                <button class="btn btn-edit" onclick="openModal(
                    <?= $row['id'] ?>,
                    '<?= $row['hari'] ?>',
                    '<?= $row['jam_mulai'] ?>',
                    '<?= $row['jam_selesai'] ?>',
                    '<?= $row['mapel'] ?>',
                    '<?= $row['guru'] ?>'
                )">Edit</button>
                <a href="?hapus=<?= $row['id'] ?>" class="btn btn-hapus" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Modal Edit -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Edit Jadwal</h3>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="hari" id="edit_hari" required>
            <input type="time" name="jam_mulai" id="edit_jam_mulai" required>
            <input type="time" name="jam_selesai" id="edit_jam_selesai" required>
            <input type="text" name="mapel" id="edit_mapel" required>
            <input type="text" name="guru" id="edit_guru" required>
            <input type="file" name="foto">
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</div>

<script>
function openModal(id,hari,jam_mulai,jam_selesai,mapel,guru){
    document.getElementById("edit_id").value=id;
    document.getElementById("edit_hari").value=hari;
    document.getElementById("edit_jam_mulai").value=jam_mulai;
    document.getElementById("edit_jam_selesai").value=jam_selesai;
    document.getElementById("edit_mapel").value=mapel;
    document.getElementById("edit_guru").value=guru;
    document.getElementById("editModal").style.display="flex";
}
function closeModal(){
    document.getElementById("editModal").style.display="none";
}
</script>

</body>
</html>
