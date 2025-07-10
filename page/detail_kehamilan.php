<?php
include 'function.php';
$id = isset($_GET['id_hamil']) ? (int)$_GET['id_hamil'] : 0;

$sql = "SELECT * FROM ibu_hamil WHERE id_hamil = $id LIMIT 1";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo '<div class="alert alert-warning text-center mt-5">Data tidak ditemukan.</div>';
    exit;
}
?>

<div class="detail-card">
    <h2 class="detail-title">Detail Catatan Ibu <?= htmlspecialchars($data['nama']) ?></h2>
    <ul class="detail-list">
        <li>
            <span class="detail-label">
                <!-- Icon user -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zM6 18v-1c0-1.1.9-2 2-2h8c1.1 0 2 .9 2 2v1H6z" />
                </svg>
                Nama Lengkap
            </span>
            <span class="detail-value"><?= htmlspecialchars($data['nama']) ?></span>
        </li>
        <li>
            <span class="detail-label">
                <!-- Icon calendar -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM5 18V9h14v9H5z" />
                </svg>
                Usia Kehamilan
            </span>
            <span class="detail-value"><?= htmlspecialchars($data['usia_kehamilan']) ?> minggu</span>
        </li>
        <li>
            <span class="detail-label">
                <!-- Icon location -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.56-2.55 6.9-5 9.88C9.56 15.85 7 11.57 7 9z" />
                </svg>
                Alamat
            </span>
            <span class="detail-value"><?= htmlspecialchars($data['alamat']) ?></span>
        </li>
        <li>
            <span class="detail-label">
                <!-- Icon clock/age -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 20c4.42 0 8-3.58 8-8s-3.58-8-8-8-8 3.58-8 8 3.58 8 8 8zm1-13h-2v6l5.25 3.15 1-1.64-4.25-2.51V7z" />
                </svg>
                Usia Ibu
            </span>
            <span class="detail-value"><?= htmlspecialchars($data['usia']) ?> tahun</span>
        </li>
        <li>
            <span class="detail-label">
                <!-- Icon calendar-check -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM7 19l-3-3 1.41-1.41L7 16.17l8.59-8.59L17 9l-10 10z" />
                </svg>
                Tanggal Periksa
            </span>
            <span class="detail-value"><?= htmlspecialchars($data['tanggal_periksa']) ?></span>
        </li>
        <li>
            <span class="detail-label">
                <!-- Icon note -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M14 2H6c-1.1 0-2 .9-2 2v16l4-4h6c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z" />
                </svg>
                Catatan
            </span>
            <span class="detail-value catatan"><?= nl2br(htmlspecialchars($data['catatan'])) ?></span>
        </li>
    </ul>

    <a href="?page=data_kehamilan" class="btn-kembali">&larr; Kembali</a>
</div>


<style>
    /* Animasi fade-in */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .detail-card {
        max-width: 720px;
        margin: 40px auto;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        background-color: #ffffff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }

    .detail-title {
        text-align: center;
        font-weight: 700;
        color: #0f766e;
        margin-bottom: 32px;
        font-size: 2rem;
        animation: fadeInUp 0.7s ease forwards;
        opacity: 0;
    }

    .detail-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .detail-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0;
        border-bottom: 1px solid #e2e8f0;
        font-size: 1rem;
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
    }

    .detail-list li:nth-child(1) {
        animation-delay: 0.1s;
    }

    .detail-list li:nth-child(2) {
        animation-delay: 0.2s;
    }

    .detail-list li:nth-child(3) {
        animation-delay: 0.3s;
    }

    .detail-list li:nth-child(4) {
        animation-delay: 0.4s;
    }

    .detail-list li:nth-child(5) {
        animation-delay: 0.5s;
    }

    .detail-list li:nth-child(6) {
        animation-delay: 0.6s;
    }

    .detail-list li:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-weight: 600;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Icon styling */
    .detail-label svg {
        width: 20px;
        height: 20px;
        fill: #0f766e;
    }

    .detail-value {
        max-width: 60%;
        text-align: right;
        color: #1e293b;
        white-space: pre-line;
    }

    .detail-value.catatan {
        text-align: left;
        max-width: 100%;
    }

    .btn-kembali {
        display: block;
        width: max-content;
        margin: 40px auto 0;
        background-color: transparent;
        border: 2px solid #0f766e;
        color: #0f766e;
        font-weight: 600;
        padding: 10px 26px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        animation: fadeInUp 0.9s ease forwards;
        opacity: 0;
    }

    .btn-kembali:hover {
        background-color: #0f766e;
        color: white;
        box-shadow: 0 6px 12px rgba(15, 118, 110, 0.4);
    }

    @media (max-width: 480px) {
        .detail-card {
            padding: 20px 24px;
        }

        .detail-list li {
            flex-direction: column;
            align-items: flex-start;
            padding: 10px 0;
            text-align: left;
        }

        .detail-value {
            max-width: 100%;
            margin-top: 6px;
            text-align: left;
        }
    }
</style>