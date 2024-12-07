<?php
require_once 'includes/db.php';

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

$codigo = isset($_GET['codigo']) ? sanitize_input($_GET['codigo']) : null;
$cidade = isset($_GET['cidade']) ? sanitize_input($_GET['cidade']) : null;
$bairro = isset($_GET['bairro']) ? sanitize_input($_GET['bairro']) : null;
$precoMin = isset($_GET['min_price']) ? sanitize_input($_GET['min_price']) : null;
$precoMax = isset($_GET['max_price']) ? sanitize_input($_GET['max_price']) : null;
$tipo = isset($_GET['tipo']) ? sanitize_input($_GET['tipo']) : null;
$page = isset($_GET['page']) ? (int)sanitize_input($_GET['page']) : 1;
$limit = 12; // Número de resultados por página
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM imoveis WHERE 1=1";
$params = [];
$types = '';

if ($codigo) {
    $query .= " AND id = ?";
    $params[] = $codigo;
    $types .= 'i';
}
if ($cidade) {
    $query .= " AND cidade LIKE ?";
    $params[] = "%$cidade%";
    $types .= 's';
}
if ($bairro) {
    $query .= " AND bairro LIKE ?";
    $params[] = "%$bairro%";
    $types .= 's';
}
if ($precoMin) {
    $query .= " AND preco >= ?";
    $params[] = $precoMin;
    $types .= 'd';
}
if ($precoMax) {
    $query .= " AND preco <= ?";
    $params[] = $precoMax;
    $types .= 'd';
}
if ($tipo) {
    $query .= " AND tipo = ?";
    $params[] = $tipo;
    $types .= 's';
}

$query .= " ORDER BY data_cadastro DESC";

$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($query);

if ($types) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$imoveis = [];
while ($row = $result->fetch_assoc()) {
    $imoveis[] = $row;
}

echo json_encode($imoveis);
?>
