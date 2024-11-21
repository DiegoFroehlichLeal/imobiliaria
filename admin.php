<?php
require 'includes/db.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $quartos = $_POST['quartos'] ?? 0;
    $banheiros = $_POST['banheiros'] ?? 0;
    $tamanho = $_POST['tamanho'] ?? 0;
    $vagas = $_POST['vagas'] ?? 0;
    $cidade = $_POST['cidade'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $preco = $_POST['preco'] ?? 0;

    $imagem_principal = $_FILES['imagem_principal']['name'] ?? null;
    $imagens = $_FILES['imagens'] ?? null;

    if ($imagem_principal) {
        $imagem_principal = uploadImagem($_FILES['imagem_principal']);
    }

    if ($imagens && is_array($imagens['name'])) {
        $imagens = uploadMultiplasImagens($_FILES['imagens']);
    }

    $sql = "INSERT INTO imoveis (titulo, descricao, quartos, banheiros, tamanho, vagas, cidade, bairro, endereco, preco, imagem_principal, imagens) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssiiissssiss',
        $titulo,
        $descricao,
        $quartos,
        $banheiros,
        $tamanho,
        $vagas,
        $cidade,
        $bairro,
        $endereco,
        $preco,
        $imagem_principal,
        $imagens
    );

    if ($stmt->execute()) {
        echo "Imóvel cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar imóvel: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Cadastro de Imóveis</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
</head>

<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Cadastro de Imóveis</h1>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="titulo" class="form-control" placeholder="Título do imóvel" required>
            </div>
            <div class="col-md-6">
                <input type="number" name="preco" class="form-control" placeholder="Preço" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="quartos" class="form-control" placeholder="Número de quartos" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="banheiros" class="form-control" placeholder="Número de banheiros" required>
            </div>
            <div class="col-md-4">
                <input type="number" name="vagas" class="form-control" placeholder="Vagas de garagem" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="cidade" class="form-control" placeholder="Cidade" required>
            </div>
            <div class="col-md-6">
                <input type="text" name="bairro" class="form-control" placeholder="Bairro" required>
            </div>
            <div class="col-12">
                <textarea name="descricao" class="form-control" placeholder="Descrição do imóvel" rows="4"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Imagem Principal</label>
                <input type="file" name="imagem_principal" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Outras Imagens</label>
                <input type="file" name="imagens[]" class="form-control" multiple>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Cadastrar Imóvel</button>
            </div>
        </form>
    </div>
</body>


</html>