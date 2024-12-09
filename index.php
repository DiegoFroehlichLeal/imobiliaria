<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="resources/LOGO500X500.png" type="image/x-icon">
    <title>Imobiliária</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/761d5bbb70.js" crossorigin="anonymous"></script>
    <script defer src="scripts/main.js"></script>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
<?php include 'templates/header.php'; ?>

  <!-- Hero Section -->
  <div class="hero">
    <h2>A chave para bons negócios.</h2>
  </div>

  <!-- Filtro de busca -->
  <div class="filters-section">
    <h3 class="text-center mb-4">Busque seu imóvel</h3>
    <form id="filter-form">
      <div class="row g-3">
        <div class="col-md-4">
          <input type="number" class="form-control" name="codigo" placeholder="Código do Imóvel">
        </div>
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

  <!-- Seção de banners de destaque -->
  <div id="featuredBanners" class="carousel slide my-5 mx-auto" data-bs-ride="carousel">
    <div class="carousel-inner">
      
      <div class="carousel-item active">
        <img src="banners/FERIASISIMOVEIS.png" class="d-block w-100" alt="Banner 2">
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#featuredBanners" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#featuredBanners" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Próximo</span>
    </button>
  </div>

  <!-- Lista de imóveis -->
  <div class="container my-5">
    <h3 class="text-center mb-4">Imóveis disponíveis</h3>
    <div id="properties-container" class="row g-3">
      <!-- Os resultados serão gerados dinamicamente aqui -->
    </div>
  </div>

<?php include 'templates/footer.php'; ?>
</body>

</html>