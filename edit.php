<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$tipos_imoveis_fixos = ['Casa', 'Apartamento', 'Geminado', 'Terreno', 'Galpão', 'Chácara', 'Sítio'];

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
            <!-- Campo Título -->
            <div class="col-md-6">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" class="form-control" value="<?= $imovel['titulo'] ?>" required>
            </div>
            <!-- Campo Tipo -->
            <div class="col-md-6">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" class="form-control" required>
                    <option value="" disabled>Selecione o tipo do imóvel</option>
                    <?php foreach ($tipos_imoveis_fixos as $tipo): ?>
                        <option value="<?= $tipo ?>" <?= $imovel['tipo'] == $tipo ? 'selected' : '' ?>>
                            <?= $tipo ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Campo Descrição -->
            <div class="col-12">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" class="form-control" rows="4" required><?= $imovel['descricao'] ?></textarea>
            </div>
            <!-- Campo Quartos -->
            <div class="col-md-4">
                <label for="quartos" class="form-label">Quartos</label>
                <input type="number" name="quartos" class="form-control" value="<?= $imovel['quartos'] ?>" required>
            </div>
            <!-- Campo Banheiros -->
            <div class="col-md-4">
                <label for="banheiros" class="form-label">Banheiros</label>
                <input type="number" name="banheiros" class="form-control" value="<?= $imovel['banheiros'] ?>" required>
            </div>
            <!-- Campo Tamanho -->
            <div class="col-md-4">
                <label for="tamanho" class="form-label">Tamanho (m²)</label>
                <input type="number" name="tamanho" class="form-control" value="<?= $imovel['tamanho'] ?>" required>
            </div>
            <!-- Campo Vagas -->
            <div class="col-md-4">
                <label for="vagas" class="form-label">Vagas de Garagem</label>
                <input type="number" name="vagas" class="form-control" value="<?= $imovel['vagas'] ?>" required>
            </div>
            <!-- Campo Cidade -->
            <div class="col-md-4">
                <label for="cidade" class="form-label">Cidade</label>
                <input type="text" name="cidade" class="form-control" value="<?= $imovel['cidade'] ?>" required>
            </div>
            <!-- Campo Bairro -->
            <div class="col-md-4">
                <label for="bairro" class="form-label">Bairro</label>
                <input type="text" name="bairro" class="form-control" value="<?= $imovel['bairro'] ?>" required>
            </div>
            <!-- Campo Endereço -->
            <div class="col-12">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control" value="<?= $imovel['endereco'] ?>" required>
            </div>
            <!-- Campo Preço -->
            <div class="col-12">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" name="preco" class="form-control" value="<?= $imovel['preco'] ?>" required>
            </div>
            <!-- Campo Imagem Principal -->
            <div class="col-12">
                <label for="imagem_principal" class="form-label">Imagem Principal</label>
                <input type="file" name="imagem_principal" class="form-control">
            </div>
            <!-- Campo Outras Imagens -->
            <div class="col-12">
                <label for="imagens" class="form-label">Outras Imagens</label>
                <input type="file" name="imagens[]" class="form-control" multiple>
            </div>
            <!-- Botão de Envio -->
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
    <!-- Inclua o JS do Bootstrap aqui -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>
