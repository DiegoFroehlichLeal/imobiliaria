<?php
require 'includes/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare('SELECT * FROM imoveis WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $property = $result->fetch_assoc();

    if ($property) {
        // Decodifica as imagens adicionais
        $property['imagens'] = json_decode($property['imagens'], true) ?? [];

        echo json_encode($property);
    } else {
        echo json_encode(['error' => 'Imóvel não encontrado.']);
    }
} else {
    echo json_encode(['error' => 'ID não fornecido.']);
}