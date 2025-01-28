<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
require_once 'includes/db.php';
require_once 'includes/functions.php';

$tipos_imoveis_fixos = ['Casa', 'Apartamento', 'Geminado', 'Terreno', 'Galpão', 'Chácara', 'Sítio'];

// TODO: 1- Colocar loading no upload de arquivos
// Adicionar um indicador de carregamento durante o upload de arquivos para melhorar a experiência do usuário.

// TODO: 2- Criar banner de vídeo
// Adicionar um banner de vídeo na página inicial para destacar os imóveis de forma mais interativa.

// TODO: 3- Melhorar mensagem de erro no cadastro de novo imóvel
// Tornar as mensagens de erro mais detalhadas e amigáveis ao usuário durante o processo de cadastro de um novo imóvel.

// TODO: 4- Aplicar as melhorias também ao editar e excluir um imóvel
// Garantir que as melhorias feitas no cadastro de imóveis sejam aplicadas também nas funcionalidades de edição e exclusão de imóveis.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura dos dados do formulário
    $titulo = $_POST['titulo'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    // Verificação dos campos obrigatórios
    if ($titulo && $tipo && isset($_FILES['imagem_principal']) && $_FILES['imagem_principal']['error'] === UPLOAD_ERR_OK) {
        // Campos opcionais com valores padrão ou NULL
        $quartos = isset($_POST['quartos']) && $_POST['quartos'] !== '' ? $_POST['quartos'] : 0;
        $banheiros = isset($_POST['banheiros']) && $_POST['banheiros'] !== '' ? $_POST['banheiros'] : 0;
        $vagas = isset($_POST['vagas']) && $_POST['vagas'] !== '' ? $_POST['vagas'] : 0;
        $preco = isset($_POST['preco']) && $_POST['preco'] !== '' ? $_POST['preco'] : 'Entre em contato';
        $endereco = $_POST['endereco'] ?? '';
        $descricao = $_POST['descricao'] ?? '';
        $cidade = $_POST['cidade'] ?? '';
        $bairro = $_POST['bairro'] ?? '';
        $tamanho = isset($_POST['tamanho']) && $_POST['tamanho'] !== '' ? $_POST['tamanho'] : null;

        // Cria a pasta para o imóvel
        $data_criacao = date('Ymd');
        $id = uniqid();
        $pasta_imovel = "uploads/{$id}_{$data_criacao}";
        if (!file_exists($pasta_imovel)) {
            mkdir($pasta_imovel, 0777, true);
        }

        // Processamento da imagem principal
        $imagem_principal = uploadImagem($_FILES['imagem_principal'], $pasta_imovel);

        // Processamento das imagens adicionais, se houver
        $imagens = $_FILES['imagens'] ?? null;
        if ($imagens && is_array($imagens['name'])) {
            $imagens = uploadMultiplasImagens($_FILES['imagens'], $pasta_imovel);
            $imagens_json = json_encode($imagens, JSON_UNESCAPED_SLASHES);
        } else {
            $imagens_json = null;
        }

        // Inserção no banco de dados
        $sql = "INSERT INTO imoveis (titulo, descricao, tipo, quartos, banheiros, tamanho, vagas, cidade, bairro, endereco, preco, imagem_principal, imagens) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            'sssiiidssssss',
            $titulo,            // s
            $descricao,         // s
            $tipo,              // s
            $quartos,           // i
            $banheiros,         // i
            $tamanho,           // d
            $vagas,             // i
            $cidade,            // s
            $bairro,            // s
            $endereco,          // s
            $preco,             // s
            $imagem_principal,  // s
            $imagens_json       // s
        );

        $conn->begin_transaction();

        if ($stmt->execute()) {
            $lastId = $conn->insert_id;

            // Verifica cada arquivo de imagem para garantir que foi salvo
            $todasImagensOk = true;
            if ($imagem_principal && !file_exists($imagem_principal)) {
                $todasImagensOk = false;
            }
            if ($imagens_json) {
                $listaImagens = json_decode($imagens_json, true);
                foreach ($listaImagens as $img) {
                    if (!file_exists($img)) {
                        $todasImagensOk = false;
                        break;
                    }
                }
            }

            if (!$todasImagensOk) {
                // Exclui o registro parcial
                $conn->query("DELETE FROM imoveis WHERE id = {$lastId}");
                $conn->rollback();
                // Modal de erro
                echo "
                <div class='modal fade show' style='display:block;' tabindex='-1'>
                  <div class='modal-dialog'>
                    <div class='modal-content'>
                      <div class='modal-header'>
                        <h5 class='modal-title'>Erro</h5>
                      </div>
                      <div class='modal-body'>
                        <p>Houve um problema ao adicionar este imóvel, por favor adicione novamente, caso voltar a acontecer, contate o administrador</p>
                      </div>
                      <div class='modal-footer'>
                        <form method='get' action='admin.php'>
                          <button type='submit' class='btn btn-secondary'>Fechar</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                ";
            } else {
                // Conclui o cadastro
                $conn->commit();
                // Modal de sucesso
                echo "
                <div class='modal fade show' style='display:block;' tabindex='-1'>
                  <div class='modal-dialog'>
                    <div class='modal-content' style='background-color: #d4edda;'>
                      <div class='modal-header'>
                        <h5 class='modal-title'>Sucesso</h5>
                      </div>
                      <div class='modal-body'>
                        <p>Imóvel cadastrado com sucesso! Código: {$lastId}</p>
                        <p><strong>Título:</strong> {$titulo}</p>
                        <p><strong>Tipo:</strong> {$tipo}</p>
                        <!-- Adicione os demais campos desejados -->
                      </div>
                      <div class='modal-footer'>
                        <form method='get' action='admin.php'>
                          <button type='submit' class='btn btn-secondary'>Fechar</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                ";
            }
        } else {
            $conn->rollback();
            echo "Erro ao cadastrar imóvel: " . $conn->error;
        }
    } else {
        echo "Erro: Preencha os campos obrigatórios (Título, Tipo e Imagem Principal) para cadastrar um imóvel.";
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

<body style="background-color:rgb(195, 198, 201);">
    <div class="container py-4">
        <h1 class="text-center mb-4">Cadastro de Imóveis</h1>
        <form method="POST" enctype="multipart/form-data" class="row g-3">
            <!-- Campos obrigatórios -->
            <div class="col-md-6">
                <input type="text" name="titulo" class="form-control" placeholder="Título do imóvel" required>
            </div>
            <div class="col-md-6">
                <select name="tipo" class="form-control" required>
                    <option value="" disabled selected>Selecione o tipo do imóvel</option>
                    <?php foreach ($tipos_imoveis_fixos as $tipo): ?>
                        <option value="<?= $tipo ?>"><?= $tipo ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Imagem Principal</label>
                <input type="file" name="imagem_principal" class="form-control" required>
            </div>

            <!-- Campos opcionais -->
            <div class="col-md-4">
                <input type="number" name="quartos" class="form-control" placeholder="Número de quartos">
            </div>
            <div class="col-md-4">
                <input type="number" name="banheiros" class="form-control" placeholder="Número de banheiros">
            </div>
            <div class="col-md-4">
                <input type="number" name="vagas" class="form-control" placeholder="Vagas de garagem">
            </div>
            <div class="col-md-4">
                <input type="number" step="0.01" name="tamanho" class="form-control" placeholder="Tamanho (m²)">
            </div>
            <div class="col-md-4">
                <input type="text" name="cidade" class="form-control" placeholder="Cidade">
            </div>
            <div class="col-md-4">
                <input type="text" name="bairro" class="form-control" placeholder="Bairro">
            </div>
            <div class="col-12">
                <input type="text" name="endereco" class="form-control" placeholder="Endereço">
            </div>
            <div class="col-12">
                <input type="text" name="preco" class="form-control" placeholder="Preço">
            </div>
            <div class="col-12">
                <textarea name="descricao" class="form-control" placeholder="Descrição do imóvel" rows="4"></textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Outras Imagens</label>
                <input type="file" name="imagens[]" class="form-control" multiple>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary">Cadastrar Imóvel</button>
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