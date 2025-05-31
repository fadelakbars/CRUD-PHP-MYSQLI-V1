<?php

    // 
    $host = "127.0.0.1";
    $user = "root";
    $pass = "";
    $db = "starter_v1";

    // buat koneksi ke database
    $conn = mysqli_connect($host, $user, $pass, $db);

    // cek koneksi database
    if (!$conn) {
        die("koneksi gagal" . mysqli_connect_error());
    }

    // fungsi untuk menghandle query SELECT
    function query ($sql) {
        global $conn;
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query Error: " . mysqli_error($conn));
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // fungsi untuk menghandle query INSERT, UPDATE, DELETE
    function execute($sql) {
        global $conn;
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query Error" . mysqli_error($conn));
        }
        return $result;
    }

    $mahasiswa = query("SELECT * FROM mahasiswa");
    $no = 1;

    // Tambah mahasiswa
    if (isset($_POST['tambahmahasiswa'])) {
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $jurusan = $_POST['jurusan'];
        execute("INSERT INTO mahasiswa (nama, nim, jurusan) VALUE ('$nama','$nim','$jurusan')");
        header("Location: index.php");
    }

    // Hapus mahasiswa
    if (isset($_GET['hapusmahasiswa'])) {
        $id = $_GET['hapusmahasiswa'];
        execute("DELETE FROM mahasiswa WHERE id=$id");
        header("Location: index.php");
    }

    // Ambil data untuk edit
    $editData = null;
    if (isset($_GET['editmahasiswa'])) {
        $id = $_GET['editmahasiswa'];
        $result = query("SELECT * FROM mahasiswa WHERE id=$id");
        if (!empty($result)) {
            $editData = $result[0];
        }
    }

    // Update mahasiswa
    if (isset($_POST['updatemahasiswa'])) {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $nim = $_POST['nim'];
        $jurusan = $_POST['jurusan'];
        execute("UPDATE mahasiswa SET nama='$nama', nim='$nim', jurusan='$jurusan' WHERE id=$id");
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h2 class="text-center mt-5">Data Mahasiswa</h2>

<section class="px-5 py-3">
    <!-- Tombol Tambah -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMahasiswa">
        Tambah Mahasiswa
    </button>

    <!-- Modal Tambah Mahasiswa -->
    <div class="modal fade" id="tambahMahasiswa" tabindex="-1" aria-labelledby="tambahMahasiswaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMahasiswaLabel">Tambah Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="tambahmahasiswa" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Mahasiswa -->
    <?php if ($editData): ?>
    <div class="modal fade show" id="editMahasiswa" tabindex="-1" aria-labelledby="editMahasiswaLabel" aria-modal="true" style="display:block;">
        <div class="modal-dialog">
            <form action="" method="post" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMahasiswaLabel">Edit Mahasiswa</h5>
                    <a href="index.php" class="btn-close"></a>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?= $editData['nama'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" value="<?= $editData['nim'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" value="<?= $editData['jurusan'] ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="updatemahasiswa" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    <?php endif; ?>
</section>

<section class="px-5 py-3">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Jurusan</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($mahasiswa as $row): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nim'] ?></td>
                <td><?= $row['jurusan'] ?></td>
                <td>
                    <a href="index.php?editmahasiswa=<?= $row['id'] ?>" class="btn btn-sm btn-outline-success">Edit</a>
                    <a href="index.php?hapusmahasiswa=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin hapus?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
