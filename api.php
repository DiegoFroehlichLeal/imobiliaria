<?php
require_once 'includes/db.php';

// Captura os filtros
$cidade = $_GET['cidade'] ?? null;
$bairro = $_GET['bairro'] ?? null;
$precoMin = $_GET['min_price'] ?? null;
$precoMax = $_GET['max_price'] ?? null;

// Prepara a consulta com filtros dinâmicos
$query = "SELECT * FROM imoveis WHERE 1=1";
$params = [];

if ($cidade) {
    $query .= " AND cidade LIKE ?";
    $params[] = "%$cidade%";
}
if ($bairro) {
    $query .= " AND bairro LIKE ?";
    $params[] = "%$bairro%";
}
if ($precoMin) {
    $query .= " AND preco >= ?";
    $params[] = $precoMin;
}
if ($precoMax) {
    $query .= " AND preco <= ?";
    $params[] = $precoMax;
}

// Executa a consulta
$stmt = $conn->prepare($query);
$stmt->execute($params);
$result = $stmt->get_result();

$imoveis = [];
while ($row = $result->fetch_assoc()) {
    $imoveis[] = $row;
}

echo json_encode($imoveis);
