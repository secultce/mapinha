document.addEventListener('click', function (event) {
    if (event.target.classList.contains('edit-organization')) {
        const organizationId = event.target.getAttribute('data-id');
        const token = event.target.getAttribute('data-token');

        fetch(`/api/organizations/${organizationId}`, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-organization').action = `/painel/admin/municipios/${organizationId}/editar`;
                document.getElementById('profile-img').src = data.image || 'img/city.png';
                document.getElementById('organization-description').value = data.description ?? '';
                document.getElementById('organization-site').value = data.extraFields?.site ?? '';
                document.getElementById('organization-phone').value = data.extraFields?.telefone ?? '';
                document.getElementById('organization-email').value = data.extraFields?.email ?? '';

                if (data.extraFields?.hasHousingExperience === true) {
                    document.getElementById('edit-hasHousingExperienceYes').checked = true;
                } else if (data.extraFields?.hasHousingExperience === false) {
                    document.getElementById('edit-hasHousingExperienceNo').checked = true;
                }

                if (data.extraFields?.hasPlhis === true) {
                    document.getElementById('edit-hasPlhisYes').checked = true;
                } else if (data.extraFields?.hasPlhis === false) {
                    document.getElementById('edit-hasPlhisNo').checked = true;
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
