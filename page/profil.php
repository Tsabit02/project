<?php
if (!isset($_SESSION['id_pengguna'])) {
    die("Session id_pengguna belum ada. Pastikan sudah login dan session diset dengan benar.");
}
include 'function.php';
$id_pengguna = $_SESSION['id_pengguna'];
$query = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
if (!$query) {
    die("Query gagal: " . mysqli_error($conn));
}
$data = mysqli_fetch_assoc($query);
if (!$data) {
    die("Data pengguna tidak ditemukan untuk ID: $id_pengguna");
}

$swal_icon = '';
$swal_title = '';
$swal_text = '';

if (isset($_POST['simpan_profil'])) {
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama']);
    $email_baru = mysqli_real_escape_string($conn, $_POST['email']);
    $cek = mysqli_query($conn, "SELECT * FROM pengguna WHERE email = '$email_baru' AND id_pengguna != '$id_pengguna'");
    if (mysqli_num_rows($cek) > 0) {
        $swal_icon = 'error';
        $swal_title = 'Gagal';
        $swal_text = 'Email sudah digunakan oleh pengguna lain.';
    } else {
        $update = mysqli_query($conn, "UPDATE pengguna SET nama = '$nama_baru', email = '$email_baru' WHERE id_pengguna = '$id_pengguna'");
        if ($update) {
            $swal_icon = 'success';
            $swal_title = 'Berhasil';
            $swal_text = 'Profil berhasil diperbarui.';

            $_SESSION['nama'] = $nama_baru;

            $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
            $data = mysqli_fetch_assoc($query);
        } else {
            $swal_icon = 'error';
            $swal_title = 'Gagal';
            $swal_text = 'Gagal memperbarui profil: ' . mysqli_error($conn);
        }
    }
}

if (isset($_POST['ubah_password'])) {
    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];

    $cek_pass = mysqli_query($conn, "SELECT password FROM pengguna WHERE id_pengguna = '$id_pengguna'");
    $row = mysqli_fetch_assoc($cek_pass);
    $password_db = $row['password'];
    if ($password_lama !== $password_db) {
        $swal_icon = 'error';
        $swal_title = 'Oops...';
        $swal_text = 'Password lama tidak sesuai!';
    } elseif ($password_baru !== $konfirmasi) {
        $swal_icon = 'error';
        $swal_title = 'Oops...';
        $swal_text = 'Konfirmasi password tidak cocok!';
    } elseif ($password_baru === $password_lama) {
        $swal_icon = 'warning';
        $swal_title = 'Perhatian';
        $swal_text = 'Password baru tidak boleh sama dengan password lama!';
    } else {
        $ubah = mysqli_query($conn, "UPDATE pengguna SET password = '$password_baru' WHERE id_pengguna = '$id_pengguna'");
        if ($ubah) {
            $swal_icon = 'success';
            $swal_title = 'Berhasil';
            $swal_text = 'Password berhasil diubah.';
        } else {
            $swal_icon = 'error';
            $swal_title = 'Gagal';
            $swal_text = 'Gagal mengubah password: ' . mysqli_error($conn);
        }
    }
}

if (isset($_POST['tambah_akun'])) {
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama_baru']);
    $email_baru = mysqli_real_escape_string($conn, $_POST['email_baru']);
    $password_baru_akun = mysqli_real_escape_string($conn, $_POST['password_baru_akun']);
    $role_baru = $_POST['role_baru'];

    $cek_email = mysqli_query($conn, "SELECT * FROM pengguna WHERE email = '$email_baru'");
    if (mysqli_num_rows($cek_email) > 0) {
        $swal_icon = 'error';
        $swal_title = 'Gagal';
        $swal_text = 'Email sudah digunakan. Gunakan email lain.';
    } else {
        $simpan = mysqli_query($conn, "INSERT INTO pengguna (nama, email, password, role) VALUES ('$nama_baru', '$email_baru', '$password_baru_akun', '$role_baru')");
        if ($simpan) {
            $swal_icon = 'success';
            $swal_title = 'Berhasil';
            $swal_text = 'Akun baru berhasil ditambahkan.';
        } else {
            $swal_icon = 'error';
            $swal_title = 'Gagal';
            $swal_text = 'Gagal menambah akun: ' . mysqli_error($conn);
        }
    }
}

?>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container px-3">
    <h3 class="mb-4">👤 Pengaturan Profil</h3>

    <?php if ($_SESSION['role'] === 'bidan'): ?>
         <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAkun">
            <i class="bi bi-person-plus-fill"></i> Tambah Akun
        </button>
    <?php endif; ?>

    <?php if ($_SESSION['role'] === 'bidan'): ?>
        <div class="modal fade" id="modalTambahAkun" tabindex="-1" aria-labelledby="modalTambahAkunLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTambahAkunLabel">Tambah Akun Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nama_baru" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_baru" name="nama_baru" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_baru" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_baru" name="email_baru" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_baru_akun" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password_baru_akun" name="password_baru_akun" required>
                            </div>
                            <div class="mb-3">
                                <label for="role_baru" class="form-label">Peran</label>
                                <select name="role_baru" class="form-select" required>
                                    <option value="asisten">Asisten Bidan</option>
                                    <option value="bidan">Bidan</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="tambah_akun" class="btn btn-success">
                                <i class="bi bi-person-plus-fill"></i> Tambah Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- Profil Pengguna -->
    <div class="card mb-4">
        <div class="card-header text-white" style="background-color: #0f766e;">Profil Pengguna</div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                    </div>
                </div>
                <button name="simpan_profil" class="btn text-white mt-2" style="background-color: #0f766e;">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- Ubah Password -->
    <div class="card">
        <div class="card-header text-white" style="background-color: #0f766e;">Ubah Password</div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>

                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required>
                    </div>
                </div>
                <button name="ubah_password" class="btn text-white mt-2" style="background-color: #0f766e;">
                    <i class="bi bi-lock-fill me-1"></i> Ubah Password
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<?php
if ($swal_icon && $swal_title && $swal_text) {
    echo "<script>
        Swal.fire({
            icon: '$swal_icon',
            title: '$swal_title',
            text: '$swal_text'
        });
    </script>";
}
?>