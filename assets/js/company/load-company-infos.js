document.addEventListener('click', function (event) {
    if (event.target.classList.contains('edit-company')) {
        const companyId = event.target.getAttribute('data-id');
        const token = event.target.getAttribute('data-token');

        fetch(`/api/organizations/${companyId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-company').action = `/painel/admin/empresas/${companyId}/editar`;
                document.getElementById('company-name').value = data.name;
                document.getElementById('company-description').value = data.description ?? '';
                document.getElementById('company-cnpj').value = data.extraFields?.cnpj ?? '';
                document.getElementById('company-site').value = data.extraFields?.site ?? '';
                document.getElementById('company-phone').value = data.extraFields?.telefone ?? '';
                document.getElementById('company-email').value = data.extraFields?.email ?? '';

                if (data.extraFields?.tipo === 'Empresa') {
                    document.getElementById('tipo-empresa').checked = true;
                } else if (data.extraFields?.tipo === 'Entidade') {
                    document.getElementById('tipo-entidade').checked = true;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
