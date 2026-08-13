function loadAgentData() {
    const agentId = document.getElementById('agent-select').value;
    const token = document.getElementById("user-edit-form").getAttribute("token");

    fetch(`/api/agents/${agentId}`, {
        headers: {
            'Authorization': `Bearer ${token}`
        }
    })
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('short-description').value = data.shortBio;
            document.getElementById('long-description').value = data.longBio;
            document.getElementById('cargo').value = data.extraFields.cargo || '';
            document.getElementById('cpf').value = data.fiscalCode || '';
        })
        .catch(error => console.error('Error:', error));
}

document.getElementById('agent-select').addEventListener('change', loadAgentData);

loadAgentData();