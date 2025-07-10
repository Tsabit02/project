<?php
include 'function.php';

// Total data
$totalHamil = $conn->query("SELECT COUNT(*) FROM ibu_hamil")->fetch_row()[0];
$totalBersalin = $conn->query("SELECT COUNT(*) FROM bersalin")->fetch_row()[0];

// Data ibu hamil per bulan
$query = "
    SELECT 
    DATE_FORMAT(tanggal_periksa, '%Y-%m') AS bulan, 
    COUNT(*) AS jumlah_ibu_hamil
    FROM ibu_hamil
    GROUP BY bulan
";
$stmt = $conn->query($query);
$data = [];
while ($row = $stmt->fetch_assoc()) {
    $data[] = $row;
}

// Data bersalin per bulan
$queryBersalin = "
    SELECT 
    DATE_FORMAT(tanggal_bersalin, '%Y-%m') AS bulan, 
    COUNT(*) AS jumlah_bersalin
    FROM bersalin
    GROUP BY bulan
";
$stmtBersalin = $conn->query($queryBersalin);
$dataBersalin = [];
while ($row = $stmtBersalin->fetch_assoc()) {
    $dataBersalin[] = $row;
}

// Gabungkan bulan dari dua tabel
$allBulan = array_unique(array_merge(
    array_column($data, 'bulan'),
    array_column($dataBersalin, 'bulan')
));
sort($allBulan);

// Map jumlah per bulan
$jumlahIbuHamilMap = array_column($data, 'jumlah_ibu_hamil', 'bulan');
$jumlahBersalinMap = array_column($dataBersalin, 'jumlah_bersalin', 'bulan');

$jumlahIbuHamilFinal = [];
$jumlahBersalinFinal = [];
foreach ($allBulan as $b) {
    $jumlahIbuHamilFinal[] = isset($jumlahIbuHamilMap[$b]) ? (int)$jumlahIbuHamilMap[$b] : 0;
    $jumlahBersalinFinal[] = isset($jumlahBersalinMap[$b]) ? (int)$jumlahBersalinMap[$b] : 0;
}
?>

<!-- CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container px-3">
    <h3 class="mb-4">📊 Dashboard</h3>

    <!-- Cards -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4" style="background-color: #0f766e;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">🤰🏻 Total Ibu Hamil</h5>
                            <p class="card-text fs-4 fw-semibold"><?= $totalHamil ?></p>
                        </div>
                        <i class="bi bi-people-fill fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4" style="background-color: #0f766e;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-1">👩🏻‍🍼 Total Data Persalinan</h5>
                            <p class="card-text fs-4 fw-semibold"><?= $totalBersalin ?></p>
                        </div>
                        <i class="bi bi-heart-pulse-fill fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="col-12">
            <div class="card p-3 shadow-sm border-0 rounded-4">
                <canvas id="grafikKehamilan" height="300"></canvas>
            </div>
        </div>

        <!-- Aktivitas -->
        <div class="card mt-4">
            <div class="card-header bg-white fw-bold">Aktivitas Terbaru</div>
            <div class="card-body">
                <ul id="aktivitasList" class="list-group list-group-flush">
                    <!-- Data aktivitas akan tampil di sini -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bulan = <?php echo json_encode($allBulan); ?>;
    const jumlahIbuHamil = <?php echo json_encode($jumlahIbuHamilFinal); ?>;
    const jumlahBersalin = <?php echo json_encode($jumlahBersalinFinal); ?>;

    const bulanLabel = bulan.map(b => {
        const parts = b.split('-');
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        return monthNames[parseInt(parts[1]) - 1] + ' ' + parts[0];
    });

    const ctx = document.getElementById('grafikKehamilan').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulanLabel,
            datasets: [
                {
                    label: 'Jumlah Ibu Hamil',
                    data: jumlahIbuHamil,
                    borderColor: '#0f766e',
                    backgroundColor: 'rgba(15, 118, 110, 0.2)',
                    tension: 0.3
                },
                {
                    label: 'Jumlah Persalinan',
                    data: jumlahBersalin,
                    borderColor: '#e11d48',
                    backgroundColor: 'rgba(225, 29, 72, 0.2)',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Grafik Ibu Hamil dan Persalinan per Bulan'
                }
            }
        }
    });
</script>

<!-- Aktivitas (jika pakai AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function fetchAktivitas() {
        fetch('./page/ajax/jq_aktivitas.php?filter=all')
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('aktivitasList');
                list.innerHTML = '';
                if (!data.length) {
                    list.innerHTML = '<li class="list-group-item text-muted">Tidak ada aktivitas.</li>';
                    return;
                }
                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item';
                    li.innerHTML = `<strong>${item.jenis}</strong>: ${item.keterangan} <br><small class="text-muted">${item.waktu}</small>`;
                    list.appendChild(li);
                });
            }).catch(err => console.error(err));
    }
    setInterval(fetchAktivitas, 10000);
    document.addEventListener('DOMContentLoaded', fetchAktivitas);
</script>
