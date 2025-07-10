<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <?php
    session_start();

    // Cek apakah pengguna sudah login
    if (!isset($_SESSION['nama'])) {
        // Tampilkan pesan alert dan lakukan redirect menggunakan JavaScript
        echo "<script>alert('Harap login terlebih dahulu!'); window.location.href = 'login.php';</script>";
        exit();
    }

    $nama = $_SESSION['nama'];
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    ?>




    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            overflow: hidden;
        }

        /* Wrapper untuk menampung sidebar dan konten utama */
        .wrapper {
            display: flex;
            height: calc(100vh - 56px);
            /* Menghitung tinggi layar minus navbar */
            overflow: hidden;

        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background-color: #ffffff;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsif: Sidebar dan konten penuh lebar di layar kecil */
        @media (max-width: 768px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                border-right: none;

            }

            .main-content {
                flex: 1;
                padding: 30px;
                background-color: #ffffff;
                overflow-y: hidden;

            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include('page/nav.php'); ?>

    <!-- Sidebar + Main Content -->
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('page/sidebar.php'); ?>

        <!-- Konten Utama -->
        <div class="main-content">

            <?php
            switch ($page) {
                case 'dashboard':
                    include('page/dashboard.php');
                    break;
                case 'data_kehamilan':
                    include('page/data_kehamilan.php');
                    break;
                case 'data_bersalin':
                    include('page/data_bersalin.php');
                    break;
                case 'profil':
                    include('page/profil.php');
                    break;
                case 'detail_kehamilan':
                    include('page/detail_kehamilan.php');
                    break;
                case 'detail_bersalin':
                    include('page/detail_bersalin.php');
                    break;
                default:
                    echo "<h5 class='text-danger'>Halaman tidak ditemukan!</h5>";
                    break;
            }
            ?>
        </div>



</body>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

</html>