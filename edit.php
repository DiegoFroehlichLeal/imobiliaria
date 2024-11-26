<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Verifica se o ID foi passado
$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do imóvel não fornecido.");
}

// Busca os dados do imóvel
$stmt = $conn->prepare('SELECT * FROM imoveis WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$imovel = $result->fetch_assoc();

if (!$imovel) {
    die("Imóvel não encontrado.");
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $quartos = $_POST['quartos'] ?? 0;
    $banheiros = $_POST['banheiros'] ?? 0;
    $tamanho = $_POST['tamanho'] ?? 0;
    $vagas = $_POST['vagas'] ?? 0;
    $cidade = $_POST['cidade'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $preco = $_POST['preco'] ?? 0;

    // Atualiza a imagem principal se houver novo upload
    if ($_FILES['imagem_principal']['name']) {
        $imagem_principal = uploadImagem($_FILES['imagem_principal'], $pasta_imovel);
    } else {
        $imagem_principal = $imovel['imagem_principal'];
    }

    // Atualiza as outras imagens se houver novo upload
    if ($_FILES['imagens']['name'][0]) {
        $imagens = uploadMultiplasImagens($_FILES['imagens'], $pasta_imovel);
    } else {
        $imagens = $imovel['imagens'];
    }

    // Atualiza o imóvel no banco de dados
    $sql = "UPDATE imoveis SET titulo = ?, descricao = ?, tipo = ?, quartos = ?, banheiros = ?, tamanho = ?, vagas = ?, cidade = ?, bairro = ?, endereco = ?, preco = ?, imagem_principal = ?, imagens = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'sssiiissssissi',
        $titulo,
        $descricao,
        $tipo,
        $quartos,
        $banheiros,
        $tamanho,
        $vagas,
        $cidade,
        $bairro,
        $endereco,
        $preco,
        $imagem_principal,
        $imagens,
        $id
    );

    if ($stmt->execute()) {
        echo "Imóvel atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar imóvel: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Imóvel</title>
    <!-- Inclua o CSS do Bootstrap aqui -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Editar Imóvel</h1>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <!-- Campos do formulário preenchidos com os dados do imóvel -->
            <!-- Exemplo do campo Título -->
            <div class="col-md-6">
                <input type="text" name="titulo" class="form-control" value="<?= $imovel['titulo'] ?>" required>
            </div>
            <!-- Campo Tipo -->
            <div class="col-md-6">
                <select name="tipo" class="form-control" required>
                    <option value="" disabled>Selecione o tipo do imóvel</option>
                    <option value="apartamento" <?= $imovel['tipo'] == 'apartamento' ? 'selected' : '' ?>>Apartamento</option>
                    <!-- Repita para os demais tipos -->
                </select>
            </div>
            <!-- Adicione o restante dos campos seguindo o padrão -->
            <!-- Botão de envio -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
    <!-- Inclua o JS do Bootstrap aqui -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
