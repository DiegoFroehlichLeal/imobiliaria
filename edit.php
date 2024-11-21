<?php
require 'includes/db.php';
require 'includes/functions.php';

// Verifica se o ID foi passado
if (!isset($_GET['id'])) {
    die("ID do imóvel não fornecido.");
}

$id = $_GET['id'];

// Busca os dados do imóvel
$sql = "SELECT * FROM imoveis WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Imóvel não encontrado.");
}

$imovel = $result->fetch_assoc();

// Atualização do imóvel
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $quartos = $_POST['quartos'];
    $banheiros = $_POST['banheiros'];
    $tamanho = $_POST['tamanho'];
    $vagas = $_POST['vagas'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $endereco = $_POST['endereco'];
    $preco = $_POST['preco'];

    // Atualiza a imagem principal se enviada
    if (!empty($_FILES['imagem_principal']['name'])) {
        $imagem_principal = salvarImagens(['name' => [$_FILES['imagem_principal']['name']], 'tmp_name' => [$_FILES['imagem_principal']['tmp_name']]]);
    } else {
        $imagem_principal = $imovel['imagem_principal'];
    }

    // Atualiza as imagens adicionais
    if (!empty($_FILES['imagens']['name'][0])) {
        $imagens = salvarImagens($_FILES['imagens']);
    } else {
        $imagens = $imovel['imagens'];
    }

    $sql = "UPDATE imoveis 
            SET titulo = '$titulo', descricao = '$descricao', quartos = $quartos, banheiros = $banheiros, 
                tamanho = $tamanho, vagas = $vagas, cidade = '$cidade', bairro = '$bairro', endereco = '$endereco', 
                preco = $preco, imagem_principal = '$imagem_principal', imagens = '$imagens' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Imóvel atualizado com sucesso!";
        header("Location: admin.php");
        exit();
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Editar Imóvel</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Título:</label>
        <input type="text" name="titulo" value="<?= $imovel['titulo'] ?>" required><br><br>

        <label>Descrição:</label>
        <textarea name="descricao" required><?= $imovel['descricao'] ?></textarea><br><br>

        <label>Quartos:</label>
        <input type="number" name="quartos" value="<?= $imovel['quartos'] ?>" required><br><br>

        <label>Banheiros:</label>
        <input type="number" name="banheiros" value="<?= $imovel['banheiros'] ?>" required><br><br>

        <label>Tamanho (m²):</label>
        <input type="number" name="tamanho" value="<?= $imovel['tamanho'] ?>" required><br><br>

        <label>Vagas de Garagem:</label>
        <input type="number" name="vagas" value="<?= $imovel['vagas'] ?>" required><br><br>

        <label>Cidade:</label>
        <input type="text" name="cidade" value="<?= $imovel['cidade'] ?>" required><br><br>

        <label>Bairro:</label>
        <input type="text" name="bairro" value="<?= $imovel['bairro'] ?>" required><br><br>

        <label>Endereço:</label>
        <textarea name="endereco" required><?= $imovel['endereco'] ?></textarea><br><br>

        <label>Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?= $imovel['preco'] ?>" required><br><br>

        <label>Imagem Principal:</label>
        <input type="file" name="imagem_principal"><br><br>
        <img src="<?= $imovel['imagem_principal'] ?>" alt="Imagem Principal" width="200"><br><br>

        <label>Imagens Adicionais:</label>
        <input type="file" name="imagens[]" multiple><br><br>
        <div>
            <?php 
            $imagens = json_decode($imovel['imagens'], true);
            foreach ($imagens as $img) {
                echo "<img src='$img' alt='Imagem Adicional' width='100' style='margin-right: 10px;'>";
            }
            ?>
        </div><br><br>

        <button type="submit">Salvar Alterações</button>
    </form>
</body>
</html>
