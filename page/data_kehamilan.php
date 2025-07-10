<?php
include 'function.php';

$sukses = false;
$aksi = '';


if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO ibu_hamil (nama, alamat, usia, usia_kehamilan, tanggal_periksa, catatan) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssiiss",
        $_POST['nama'],
        $_POST['alamat'],
        $_POST['usia'],
        $_POST['usia_kehamilan'],
        $_POST['tanggal_periksa'],
        $_POST['catatan']
    );

    if ($stmt->execute()) {
        // Tambahkan ke tabel aktivitas
        $nama = $_POST['nama'];
        $keterangan = "Pemeriksaan ibu hamil atas nama <strong>$nama</strong> berhasil ditambahkan.";
        $jenis = "pemeriksaan";

        $aktivitas = $conn->prepare("INSERT INTO aktivitas (jenis, keterangan) VALUES (?, ?)");
        $aktivitas->bind_param("ss", $jenis, $keterangan);
        $aktivitas->execute();
        $aktivitas->close();

        $sukses = true;
        $aksi = 'Tambah';
    }

    $stmt->close();
}

if (isset($_GET['hapus'])) {
    $stmt = $conn->prepare("DELETE FROM ibu_hamil WHERE id_hamil = ?");
    $stmt->bind_param("i", $_GET['hapus']);
    $stmt->execute();
    $sukses = true;
    $aksi = 'Hapus';
}

if (isset($_POST['update'])) {
    // Update query dan bind_param harusnya seperti ini:
    $stmt = $conn->prepare("UPDATE ibu_hamil SET nama=?, alamat=?, usia=?, usia_kehamilan=?, tanggal_periksa=?, catatan=? WHERE id_hamil=?");
    $stmt->bind_param("ssiissi", $_POST['nama'], $_POST['alamat'], $_POST['usia'], $_POST['usia_kehamilan'], $_POST['tanggal_periksa'], $_POST['catatan'], $_POST['id_hamil']);
    $stmt->execute();
    $sukses = true;
    $aksi = 'Update';
}

?>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">




<!-- Judul Halaman -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">🤰🏻 Data Ibu Hamil</h3>
    <button class="btn text-white" style="background-color: #0f766e;" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i> Tambah Data
    </button>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #0f766e;">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Data Ibu Hamil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia (tahun)</label>
                            <input type="number" name="usia" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Usia Kehamilan (minggu)</label>
                            <input type="number" name="usia_kehamilan" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Periksa</label>
                            <input type="date" name="tanggal_periksa" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="3" placeholder="Isi catatan tambahan jika ada..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn text-white" style="background-color: #0f766e;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header text-white" style="background-color: #0f766e;">
                    <h5 class="modal-title">Edit Data Ibu Hamil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" name="id_hamil" id="edit-id_hamil">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" id="edit-nama" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="edit-alamat" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usia (tahun)</label>
                        <input type="number" name="usia" id="edit-usia" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usia Kehamilan (minggu)</label>
                        <input type="number" name="usia_kehamilan" id="edit-usia_kehamilan" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Periksa</label>
                        <input type="date" name="tanggal_periksa" id="edit-tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" id="edit-catatan" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update" class="btn text-white" style="background-color: #0f766e;">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="mb-3">
    <button class="btn btn-success btn-sm" onclick="exportExcel()">Export Excel</button>
   
    <button class="btn btn-danger btn-sm" onclick="exportPDF()">Export PDF</button>
</div>


<!-- Tabel -->
<div class="card">
    <div class="card-header text-white" style="background-color: #0f766e;">Daftar Ibu Hamil</div>
    <div class="card-body p-0">
        <?php
        $query = "SELECT * FROM ibu_hamil ORDER BY id_hamil ASC";
        $result = $conn->query($query);
        ?>

        <table id="tableIbuHamil" class="table table-bordered table-hover mb-0">
            <thead class="table-light">
                <tr class="text-center">
                    <th style="width: 5%;">No</th>
                    <th>Nama</th>
                    <th>Usia Kehamilan</th>
                    <th>Usia</th>
                    <th>Tanggal Periksa</th>
                    <th>Alamat</th>
                    <th>Catatan</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr data-id="<?= $row['id_hamil'] ?>">
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['usia_kehamilan']) ?> minggu</td>
                            <td><?= htmlspecialchars($row['usia']) ?> tahun</td>
                            <td><?= htmlspecialchars($row['tanggal_periksa']) ?></td>
                            <td><?= htmlspecialchars($row['alamat']) ?></td>
                            <td><?= htmlspecialchars($row['catatan']) ?></td>
                            <td class="text-center d-flex justify-content-center gap-2">
                                <button
                                    class="btn btn-sm btn-warning"
                                    title="Edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit"
                                    data-id="<?= $row['id_hamil'] ?>"
                                    data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                    data-alamat="<?= htmlspecialchars($row['alamat']) ?>"
                                    data-catatan="<?= htmlspecialchars($row['catatan']) ?>"
                                    data-usia_kehamilan="<?= $row['usia_kehamilan'] ?>"
                                    data-usia="<?= $row['usia'] ?>"
                                    data-tanggal="<?= $row['tanggal_periksa'] ?>">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="javascript:void(0);" onclick="confirmDelete('?page=data_kehamilan&hapus=<?= $row['id_hamil'] ?>')" class="btn btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <script>
                                    function confirmDelete(url) {
                                        Swal.fire({
                                            title: 'Yakin?',
                                            text: "Data tidak dapat dikembalikan!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#d33',
                                            cancelButtonColor: '#aaa',
                                            confirmButtonText: 'Ya, hapus!'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                window.location.href = url;
                                            }
                                        });
                                    }
                                </script>
                                <button class="btn btn-sm btn-success" onclick="transferData(<?= $row['id_hamil']; ?>)" title="Transfer ke Bersalin">
                                    <i class="bi bi-box-arrow-in-up"></i>
                                </button>
                                <a href="?page=detail_kehamilan&id_hamil=<?= $row['id_hamil'] ?>" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-file-text"></i>
                                </a>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<!-- Bootstrap JS (Wajib untuk modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Export File -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>




<script>
    <?php if ($sukses): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil di<?= $aksi ?>.',
            confirmButtonColor: '#0f766e'
        }).then(() => {
            window.location.href = 'index.php?page=data_kehamilan';
        });
    <?php endif; ?>
</script>


<script>
    // Isi otomatis data edit
    const modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;

        document.getElementById('edit-id_hamil').value = button.getAttribute('data-id');
        document.getElementById('edit-nama').value = button.getAttribute('data-nama');
        document.getElementById('edit-alamat').value = button.getAttribute('data-alamat');
        document.getElementById('edit-usia').value = button.getAttribute('data-usia');
        document.getElementById('edit-usia_kehamilan').value = button.getAttribute('data-usia_kehamilan');
        document.getElementById('edit-tanggal').value = button.getAttribute('data-tanggal');
        document.getElementById('edit-catatan').value = button.getAttribute('data-catatan');
    });

    // Aksi Transfer data
    function transferData(id) {
        const row = document.querySelector(`#tableIbuHamil tr[data-id="${id}"]`);
        const usiaKehamilan = row.querySelector('td:nth-child(3)').innerText; // Ambil usia kehamilan dari kolom ke-3
        const statusKehamilan = parseInt(usiaKehamilan) >= 37 ? 'Normal' : 'Prematur'; // Tentukan status

        // Konfirmasi apakah ingin memindahkan data
        if (confirm(`Yakin ingin memindahkan data ini ke Data Persalinan? Usia Kehamilan: ${statusKehamilan}`)) {
            fetch(`./page/ajax/jq_transdata.php?id_hamil=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data berhasil dipindah ke Data Persalinan.',
                            confirmButtonColor: '#0f766e'
                        }).then(() => {
                            location.reload(); // Reload untuk update tampilan
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal memindahkan data: ' + data.message,
                            confirmButtonColor: '#0f766e'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memindahkan data: ' + err.message,
                        confirmButtonColor: '#0f766e'
                    });
                });
        }
    }



    // Export Excel
    function exportExcel() {
        var table = document.getElementById('tableIbuHamil');
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "DataIbuHamil"
        });
        XLSX.writeFile(wb, 'data_ibu_hamil.xlsx');
    }

 

    // Export PDF
    function exportPDF() {
        const {
            jsPDF
        } = window.jspdf;
        var doc = new jsPDF();
        doc.text("Data Ibu Hamil", 14, 16);
        var table = document.getElementById("tableIbuHamil");
        var headers = [];
        var ths = table.querySelectorAll("thead tr th");
        ths.forEach(function(th) {
            headers.push(th.innerText.trim());
        });
        var data = [];
        var trs = table.querySelectorAll("tbody tr");
        trs.forEach(function(tr) {
            var rowData = [];
            tr.querySelectorAll("td").forEach(function(td) {
                rowData.push(td.innerText.trim());
            });
            data.push(rowData);
        });
        doc.autoTable({
            head: [headers],
            body: data,
            startY: 20
        });
        doc.save("data_ibu_hamil.pdf");
    }
</script>

<style>
    /* Table container style */
    .card {
        border: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
    }

    .card-header {
        font-weight: 600;
        font-size: 1.1rem;
        background-color: #0f766e !important;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    #tableIbuHamil {
        border-collapse: separate;
        border-spacing: 0;
    }

    #tableIbuHamil thead {
        background-color: #f0fdf4;
        color: #0f766e;
    }

    #tableIbuHamil th,
    #tableIbuHamil td {
        vertical-align: middle !important;
        padding: 12px;
        font-size: 0.95rem;
    }

    #tableIbuHamil tbody tr:hover {
        background-color: #f8fafc;
        transition: background-color 0.3s ease;
    }

    .btn-sm {
        padding: 6px 8px;
        font-size: 0.8rem;
        border-radius: 6px;
    }

    .btn-warning {
        background-color: #facc15;
        border: none;
        color: #000;
    }

    .btn-danger {
        background-color: #ef4444;
        border: none;
    }

    .btn-success {
        background-color: #10b981;
        border: none;
    }

    .btn-info {
        background-color: #38bdf8;
        border: none;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .text-center.gap-2>* {
        margin: 0 2px;
    }

    @media screen and (max-width: 768px) {

        #tableIbuHamil th,
        #tableIbuHamil td {
            font-size: 0.85rem;
            padding: 10px;
        }
    }
</style>