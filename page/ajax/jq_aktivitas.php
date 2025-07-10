<?php
header('Content-Type: application/json'); 
include '../../function.php';

$filter = $_GET['filter'] ?? 'all';
$query = "SELECT * FROM aktivitas";
if ($filter !== 'all') {
    $query .= " WHERE jenis = ?";
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare statement"]);
        exit;
    }
    $stmt->bind_param("s", $filter);
} else {
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare statement"]);
        exit;
    }
}
$stmt->execute();
if ($stmt->error) {
    echo json_encode(["error" => "Query execution failed: " . $stmt->error]);
    exit;
}
$result = $stmt->get_result();
$aktivitas = [];
while ($row = $result->fetch_assoc()) {
    $aktivitas[] = $row;
}
$stmt->close();
$conn->close();
echo json_encode($aktivitas);
