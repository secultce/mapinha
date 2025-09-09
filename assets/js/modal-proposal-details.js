function modalProposalDetails(event) {
    const modal = new bootstrap.Modal('#proposalDetails');

    const data = event.getAttribute('data-proposal');
    const proposal = JSON.parse(data);

    const quantityHouses = proposal.quantity_houses;
    const pricePerHouse = proposal.price_per_house;

    document.querySelector('#region-name').innerHTML = proposal.region;
    document.querySelector('#state-name').innerHTML = proposal.state;
    document.querySelector('#city-name').innerHTML = proposal.city_name;
    document.querySelector('#proposal-name-title').innerHTML = proposal.name;
    document.querySelector('#proposal-name').innerHTML = proposal.name;
    document.querySelector('#project-file').innerHTML = '<a href="/painel/admin/propostas/'+proposal.id+'/projeto" download>Clique aqui para baixar o arquivo do projeto.</a>';
    document.querySelector('#company-name').innerHTML = proposal.company;
    document.querySelector('#created-by').innerHTML = proposal.created_by;
    document.querySelector('#created-at').innerHTML = proposal.created_at;
    document.querySelector('#area-characteristic').innerHTML = proposal.area_option;
    document.querySelector('#area-size').innerHTML = proposal.area_size;
    document.querySelector('#quantity-houses').innerHTML = quantityHouses;
    document.querySelector('#price-per-household').innerHTML = pricePerHouse.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    document.querySelector('#total-price').innerHTML = (quantityHouses * pricePerHouse).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

    if ('pdf' === proposal.map_file.slice(-3)) {
        document.querySelector('#map-file').innerHTML = `
            <object style="min-height: 600px;" data="/painel/admin/propostas/`+proposal.id+`/mapa" type="application/pdf" width="100%">
                <p>Caso o documento não esteja visível, <a href="/painel/admin/propostas/`+proposal.id+`/mapa">clique aqui para acessar o PDF!</a></p>
            </object>
        `;
    } else {
        document.querySelector('#map-file').innerHTML = `
            <img src="/painel/admin/propostas/`+proposal.id+`/mapa" alt="Mapa do projeto" class="img-fluid">
        `;
    }

    modal.show();
}
