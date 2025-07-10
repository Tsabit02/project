<?php
session_start();
include 'function.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE email='$email' AND password='$password' AND role='$role'");
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);
            $_SESSION['id_pengguna'] = $data['id_pengguna'];
            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Email, password, atau peran salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
    background: url('images/klinik.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 15px;
}


        .login-card {
    max-width: 400px;
    width: 100%;
    background: rgba(255, 255, 255, 0.85); /* transparan putih */
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    padding: 30px 35px;
    backdrop-filter: blur(6px); /* efek blur background */
}


        .login-header {
            color: #0f766e;
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
        }

        label {
            font-weight: 600;
            color: #2c3e3a;
        }

        .form-control {
            border-radius: 8px;
            border: 1.8px solid #a7d0cc;
            padding: 10px 15px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #0f766e;
            box-shadow: 0 0 5px rgba(15, 118, 110, 0.4);
        }

        .btn-login {
            background-color: #0f766e;
            border: none;
            padding: 12px 0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            color: #ffffff;
            width: 100%;
            margin-top: 15px;
        }

        .btn-login:hover {
            background-color: #0c5c56;
        }

        .alert-danger {
            font-weight: 600;
            border-radius: 8px;
            font-size: 0.95rem;
            margin-bottom: 20px;
            padding: 12px 20px;
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-card shadow">
        <h2 class="login-header">Klinik Bidan Umi</h2>
        <?php if ($error && isset($_POST['login'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="" method="POST" novalidate>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Masukkan email" required />
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required />
            </div>
            <div class="mb-3">
                <label for="role">Login sebagai</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>Pilih peran</option>
                    <option value="bidan">Bidan</option>
                    <option value="asisten">Asisten Bidan</option>
                </select>
            </div>
            <button name="login" type="submit" class="btn-login">Login</button>

            <!-- Link Google Maps di bawah tombol login -->
<div class="text-center mt-3">
    <a href="https://maps.app.goo.gl/rCgNK25sXKy5MmfS9" target="_blank" class="text-decoration-none" style="color: #0f766e; font-weight: 600;">
        📍 Lokasi Klinik Bidan Umi
    </a>
</div>
        </form>
        <div class="footer-text">© 2025 Sistem Pengelolaan Data Klinik Bidan Umi</div>
    </div>
</body>

</html>