<?php
if (!isset($_SESSION)) session_start();
include 'function.php';
$id_pengguna = $_SESSION['id_pengguna'];
$r = mysqli_query($conn, "SELECT * FROM pengguna WHERE id_pengguna = '$id_pengguna'");
$pengguna = mysqli_fetch_assoc($r);
?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm py-2">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-primary-emphasis fw-semibold" href="index.php">
            <span class="fs-4">🩺</span> <span class="fs-5">Klinik Bidan Umi</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-2" id="profileDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="d-none d-md-block text-start">
                            <div class="fw-semibold text-dark"><?= htmlspecialchars($pengguna['nama']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($pengguna['email']) ?></small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 mt-2" aria-labelledby="profileDropdown" style="min-width: 230px;">
                        <li class="px-3 pt-2 pb-1 text-center border-bottom">
                            <div class="fw-semibold"><?= htmlspecialchars($pengguna['nama']) ?></div>
                            <small class="text-muted"><?= ucfirst($pengguna['role']) ?></small>
                        </li>
                        <li><a class="dropdown-item py-2 d-flex align-items-center gap-2" href="index.php?page=profil">
                                <i class="bi bi-person-circle"></i> Profil
                            </a></li>
                        <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </a></li>
            </ul>
            </li>
            </ul>
        </div>
    </div>
</nav>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
    .navbar-brand span {
        color: #0f766e;
    }

    .navbar .nav-link {
        color: #374151;
        transition: color 0.2s ease-in-out;
    }

    .navbar .nav-link:hover {
        color: #0f766e;
    }

    .dropdown-menu .dropdown-item {
        font-size: 0.925rem;
        transition: all 0.2s ease-in-out;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #0f766e;
    }

    .dropdown-divider {
        margin: 0.5rem 0;
    }
</style>