<?php
include 'function.php';

$sukses = false;
$aksi = '';

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM bersalin WHERE id_bersalin = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        // Tambahkan ke aktivitas
        $jenis = "bersalin";
        $keterangan = "Data persalinan dengan ID <strong>$id</strong> berhasil dihapus.";
        $log = $conn->prepare("INSERT INTO aktivitas (jenis, keterangan) VALUES (?, ?)");
        $log->bind_param("ss", $jenis, $keterangan);
        $log->execute();
        $log->close();

        $sukses = true;
        $aksi = 'Hapus';
    }
    $stmt->close();
}


if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO bersalin (nama_ibu, alamat, tanggal_bersalin, jenis_persalinan, jenis_kelamin, berat_bayi, catatan, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
    "sssssdss",
    $_POST['nama_ibu'],
    $_POST['alamat'],
    $_POST['tanggal_bersalin'],
    $_POST['jenis_persalinan'],
    $_POST['jenis_kelamin'],
    $_POST['berat_bayi'],
    $_POST['catatan'],
    $_POST['status']
);


    if ($stmt->execute()) {
        $nama = $_POST['nama_ibu'];
        $keterangan = "Proses persalinan atas nama <strong>$nama</strong> berhasil dicatat.";
        $jenis = "bersalin";

        $aktivitas = $conn->prepare("INSERT INTO aktivitas (jenis, keterangan) VALUES (?, ?)");
        $aktivitas->bind_param("ss", $jenis, $keterangan);
        $aktivitas->execute();
        $aktivitas->close();

        $sukses = true;
        $aksi = 'Tambah';
    }
    $stmt->close();
}

if (isset($_POST['update'])) {
    $stmt = $conn->prepare("UPDATE bersalin SET nama_ibu=?, alamat=?, tanggal_bersalin=?, jenis_persalinan=?, jenis_kelamin=?, berat_bayi=?, catatan=?, status=? WHERE id_bersalin=?");
    $stmt->bind_param(
        "ssssssssi",
        $_POST['nama_ibu'],
        $_POST['alamat'],
        $_POST['tanggal_bersalin'],
        $_POST['jenis_persalinan'],
        $_POST['jenis_kelamin'],
        $_POST['berat_bayi'],
        $_POST['catatan'],
        $_POST['status'],
        $_POST['id_bersalin'] // pastikan ini ada di <input type="hidden" ...>
    );

    if ($stmt->execute()) {
        $nama = $_POST['nama_ibu'];
        $keterangan = "Data persalinan atas nama <strong>$nama</strong> telah diperbarui.";
        $jenis = "bersalin";

        $aktivitas = $conn->prepare("INSERT INTO aktivitas (jenis, keterangan) VALUES (?, ?)");
        $aktivitas->bind_param("ss", $jenis, $keterangan);
        $aktivitas->execute();
        $aktivitas->close();

        $sukses = true;
        $aksi = 'Update';
    }

    $stmt->close();
}

?>


<!-- Bootstrap CSS dan Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


<!-- Judul Halaman -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">👩🏻‍🍼 Data Persalinan</h3>
    <button class="btn text-white" style="background-color: #0f766e;" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-circle me-1"></i> Tambah Data
    </button>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #0f766e;">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Data Persalinan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Bersalin</label>
                            <input type="date" name="tanggal_bersalin" class="form-control" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Persalinan</label>
                            <select name="jenis_persalinan" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Normal">Normal</option>
                                <option value="Caesar">Caesar</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin Bayi</label>
                            <select name="jenis_kelamin" class="form-select" required>
                                <option value="">Pilih...</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Berat Bayi (kg)</label>
                            <input type="number" step="0.01" name="berat_bayi" class="form-control" required />
                        </div>
                        
                        <div class="col-md-6 mb-3">
    <label class="form-label">Status Kelahiran</label>
    <select name="status" class="form-select" required>
        <option value="">Pilih...</option>
        <option value="Normal">Normal</option>
        <option value="Prematur">Prematur</option>
        
    </select>
</div>

                        <div class="col-md-6 mb-3">
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
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header text-white" style="background-color: #0f766e;">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Persaalinan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body row">
                    <input type="hidden" name="id_bersalin" id="edit-id_bersalin" />
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Ibu</label>
                        <input type="text" name="nama_ibu" id="edit-nama_ibu" class="form-control" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat" id="edit-alamat" class="form-control" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Bersalin</label>
                        <input type="date" name="tanggal_bersalin" id="edit-tanggal_bersalin" class="form-control" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Persalinan</label>
                        <select name="jenis_persalinan" id="edit-jenis_persalinan" class="form-select" required>
                            <option value="">Pilih...</option>
                            <option value="Normal">Normal</option>
                            <option value="Caesar">Caesar</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin Bayi</label>
                        <select name="jenis_kelamin" id="edit-jenis_kelamin" class="form-select" required>
                            <option value="">Pilih...</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berat Bayi (kg)</label>
                        <input type="number" step="0.01" name="berat_bayi" id="edit-berat_bayi" class="form-control" required />
                    </div>

                    <div class="col-md-6 mb-3">
    <label class="form-label">Status Kelahiran</label>
    <select name="status" id="edit-status" class="form-select" required>
        <option value="">Pilih...</option>
        <option value="Normal">Normal</option>
        <option value="Prematur">Prematur</option>
        
    </select>
</div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" id="edit-catatan" class="form-control" rows="3" placeholder="Isi catatan tambahan jika ada..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
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


<!-- Tabel Data -->
<div class="card">
    <div class="card-header text-white fw-semibold" style="background-color: #0f766e;">
        Daftar Data Persalinan
    </div>
    <div class="card-body p-0">
        <?php
        $query = "SELECT * FROM bersalin ORDER BY id_bersalin ASC";
        $result = $conn->query($query);
        ?>
        <table id="tableBersalin" class="table table-bordered table-hover mb-0 align-middle">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama</th>
                    <th>Tanggal Bersalin</th>
                    <th>Jenis Persalinan</th>
                    <th>Jenis Kelamin</th>
                    <th>Berat Bayi</th>
                    <th>Alamat</th>
                    <th>Catatan</th>
                    <th>Status</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0):
                    $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_ibu']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_bersalin']); ?></td>
                            <td><?= htmlspecialchars($row['jenis_persalinan']); ?></td>
                            <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                            <td><?= htmlspecialchars($row['berat_bayi']); ?> kg</td>
                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                            <td><?= htmlspecialchars($row['catatan']); ?></td>
                            <td><?= htmlspecialchars($row['status']); ?></td>

                            <td class="text-center">
                                <button
    class="btn btn-sm btn-warning me-1 btn-edit"
    data-id="<?= $row['id_bersalin']; ?>"
    data-nama="<?= htmlspecialchars($row['nama_ibu']); ?>"
    data-alamat="<?= htmlspecialchars($row['alamat']); ?>"
    data-tanggal="<?= $row['tanggal_bersalin']; ?>"
    data-jenis_persalinan="<?= $row['jenis_persalinan']; ?>"
    data-jenis_kelamin="<?= $row['jenis_kelamin']; ?>"
    data-berat="<?= $row['berat_bayi']; ?>"
    data-catatan="<?= $row['catatan']; ?>"
    data-status="<?= $row['status']; ?>"
    title="Edit"
    data-bs-toggle="modal" data-bs-target="#modalEdit">
    <i class="bi bi-pencil-square"></i>
</button>

                                <a href="javascript:void(0);" onclick="confirmDelete('?page=data_bersalin&hapus=<?= $row['id_bersalin'] ?>')" class="btn btn-sm btn-danger" title="Hapus">
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
                                <a href="?page=detail_bersalin&id_bersalin=<?= $row['id_bersalin'] ?>" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-file-text"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile;
                else: ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
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
            window.location.href = 'index.php?page=data_bersalin';
        });
    <?php endif; ?>
</script>

<script>
    // Saat tombol edit diklik, isi form edit dengan data dari row
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit-id_bersalin').value = this.dataset.id;
            document.getElementById('edit-nama_ibu').value = this.dataset.nama;
            document.getElementById('edit-alamat').value = this.dataset.alamat;
            document.getElementById('edit-tanggal_bersalin').value = this.dataset.tanggal;
            document.getElementById('edit-jenis_persalinan').value = this.dataset.jenis_persalinan;
            document.getElementById('edit-jenis_kelamin').value = this.dataset.jenis_kelamin;
            document.getElementById('edit-berat_bayi').value = this.dataset.berat;
            document.getElementById('edit-status').value = this.dataset.status;
            document.getElementById('edit-catatan').value = this.dataset.catatan;
        });
    });


    // Export Excel
    function exportExcel() {
        var table = document.getElementById('tableBersalin');
        var wb = XLSX.utils.table_to_book(table, {
            sheet: "Databersalin"
        });
        XLSX.writeFile(wb, 'data_bersalin.xlsx');
    }

    

    // Export PDF
    function exportPDF() {
        const {
            jsPDF
        } = window.jspdf;
        var doc = new jsPDF();
        doc.text("Data Bersalin", 14, 16);
        var table = document.getElementById('tableBersalin');
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
        doc.save("data_Bersalin.pdf");
    }
</script>

<style>
    /* Card wrapper */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .card-header {
        font-size: 1.1rem;
        font-weight: 600;
        background-color: #0f766e !important;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    /* Table styling */
    #tableBersalin {
        border-collapse: separate;
        border-spacing: 0;
    }

    #tableBersalin th,
    #tableBersalin td {
        padding: 12px;
        vertical-align: middle !important;
        font-size: 0.95rem;
    }

    #tableBersalin thead {
        background-color: #e6fffa;
        color: #0f766e;
    }

    #tableBersalin tbody tr:hover {
        background-color: #f1f5f9;
        transition: background-color 0.3s ease;
    }

    /* Buttons */
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

    .btn-info {
        background-color: #38bdf8;
        border: none;
        color: #fff;
    }

    .btn:hover {
        opacity: 0.9;
    }

    .text-center .btn {
        margin: 0 2px;
    }

    td.text-center {
        display: flex;
        justify-content: center;
        gap: 6px;
        align-items: center;
    }

    td.text-center .btn {
        padding: 6px 8px;
        min-width: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    td.text-center .btn i {
        font-size: 1rem;
    }


    @media screen and (max-width: 768px) {

        #tableBersalin th,
        #tableBersalin td {
            font-size: 0.85rem;
            padding: 10px;
        }
    }
</style>