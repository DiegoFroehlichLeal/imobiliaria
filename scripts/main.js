document.addEventListener('DOMContentLoaded', async () => {
    const propertiesContainer = document.getElementById('properties-container');
    const filterForm = document.getElementById('filter-form');

    function formatarPreco(preco) {
        if (!isNaN(preco) && preco !== null && preco !== '') {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(parseFloat(preco));
        } else {
            return preco;
        }
    }

    async function loadProperties(filters = {}) {
        const query = new URLSearchParams(filters).toString();
        const response = await fetch(`api.php?${query}`);
        const properties = await response.json();

        propertiesContainer.innerHTML = '';
        propertiesContainer.classList.add('d-flex', 'flex-wrap', 'justify-content-center');

        if (properties.length === 0) {
            propertiesContainer.innerHTML = '<p class="text-center">Não possuímos imóveis com estas características.</p>';
            return;
        }

        properties.forEach(property => {
            propertiesContainer.innerHTML += `
                <div class="card property-card" data-id="${property.id}">
                    <!-- Seção da imagem -->
                    <div class="card-image">
                        <img src="${property.imagem_principal}" alt="${property.titulo}">
                    </div>
                    <!-- Seção do conteúdo -->
                    <div class="card-content">
                        <!-- Primeira linha: Título e Código -->
                        <h5 class="property-title">
                            ${property.titulo}
                            <span>Cód: ${property.id}</span>
                        </h5>
                        <!-- Segunda linha: Quartos, Banheiros, Vagas, Tamanho -->
                        <div class="property-features">
                            <span><i class="fa fa-bed"></i> ${property.quartos}</span>
                            <span><i class="fa fa-bath"></i> ${property.banheiros}</span>
                            <span><i class="fa fa-car"></i> ${property.vagas}</span>
                            <span><i class="fa fa-ruler-combined"></i> ${property.tamanho} m²</span>
                        </div>
                        <!-- Terceira linha: Bairro -->
                        <div class="property-location">
                            <span>${property.bairro}</span>
                        </div>
                        <!-- Quarta linha: Cidade -->
                        <div class="property-location">
                            <span>${property.cidade}</span>
                        </div>
                        <!-- Última linha: Preço -->
                        <div class="property-price">
                            <button class="btn btn-primary">${formatarPreco(property.preco)}</button>
                        </div>
                    </div>
                </div>
            `;
        });

        // Adiciona evento de clique nos cards
        document.querySelectorAll('.property-card').forEach(card => {
            card.addEventListener('click', () => {
                const propertyId = card.getAttribute('data-id');
                showPropertyDetails(propertyId);
            });
        });
    }

    async function showPropertyDetails(id) {
        const response = await fetch(`property_details.php?id=${id}`);
        const property = await response.json();

        // Garante que property.imagens seja um array
        const imagens = Array.isArray(property.imagens) ? property.imagens : [];

        // Cria o conteúdo do modal
        const modalContent = `
            <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="propertyModalLabel">${property.titulo}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Carrossel de imagens -->
                            <div id="carouselImages" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img src="${property.imagem_principal}" class="d-block w-100" alt="${property.titulo}">
                                    </div>
                                    ${imagens.map(img => `
                                        <div class="carousel-item">
                                            <img src="${img}" class="d-block w-100" alt="${property.titulo}">
                                        </div>
                                    `).join('')}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Próximo</span>
                                </button>
                            </div>
                            <!-- Detalhes do imóvel -->
                            <p class="mt-3">${property.descricao}</p>
                            <ul>
                                <li>Quartos: ${property.quartos}</li>
                                <li>Banheiros: ${property.banheiros}</li>
                                <li>Tamanho: ${property.tamanho} m²</li>
                                <li>Vagas de Garagem: ${property.vagas}</li>
                                <li>Endereço: ${property.endereco}, ${property.bairro}, ${property.cidade}</li>
                                <li>Preço: ${formatarPreco(property.preco)}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Adiciona o modal ao DOM e exibe
        document.body.insertAdjacentHTML('beforeend', modalContent);
        const propertyModal = new bootstrap.Modal(document.getElementById('propertyModal'));
        propertyModal.show();

        // Remove o modal do DOM após ser fechado
        document.getElementById('propertyModal').addEventListener('hidden.bs.modal', function () {
            this.remove();
        });
    }

    // Carrega os imóveis ao iniciar
    loadProperties();

    // Aplica filtros ao formulário
    filterForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const formData = new FormData(filterForm);
        const filters = Object.fromEntries(formData);
        loadProperties(filters);
    });
});
