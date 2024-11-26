<?php
require_once 'includes/db.php';

// Captura os filtros
$codigo = $_GET['codigo'] ?? null;
$cidade = $_GET['cidade'] ?? null;
$bairro = $_GET['bairro'] ?? null;
$precoMin = $_GET['min_price'] ?? null;
$precoMax = $_GET['max_price'] ?? null;

// Prepara a consulta com filtros dinâmicos
$query = "SELECT * FROM imoveis WHERE 1=1";
$params = [];

if ($codigo) {
    $query .= " AND id = ?";
    $params[] = $codigo;
}
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

// Adiciona ordenação por data de cadastro (mais recentes primeiro)
$query .= " ORDER BY data_cadastro DESC";

// Se algum filtro foi aplicado, limita a 6 resultados
if (!empty($params)) {
    $query .= " LIMIT 6";
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    // Define os tipos dos parâmetros
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$imoveis = [];
while ($row = $result->fetch_assoc()) {
    $imoveis[] = $row;
}

echo json_encode($imoveis);
