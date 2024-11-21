<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imobiliária</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://kit.fontawesome.com/761d5bbb70.js" crossorigin="anonymous"></script>
    <script defer src="scripts/main.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
  <!-- Cabeçalho -->
  <header class="py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="h4 m-0">Imobiliária</h1>
      <nav>
        <a href="#" class="text-white text-decoration-none me-3">Home</a>
        <a href="#" class="text-white text-decoration-none me-3">Sobre</a>
        <a href="#" class="text-white text-decoration-none">Contato</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <div class="hero text-center">
    <h2>Encontre o imóvel dos seus sonhos</h2>
    <p>Os melhores imóveis estão aqui</p>
  </div>

  <!-- Filtro de busca -->
  <div class="container filters-section">
    <h3 class="text-center mb-4">Busque seu imóvel</h3>
    <form id="filter-form">
      <div class="row g-3">
        <div class="col-md-4">
          <input type="text" class="form-control" name="cidade" placeholder="Cidade">
        </div>
        <div class="col-md-4">
          <input type="text" class="form-control" name="bairro" placeholder="Bairro">
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" name="max_price" placeholder="Preço Máximo (R$)">
        </div>
        <div class="col-md-4">
          <input type="number" class="form-control" name="quartos" placeholder="Quartos">
        </div>
        <div class="col-md-4">
          
          <input type="number" class="form-control" name="banheiros" placeholder="Banheiros">
        </div>
        <div class="col-md-4">
          <select class="form-select" name="tipo">
            <option selected disabled>Tipo de imóvel</option>
            <option value="casa">Casa</option>
            <option value="apartamento">Apartamento</option>
          </select>
        </div>
      </div>
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
    </form>
  </div>

  <!-- Lista de imóveis -->
  <div class="container my-5">
    <h3 class="text-center mb-4">Imóveis disponíveis</h3>
    <div id="properties-container" class="row g-3">
      <!-- Os resultados serão gerados dinamicamente aqui -->
    </div>
  </div>

  <!-- Rodapé -->
  <footer class="text-center">
    <div class="container">
      <p class="m-0">© 2024 Imobiliária. Todos os direitos reservados.</p>
      <p>Endereço: Rua Exemplo, 123 - Cidade/Estado | Telefone: (99) 9999-9999</p>
    </div>
  </footer>
</body>


</html>