<?php
require_once 'includes/db.php';

function getTiposImoveis() {
    global $conn;

    $query = "SELECT DISTINCT tipo FROM imoveis";
    $result = $conn->query($query);

    $tipos = [];
    while ($row = $result->fetch_assoc()) {
        $tipos[] = $row['tipo'];
    }

    return $tipos;
}
?>