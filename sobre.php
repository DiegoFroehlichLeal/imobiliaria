<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Sobre - Imobiliária</title>
    <!-- Meta tags para responsividade -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fontes e ícones -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900&display=swap">
    <script src="https://kit.fontawesome.com/761d5bbb70.js" crossorigin="anonymous"></script>
    <!-- Seu arquivo CSS -->
    <link rel="stylesheet" href="styles/styles.css">
    <!-- Novo arquivo CSS para a página Sobre -->
    <link rel="stylesheet" href="styles/sobre_styles.css">
</head>
<body>
    <div class="wrapper">
        <?php include 'templates/header.php'; ?>

        <div class="content my-5">
            <div class="container sobre-container">
                <h1 class="text-center mb-4">Quem Somos</h1>
                <div class="row align-items-center">
                    <!-- Primeira coluna: Texto -->
                    <div class="col-md-6">
                        <p>Visando um atendimento de qualidade e acima de tudo transparência, a IS IMÓVEIS chegou à Jaraguá do Sul com o objetivo de transformar sonhos em realizações, buscando sempre a satisfação dos nossos clientes.</p>
                        <p>Está localizada em um dos bairros mais antigos da cidade, o bairro Jaraguá Esquerdo, na Rua João Carlos Stein, 593, bem em frente ao Estádio João Marcatto (Juventus), com fácil estacionamento.</p>
                        <p>Contamos com a parceria de construtoras e demais parceiros, que juntos garantem a segurança de um bom negócio.</p>
                        <p>Oferecemos os seguintes serviços:</p>
                        <ul>
                            <li>Compra;</li>
                            <li>Venda;</li>
                            <li>Avaliação de Imóveis;</li>
                            <li>Documentação (Contratos, Escrituras, Averbações...);</li>
                            <li>Venda de Imóveis Caixa recuperados de Financiamento.</li>
                        </ul>
                        <p>Venha nos fazer uma visita!</p>
                        <p>Teremos imensa satisfação em recebê-lo(a)!</p>
                    </div>
                    <!-- Segunda coluna: Imagens -->
                    <div class="col-md-6 text-center">
                        <img src="resources/LOGO500X500.png" alt="Foto da fachada" class="img-fluid mb-3">
                        <img src="resources/LOGO500X500.png" alt="Foto do interior" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>

        <?php include 'templates/footer.php'; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
</body>
</html>