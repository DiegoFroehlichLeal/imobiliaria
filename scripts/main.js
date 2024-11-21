document.addEventListener('DOMContentLoaded', async () => {
    const propertiesContainer = document.getElementById('properties-container');
    const filterForm = document.getElementById('filter-form');

    async function loadProperties(filters = {}) {
        const query = new URLSearchParams(filters).toString();
        const response = await fetch(`api.php?${query}`);
        const properties = await response.json();

        propertiesContainer.innerHTML = '';
        properties.forEach(property => {
            propertiesContainer.innerHTML += `
                <div class="col-md-4">
                    <div class="card" style="width: 18rem;">
                        <img src="${property.imagem_principal}" class="card-img-top" alt="${property.titulo}" style= "height: 300px;">
                        <div class="card-body">
                            <h5 class="card-title">${property.titulo}</h5>
                            <p class="card-text">
                                <i class="fa-solid fa-bed"></i> ${property.quartos}| <i class="fa-solid fa-bath"></i> ${property.banheiros} | <i class="fa-solid fa-ruler-combined"></i> ${property.tamanho}m²<br>
                                ${property.bairro}, ${property.cidade}<br>
                                <strong>R$ ${property.preco}</strong>
                            </p>
                            <a href="#" class="btn btn-primary">Ver informações</a>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // Carrega imóveis ao carregar a página
    loadProperties();

    // Aplica filtros ao formulário
    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(filterForm);
        const filters = Object.fromEntries(formData);
        loadProperties(filters);
    });
});

