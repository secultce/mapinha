import TomSelect from 'tom-select';
document.addEventListener('DOMContentLoaded', function() {
    const yesRadio = document.getElementById('yes');
    const noRadio = document.getElementById('no');
    const architecturalSection = document.getElementById('architecturalSection');

    if (yesRadio && noRadio && architecturalSection) {
        function toggleArchitecturalSection() {
            if (yesRadio.checked) {
                architecturalSection.classList.remove('d-none');
            } else {
                architecturalSection.classList.add('d-none');
            }
        }

        toggleArchitecturalSection();

        yesRadio.addEventListener('change', toggleArchitecturalSection);
        noRadio.addEventListener('change', toggleArchitecturalSection);

        if (typeof TomSelect !== 'undefined') {
            new TomSelect('#physical_accessibility', {
                placeholder: 'Selecione as opções de acessibilidade...',
                create: false,
                maxItems: null,
                plugins: ['remove_button'],
            });
        }
    }
});
