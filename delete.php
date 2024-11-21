<?php
require 'includes/db.php';

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do imóvel não fornecido.");
}

$id = $_GET['id'];

// Busca o imóvel
$sql = "SELECT * FROM imoveis WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Imóvel não encontrado.");
}

$imovel = $result->fetch_assoc();

// Remove as imagens do servidor
@unlink($imovel['imagem_principal']);
$imagens = json_decode($imovel['imagens'], true);
foreach ($imagens as $img) {
    @unlink($img);
}

// Remove o imóvel do banco
$sql = "DELETE FROM imoveis WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Imóvel excluído com sucesso!";
    header("Location: admin.php");
    exit();
} else {
    echo "Erro: " . $conn->error;
}
?>
