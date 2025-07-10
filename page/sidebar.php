<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .sidebar {
        background-color: #e0f2f1;
        /* Warna hijau kebiruan lembut */
        width: 240px;
        padding: 1.5rem 1rem;
        box-shadow: 2px 0 6px rgba(0, 0, 0, 0.05);
    }

    .sidebar .nav-link {
        color: #0f4c4c;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .sidebar .nav-link:hover {
        background-color: #b2dfdb;
        color: #004d40;
    }

    .sidebar .nav-link.active {
        background-color: #26a69a;
        color: white;
        font-weight: 600;
    }

    .sidebar .nav-link i {
        font-size: 1.2rem;
    }
</style>

<!-- Sidebar Container -->
<div class="d-flex">
    <nav class="sidebar min-vh-100">
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>
        <ul class="nav flex-column mb-0">
            <li class="nav-item mb-1">
                <a href="index.php?page=dashboard" class="nav-link <?= ($page == 'dashboard') ? 'active' : '' ?>">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="index.php?page=data_kehamilan" class="nav-link <?= ($page == 'data_kehamilan') ? 'active' : '' ?>">
                    <i class="bi bi-people"></i> Data Ibu Hamil
                </a>
            </li>
            <li class="nav-item mb-1">
                <a href="index.php?page=data_bersalin" class="nav-link <?= ($page == 'data_bersalin') ? 'active' : '' ?>">
                    <i class="bi bi-bag-heart"></i> Data Persalinan
                </a>
            </li>
            <li class="nav-item mt-4">
                <a href="index.php?page=profil" class="nav-link <?= ($page == 'profil') ? 'active' : '' ?>">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            </li>
        </ul>
    </nav>
</div>


<?php $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>