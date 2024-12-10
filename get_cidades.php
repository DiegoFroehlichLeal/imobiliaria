<?php
require_once 'includes/db.php';

function getCidadesOrdenadas($cidades_prioritarias) {
    global $conn;

    // Obter as cidades distintas do banco de dados
    $query = "SELECT DISTINCT cidade FROM imoveis";
    $result = $conn->query($query);

    $cidades_db = [];
    while ($row = $result->fetch_assoc()) {
        $cidades_db[] = $row['cidade'];
    }

    // Remover possíveis duplicatas nas cidades prioritárias
    $cidades_prioritarias = array_unique($cidades_prioritarias);

    // Remover cidades prioritárias da lista obtida do banco de dados
    $cidades_restantes = array_diff($cidades_db, $cidades_prioritarias);

    // Ordenar as cidades restantes em ordem alfabética
    usort($cidades_restantes, function($a, $b) {
        $a_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
        $b_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
        return strcmp($a_normalized, $b_normalized);
    });

    // Combinar as cidades prioritárias com as demais
    $cidades_ordenadas = array_merge($cidades_prioritarias, $cidades_restantes);

    return $cidades_ordenadas;
}
?>