<?php
require_once 'includes/db.php';

function getBairrosPorCidade($cidades_importantes) {
    global $conn;

    // Obter os bairros disponíveis no banco de dados, agrupados por cidade
    $query = "SELECT DISTINCT cidade, bairro FROM imoveis";
    $result = $conn->query($query);

    $bairros_por_cidade = [];
    while ($row = $result->fetch_assoc()) {
        $cidade = $row['cidade'];
        $bairro = $row['bairro'];
        if (!isset($bairros_por_cidade[$cidade])) {
            $bairros_por_cidade[$cidade] = [];
        }
        if (!in_array($bairro, $bairros_por_cidade[$cidade])) {
            $bairros_por_cidade[$cidade][] = $bairro;
        }
    }

    // Ordenar as cidades por ordem de importância
    $cidades_no_banco = array_keys($bairros_por_cidade);
    $cidades_restantes = array_diff($cidades_no_banco, $cidades_importantes);
    usort($cidades_restantes, function($a, $b) {
        $a_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
        $b_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
        return strcmp($a_normalized, $b_normalized);
    });

    $cidades_ordenadas = array_merge($cidades_importantes, $cidades_restantes);

    // Ordenar os bairros dentro de cada cidade
    $bairros_por_cidade_ordenado = [];
    foreach ($cidades_ordenadas as $cidade) {
        if (isset($bairros_por_cidade[$cidade])) {
            $bairros = $bairros_por_cidade[$cidade];
            usort($bairros, function($a, $b) {
                $a_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $a);
                $b_normalized = iconv('UTF-8', 'ASCII//TRANSLIT', $b);
                return strcmp($a_normalized, $b_normalized);
            });
            $bairros_por_cidade_ordenado[$cidade] = $bairros;
        }
    }

    return $bairros_por_cidade_ordenado;
}
?>