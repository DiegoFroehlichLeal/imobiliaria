<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Processamento do formulário
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
    $tipo = $_POST['tipo'] ?? '';

    $imagem_principal = $_FILES['imagem_principal']['name'] ?? null;
    $imagens = $_FILES['imagens'] ?? null;
    $banners = $_FILES['banners'] ?? null;

    // Cria a pasta para o imóvel
    $data_criacao = date('Ymd');
    $id = uniqid();
    $pasta_imovel = "uploads/{$id}_{$data_criacao}";
    if (!file_exists($pasta_imovel)) {
        mkdir($pasta_imovel, 0777, true);
    }

    if ($imagem_principal) {
        $imagem_principal = uploadImagem($_FILES['imagem_principal'], $pasta_imovel);
    }

    if ($imagens && is_array($imagens['name'])) {
        $imagens = uploadMultiplasImagens($_FILES['imagens'], $pasta_imovel);
    } else {
        $imagens = null;
    }

    if ($banners && is_array($banners['name'])) {
        $banners = uploadMultiplasImagens($_FILES['banners'], $pasta_imovel);
    } else {
        $banners = null;
    }

    if ($titulo && $descricao && $tipo && $quartos && $banheiros && $tamanho && $vagas && $cidade && $bairro && $endereco && $preco && $imagem_principal) {
        $sql = "INSERT INTO imoveis (titulo, descricao, tipo, quartos, banheiros, tamanho, vagas, cidade, bairro, endereco, preco, imagem_principal, imagens) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssiiissssiss',
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
            $imagens
        );

        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            echo "O imóvel foi cadastrado com sucesso, seu código é {$id} e seu título é {$titulo}.";
        } else {
            echo "Erro ao cadastrar imóvel: " . $conn->error;
        }
    } elseif ($banners) {
        echo "Os banners foram cadastrados com sucesso.";
    } else {
        echo "Erro: Preencha todos os campos obrigatórios para cadastrar um imóvel ou forneça banners.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Admin - Cadastro de Imóveis</title>
    <!-- Inclua o CSS do Bootstrap aqui -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-4">
        <h1 class="text-center mb-4">Cadastro de Imóveis</h1>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-6">
                <select name="tipo" class="form-control" required>
                    <option value="" disabled selected>Selecione o tipo do imóvel</option>
                    <option value="apartamento">Apartamento</option>
                    <option value="casa">Casa</option>
                    <option value="casa comercial">Casa Comercial</option>
                    <option value="sala comercial">Sala Comercial</option>
                    <option value="geminado">Geminado</option>
                    <option value="galpão">Galpão</option>
                    <option value="terreno">Terreno</option>
                    <option value="chácara">Chácara</option>
                    <option value="outros">Outros</option>
                </select>
            </div>
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
                <input type="number" name="tamanho" class="form-control" placeholder="Tamanho (m²)" required>
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
                <input type="text" name="endereco" class="form-control" placeholder="Endereço" required>
            </div>
            <div class="col-12">
                <textarea name="descricao" class="form-control" placeholder="Descrição do imóvel" rows="4"
                    required></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Imagem Principal</label>
                <input type="file" name="imagem_principal" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label">Outras Imagens</label>
                <input type="file" name="imagens[]" class="form-control" multiple>
            </div>
            <div class="col-12">
                <label class="form-label">Banners</label>
                <input type="file" name="banners[]" class="form-control" multiple>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>

        <h2 class="mt-5">Imóveis Cadastrados</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Cidade</th>
                    <th>Bairro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM imoveis");
                while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['titulo'] ?></td>
                        <td><?= $row['tipo'] ?></td>
                        <td><?= $row['cidade'] ?></td>
                        <td><?= $row['bairro'] ?></td>
                        <td>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Tem certeza que deseja excluir este imóvel?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Inclua o JS do Bootstrap aqui -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>

</html>