<?php
require_once 'includes/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do imóvel não fornecido.");
}

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

// Exclui o imóvel do banco de dados
$stmt = $conn->prepare('DELETE FROM imoveis WHERE id = ?');
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    echo "Imóvel excluído com sucesso!";
    // Redireciona para a página de administração após a exclusão
    header("Location: admin.php");
    exit;
} else {
    echo "Erro ao excluir imóvel: " . $conn->error;
}
?>
