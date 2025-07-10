<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include '../../function.php';

if (!isset($_GET['id_hamil'])) {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

$id_hamil = intval($_GET['id_hamil']);

// Ambil data ibu hamil
$sql = "SELECT * FROM ibu_hamil WHERE id_hamil = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_hamil);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Query error: ' . $conn->error]);
    exit;
}

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}



if (!isset($_GET['id_hamil'])) {
    echo json_encode(['success' => false, 'message' => 'ID tidak ditemukan']);
    exit;
}

$id_hamil = intval($_GET['id_hamil']);

// Ambil data ibu hamil berdasarkan ID
$sql = "SELECT * FROM ibu_hamil WHERE id_hamil = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_hamil);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    exit;
}

$data = $result->fetch_assoc();

// Ambil data yang diperlukan dari tabel ibu_hamil
$nama_ibu = $data['nama'];
$alamat = $data['alamat'];
$tanggal_bersalin = date('Y-m-d');
$usia_kehamilan = $data['usia_kehamilan'];  // Asumsi usia kehamilan disimpan di kolom 'usia_kehamilan'

// Tentukan status kehamilan berdasarkan usia kehamilan
$status_kehamilan = ($usia_kehamilan >= 37) ? 'Normal' : 'Prematur';

// Set nilai NULL untuk kolom yang belum diisi
$jenis_persalinan = null;
$jenis_kelamin = null;
$berat_bayi = null;

// Query insert ke tabel bersalin
$insert_sql = "INSERT INTO bersalin (nama_ibu, alamat, tanggal_bersalin, jenis_persalinan, jenis_kelamin, berat_bayi, status) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);

if (!$insert_stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}

// Bind parameter
$insert_stmt->bind_param(
    'sssssss',  // 's' untuk string, 's' untuk nullable string, 'd' untuk double
    $nama_ibu,
    $alamat,
    $tanggal_bersalin,
    $jenis_persalinan,  // NULL jika belum diisi
    $jenis_kelamin,     // NULL jika belum diisi
    $berat_bayi,        // NULL jika belum diisi
    $status_kehamilan   // Status kehamilan (Normal atau Prematur)
);

if (!$insert_stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Gagal insert ke data persalinan: ' . $insert_stmt->error]);
    exit;
}

$insert_stmt->close();

// Hapus data ibu hamil dari tabel ibu_hamil
$delete_sql = "DELETE FROM ibu_hamil WHERE id_hamil = ?";
$delete_stmt = $conn->prepare($delete_sql);
if (!$delete_stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $conn->error]);
    exit;
}
$delete_stmt->bind_param('i', $id_hamil);
if (!$delete_stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Gagal hapus data ibu hamil: ' . $delete_stmt->error]);
    exit;
}
$delete_stmt->close();

echo json_encode(['success' => true]);
